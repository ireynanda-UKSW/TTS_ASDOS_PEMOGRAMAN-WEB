<?php
function head($judul) { ?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($judul) ?> | simakomputer</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=IBM+Plex+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="bar">
  <a class="logo" href="index.php">simakomputer<span>.</span></a>
  <nav>
    <a href="index.php">Katalog</a>
    <a href="keranjang.php">Keranjang <span class="badge"><?= jumlah_keranjang() ?></span></a>
  </nav>
</header>
<main class="wrap">
<?php }

function foot() { ?>
</main>
<footer class="foot">simakomputer, toko komputer. <a href="admin.php">Admin</a></footer>
</body>
</html>
<?php }
