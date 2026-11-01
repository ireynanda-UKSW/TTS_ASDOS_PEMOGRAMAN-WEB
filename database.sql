CREATE DATABASE IF NOT EXISTS toko_komputer CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE toko_komputer;

DROP TABLE IF EXISTS pesanan_item;
DROP TABLE IF EXISTS pesanan;
DROP TABLE IF EXISTS produk;
DROP TABLE IF EXISTS kategori;

CREATE TABLE kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(60) NOT NULL
);

CREATE TABLE produk (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kategori_id INT NOT NULL,
  nama VARCHAR(120) NOT NULL,
  merek VARCHAR(40) NOT NULL,
  spesifikasi VARCHAR(255) NOT NULL DEFAULT '',
  foto VARCHAR(500) NULL,
  harga DECIMAL(12,0) NOT NULL,
  stok INT NOT NULL DEFAULT 0,
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (kategori_id) REFERENCES kategori(id)
);

CREATE TABLE pesanan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  telepon VARCHAR(30) NOT NULL,
  alamat TEXT NOT NULL,
  total DECIMAL(12,0) NOT NULL,
  status ENUM('baru','diproses','selesai') NOT NULL DEFAULT 'baru',
  dibuat TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pesanan_item (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pesanan_id INT NOT NULL,
  produk_id INT NULL,
  nama_produk VARCHAR(120) NOT NULL,
  harga DECIMAL(12,0) NOT NULL,
  jumlah INT NOT NULL,
  FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
  FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE SET NULL
);

INSERT INTO kategori (id, nama) VALUES
(1,'Laptop'),(2,'Komponen'),(3,'Periferal'),(4,'Monitor'),(5,'Penyimpanan'),(6,'Jaringan');

INSERT INTO produk (kategori_id, nama, merek, spesifikasi, harga, stok) VALUES
(1,'Ultrabook 14 inci','Lenovo','Ryzen 5, RAM 16 GB, SSD 512 GB, layar IPS',9499000,8),
(1,'Laptop Gaming 15 inci','Asus','Core i7, RAM 16 GB, SSD 1 TB, RTX 4050',17999000,4),
(1,'Laptop Pelajar 14 inci','Acer','Core i3, RAM 8 GB, SSD 256 GB',5799000,12),
(2,'Prosesor 6 core','AMD','6 core 12 thread, AM5, boost hingga 5,0 GHz',2899000,10),
(2,'RAM DDR5 16 GB','Corsair','DDR5 5600 MHz, 1 keping 16 GB',849000,25),
(2,'Motherboard ATX','MSI','Socket AM5, DDR5, PCIe 4.0, WiFi',2599000,6),
(3,'Keyboard Mekanikal','Keychron','Layout 75%, hot-swap, Bluetooth dan kabel',1349000,15),
(3,'Mouse Nirkabel Ringan','Logitech','Sensor 25K DPI, bobot 60 g, baterai 70 jam',1099000,20),
(4,'Monitor 24 inci IPS','LG','Full HD, 100 Hz, HDMI, bezel tipis',1699000,9),
(4,'Monitor Lengkung 27 inci','Samsung','QHD, 165 Hz, panel VA',3299000,5),
(5,'SSD NVMe 1 TB','Samsung','PCIe 4.0, baca hingga 7.000 MB/s',1399000,18),
(6,'Router WiFi 6','TP-Link','AX3000, dual band, 4 antena',899000,14);