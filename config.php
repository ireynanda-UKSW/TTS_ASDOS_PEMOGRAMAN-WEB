<?php
session_start();

const DB_HOST = 'localhost';
const DB_NAME = 'toko_komputer';
const DB_USER = 'root';
const DB_PASS = '';

// Ganti sebelum dipakai di server sungguhan.
const ADMIN_USER = 'admin';
const ADMIN_PASS = 'admin123';

$pdo = new PDO(
    'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
    DB_USER,
    DB_PASS,
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
);

if (!$pdo->query("SHOW COLUMNS FROM produk LIKE 'foto'")->fetch()) {
    $pdo->exec('ALTER TABLE produk ADD foto VARCHAR(500) NULL AFTER spesifikasi');
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function rp($n) { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
function jumlah_keranjang() { return array_sum($_SESSION['cart'] ?? []); }

// Validasi link foto (harus http/https). Mengembalikan link, atau null jika kosong/tidak valid.
// Alasan gagal disimpan di $_SESSION['flash'] supaya ditampilkan di admin.
function simpan_link($u) {
    $u = trim((string)$u);
    if ($u === '') return null;
    if (strlen($u) > 500 || !preg_match('#^https?://#i', $u) || !filter_var($u, FILTER_VALIDATE_URL)) {
        $_SESSION['flash'] = 'Link foto tidak valid. Harus diawali http:// atau https:// dan maksimal 500 karakter.';
        return null;
    }
    return $u;
}