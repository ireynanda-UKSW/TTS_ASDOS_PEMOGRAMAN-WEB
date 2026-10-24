<?php
require 'config.php';
require 'layout.php';

$_SESSION['cart'] ??= [];
$cart =& $_SESSION['cart'];
$err = '';
$sukses = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hapus'])) {
        unset($cart[(int)$_POST['hapus']]);
        header('Location: keranjang.php');
        exit;
    }
    if (isset($_POST['perbarui'])) {
        foreach (($_POST['qty'] ?? []) as $id => $n) {
            $n = (int)$n;
            if ($n <= 0) unset($cart[(int)$id]); else $cart[(int)$id] = $n;
        }
        header('Location: keranjang.php');
        exit;
    }
    if (isset($_POST['checkout'])) {
        $nama = trim($_POST['nama'] ?? '');
        $tel = trim($_POST['telepon'] ?? '');
        $alamat = trim($_POST['alamat'] ?? '');
        if ($nama === '' || $tel === '' || $alamat === '') {
            $err = 'Lengkapi nama, telepon, dan alamat pengiriman.';
        } elseif (!$cart) {
            $err = 'Keranjang masih kosong.';
        } else {
            try {
                $pdo->beginTransaction();
                $total = 0;
                $baris = [];
                foreach ($cart as $id => $n) {
                    $s = $pdo->prepare('SELECT * FROM produk WHERE id = ? FOR UPDATE');
                    $s->execute([$id]);
                    $p = $s->fetch();
                    if (!$p || $p['stok'] < $n) {
                        throw new RuntimeException('Stok ' . ($p['nama'] ?? 'produk') . ' tidak cukup. Kurangi jumlahnya.');
                    }
                    $total += $p['harga'] * $n;
                    $baris[] = [$p, $n];
                }
                $pdo->prepare('INSERT INTO pesanan (nama, telepon, alamat, total) VALUES (?,?,?,?)')
                    ->execute([$nama, $tel, $alamat, $total]);
                $oid = (int)$pdo->lastInsertId();
                foreach ($baris as [$p, $n]) {
                    $pdo->prepare('INSERT INTO pesanan_item (pesanan_id, produk_id, nama_produk, harga, jumlah) VALUES (?,?,?,?,?)')
                        ->execute([$oid, $p['id'], $p['nama'], $p['harga'], $n]);
                    $pdo->prepare('UPDATE produk SET stok = stok - ? WHERE id = ?')->execute([$n, $p['id']]);
                }
                $pdo->commit();
                $cart = [];
                $sukses = $oid;
            } catch (RuntimeException $ex) {
                $pdo->rollBack();
                $err = $ex->getMessage();
            }
        }
    }
}

$items = [];
$total = 0;
if ($cart) {
    $in = implode(',', array_fill(0, count($cart), '?'));
    $s = $pdo->prepare("SELECT * FROM produk WHERE id IN ($in)");
    $s->execute(array_keys($cart));
    foreach ($s->fetchAll() as $p) {
        $p['qty'] = min($cart[$p['id']], max(1, (int)$p['stok']));
        $p['sub'] = $p['qty'] * $p['harga'];
        $total += $p['sub'];
        $items[] = $p;
    }
}

head('Keranjang');
?>
<h1>Keranjang</h1>

<?php if ($sukses): ?>
  <div class="pesan ok">Pesanan #<?= $sukses ?> diterima. Kami akan menghubungi Anda lewat nomor telepon yang diisi.</div>
  <a class="btn" href="index.php">Lanjut belanja</a>
<?php elseif (!$items): ?>
  <p class="kosong">Keranjang masih kosong. <a href="index.php">Lihat katalog</a> untuk mulai memilih.</p>
<?php else: ?>
  <?php if ($err): ?><div class="pesan"><?= e($err) ?></div><?php endif; ?>
  <div class="kolom">
    <form method="post" class="panel">
      <h2>Barang</h2>
      <table>
        <tr><th>Produk</th><th>Jumlah</th><th>Subtotal</th><th></th></tr>
        <?php foreach ($items as $p): ?>
        <tr>
          <td><strong><?= e($p['nama']) ?></strong><br><span class="kat"><?= rp($p['harga']) ?></span></td>
          <td><input type="number" min="0" max="<?= (int)$p['stok'] ?>" name="qty[<?= $p['id'] ?>]" value="<?= $p['qty'] ?>" aria-label="Jumlah"></td>
          <td><?= rp($p['sub']) ?></td>
          <td><button class="btn ghost small" name="hapus" value="<?= $p['id'] ?>">Hapus</button></td>
        </tr>
        <?php endforeach; ?>
      </table>
      <div class="total"><span>Total</span><span><?= rp($total) ?></span></div>
      <p><button class="btn ghost" name="perbarui" value="1">Perbarui jumlah</button></p>
    </form>

    <form method="post" class="panel form-grid">
      <h2>Data pengiriman</h2>
      <label>Nama lengkap <input name="nama" required value="<?= e($_POST['nama'] ?? '') ?>"></label>
      <label>Nomor telepon <input name="telepon" required value="<?= e($_POST['telepon'] ?? '') ?>"></label>
      <label>Alamat <textarea name="alamat" rows="3" required><?= e($_POST['alamat'] ?? '') ?></textarea></label>
      <button class="btn" name="checkout" value="1">Pesan sekarang</button>
    </form>
  </div>
<?php endif; ?>
<?php foot(); ?>
