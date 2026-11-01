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

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function rp($n) { return 'Rp ' . number_format((float)$n, 0, ',', '.'); }
function jumlah_keranjang() { return array_sum($_SESSION['cart'] ?? []); }

// Simpan foto ke folder uploads/. Mengembalikan nama file, atau null jika tidak ada/gagal.
function simpan_foto($f) {
    if (!$f || $f['error'] !== UPLOAD_ERR_OK || $f['size'] > 2 * 1024 * 1024) return null; // maks 2 MB
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
    $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime] ?? null;
    if (!$ext) return null; // hanya JPG/PNG/WEBP
    $nama = bin2hex(random_bytes(8)) . '.' . $ext;
    return move_uploaded_file($f['tmp_name'], __DIR__ . '/uploads/' . $nama) ? $nama : null;
}

function hapus_foto($nama) {
    if ($nama && is_file(__DIR__ . '/uploads/' . basename($nama))) unlink(__DIR__ . '/uploads/' . basename($nama));
}