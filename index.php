<?php
require 'config.php';
require 'layout.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah'])) {
    $id = (int)$_POST['tambah'];
    $s = $pdo->prepare('SELECT stok FROM produk WHERE id = ?');
    $s->execute([$id]);
    $stok = $s->fetchColumn();
    $ada = $_SESSION['cart'][$id] ?? 0;
    if ($stok !== false && $ada < $stok) {
        $_SESSION['cart'][$id] = $ada + 1;
    }
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit;
}

$kat = (int)($_GET['kategori'] ?? 0);
$q = trim($_GET['q'] ?? '');

$sql = 'SELECT p.*, k.nama AS kategori FROM produk p JOIN kategori k ON k.id = p.kategori_id WHERE 1=1';
$par = [];
if ($kat) { $sql .= ' AND p.kategori_id = ?'; $par[] = $kat; }
if ($q !== '') { $sql .= ' AND (p.nama LIKE ? OR p.merek LIKE ? OR p.spesifikasi LIKE ?)'; array_push($par, "%$q%", "%$q%", "%$q%"); }
$sql .= ' ORDER BY p.id DESC';
$s = $pdo->prepare($sql);
$s->execute($par);
$produk = $s->fetchAll();
$kategori = $pdo->query('SELECT * FROM kategori ORDER BY id')->fetchAll();

head('Katalog');
?>
<section class="hero">
  <h1>Perangkat komputer, tanpa ribet.</h1>
  <p>Laptop, komponen, dan periferal pilihan. Pilih, masukkan keranjang, kami siapkan pesanannya.</p>
  <form class="cari" method="get">
    <?php if ($kat): ?><input type="hidden" name="kategori" value="<?= $kat ?>"><?php endif; ?>
    <input type="search" name="q" value="<?= e($q) ?>" placeholder="Cari produk atau merek" aria-label="Cari produk">
    <button class="btn" type="submit">Cari</button>
  </form>
</section>

<nav class="chips" aria-label="Kategori">
  <a href="index.php" class="<?= $kat ? '' : 'on' ?>">Semua</a>
  <?php foreach ($kategori as $k): ?>
    <a href="?kategori=<?= $k['id'] ?>" class="<?= $kat == $k['id'] ? 'on' : '' ?>"><?= e($k['nama']) ?></a>
  <?php endforeach; ?>
</nav>

<?php if (!$produk): ?>
  <p class="kosong">Tidak ada produk yang cocok. Coba kata kunci lain atau pilih kategori Semua.</p>
<?php else: ?>
<section class="grid">
  <?php foreach ($produk as $p): ?>
  <article class="card">
    <?php if ($p['foto']): ?>
      <img class="foto" src="<?= e($p['foto']) ?>" alt="<?= e($p['nama']) ?>" loading="lazy">
    <?php else: ?>
      <div class="thumb"><?= e($p['merek']) ?></div>
    <?php endif; ?>
    <div class="body">
      <span class="kat"><?= e($p['kategori']) ?></span>
      <h3><?= e($p['nama']) ?></h3>
      <p class="spek"><?= e($p['spesifikasi']) ?></p>
      <span class="harga"><?= rp($p['harga']) ?></span>
      <span class="stok <?= $p['stok'] <= 5 ? 'rendah' : '' ?>">
        <?= $p['stok'] > 0 ? 'Stok ' . (int)$p['stok'] : 'Stok habis' ?>
      </span>
      <form method="post">
        <button class="btn" name="tambah" value="<?= (int)$p['id'] ?>" <?= $p['stok'] < 1 ? 'disabled' : '' ?>>
          Tambah ke keranjang
        </button>
      </form>
    </div>
  </article>
  <?php endforeach; ?>
</section>
<?php endif; ?>
<?php foot(); ?>