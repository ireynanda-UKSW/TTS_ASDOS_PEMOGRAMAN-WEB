<?php
require 'config.php';
require 'layout.php';

if (isset($_GET['keluar'])) { unset($_SESSION['admin']); header('Location: admin.php'); exit; }

$err = '';
if (isset($_POST['masuk'])) {
    if (hash_equals(ADMIN_USER, (string)($_POST['user'] ?? '')) && hash_equals(ADMIN_PASS, (string)($_POST['pass'] ?? ''))) {
        $_SESSION['admin'] = 1;
        header('Location: admin.php');
        exit;
    }
    $err = 'Username atau password salah.';
}

if (empty($_SESSION['admin'])) {
    head('Masuk admin'); ?>
    <form method="post" class="panel form-grid" style="max-width:380px">
      <h2>Masuk admin</h2>
      <?php if ($err): ?><div class="pesan"><?= e($err) ?></div><?php endif; ?>
      <label>Username <input name="user" required></label>
      <label>Password <input type="password" name="pass" required></label>
      <button class="btn" name="masuk" value="1">Masuk</button>
    </form>
    <?php foot(); exit;
}

$tab = ($_GET['tab'] ?? 'produk') === 'pesanan' ? 'pesanan' : 'produk';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['tambah'])) {
        $pdo->prepare('INSERT INTO produk (kategori_id, nama, merek, spesifikasi, harga, stok) VALUES (?,?,?,?,?,?)')
            ->execute([(int)$_POST['kategori_id'], trim($_POST['nama']), trim($_POST['merek']), trim($_POST['spesifikasi']), (int)$_POST['harga'], (int)$_POST['stok']]);
    }
    if (isset($_POST['hapus'])) {
        $pdo->prepare('DELETE FROM produk WHERE id = ?')->execute([(int)$_POST['hapus']]);
    }
    if (isset($_POST['simpan'])) {
        $pdo->prepare('UPDATE produk SET harga = ?, stok = ? WHERE id = ?')
            ->execute([(int)$_POST['harga'], (int)$_POST['stok'], (int)$_POST['simpan']]);
    }
    if (isset($_POST['status'])) {
        $st = in_array($_POST['status_baru'], ['baru', 'diproses', 'selesai'], true) ? $_POST['status_baru'] : 'baru';
        $pdo->prepare('UPDATE pesanan SET status = ? WHERE id = ?')->execute([$st, (int)$_POST['status']]);
    }
    header('Location: admin.php?tab=' . $tab);
    exit;
}

$kategori = $pdo->query('SELECT * FROM kategori ORDER BY id')->fetchAll();
head('Admin');
?>
<h1>Admin <a class="btn ghost small" href="?keluar=1">Keluar</a></h1>
<div class="tabs">
  <a href="?tab=produk" class="<?= $tab === 'produk' ? 'on' : '' ?>">Produk</a>
  <a href="?tab=pesanan" class="<?= $tab === 'pesanan' ? 'on' : '' ?>">Pesanan</a>
</div>

<?php if ($tab === 'produk'): ?>
  <form method="post" class="panel form-grid dua">
    <h2 style="grid-column:1/-1">Tambah produk</h2>
    <label>Nama <input name="nama" required></label>
    <label>Merek <input name="merek" required></label>
    <label>Kategori
      <select name="kategori_id"><?php foreach ($kategori as $k): ?>
        <option value="<?= $k['id'] ?>"><?= e($k['nama']) ?></option><?php endforeach; ?>
      </select>
    </label>
    <label>Spesifikasi <input name="spesifikasi"></label>
    <label>Harga (Rp) <input type="number" name="harga" min="0" required></label>
    <label>Stok <input type="number" name="stok" min="0" value="0" required></label>
    <div><button class="btn" name="tambah" value="1">Tambah produk</button></div>
  </form>

  <div class="panel">
    <h2>Daftar produk</h2>
    <table>
      <tr><th>Produk</th><th>Harga</th><th>Stok</th><th></th></tr>
      <?php foreach ($pdo->query('SELECT * FROM produk ORDER BY id DESC') as $p): ?>
      <tr>
        <form method="post">
          <td><strong><?= e($p['nama']) ?></strong><br><span class="kat"><?= e($p['merek']) ?></span></td>
          <td><input type="number" name="harga" value="<?= (int)$p['harga'] ?>" style="width:140px" aria-label="Harga"></td>
          <td><input type="number" name="stok" value="<?= (int)$p['stok'] ?>" aria-label="Stok"></td>
          <td style="white-space:nowrap">
            <button class="btn small" name="simpan" value="<?= $p['id'] ?>">Simpan</button>
            <button class="btn ghost small" name="hapus" value="<?= $p['id'] ?>" onclick="return confirm('Hapus produk ini?')">Hapus</button>
          </td>
        </form>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>

<?php else: ?>
  <div class="panel">
    <h2>Pesanan masuk</h2>
    <?php
    $rows = $pdo->query("SELECT o.*, GROUP_CONCAT(CONCAT(i.nama_produk, ' x', i.jumlah) SEPARATOR ', ') AS isi
        FROM pesanan o LEFT JOIN pesanan_item i ON i.pesanan_id = o.id GROUP BY o.id ORDER BY o.id DESC")->fetchAll();
    if (!$rows): ?><p class="kosong">Belum ada pesanan.</p><?php else: ?>
    <table>
      <tr><th>No</th><th>Pemesan</th><th>Isi</th><th>Total</th><th>Status</th></tr>
      <?php foreach ($rows as $o): ?>
      <tr>
        <td>#<?= $o['id'] ?><br><span class="kat"><?= e($o['dibuat']) ?></span></td>
        <td><?= e($o['nama']) ?><br><span class="kat"><?= e($o['telepon']) ?><br><?= e($o['alamat']) ?></span></td>
        <td><?= e($o['isi']) ?></td>
        <td><?= rp($o['total']) ?></td>
        <td>
          <form method="post" style="display:flex;gap:6px">
            <select name="status_baru" style="width:auto">
              <?php foreach (['baru', 'diproses', 'selesai'] as $st): ?>
                <option value="<?= $st ?>" <?= $o['status'] === $st ? 'selected' : '' ?>><?= ucfirst($st) ?></option>
              <?php endforeach; ?>
            </select>
            <button class="btn small" name="status" value="<?= $o['id'] ?>">Simpan</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php endif; ?>
  </div>
<?php endif; ?>
<?php foot(); ?>
