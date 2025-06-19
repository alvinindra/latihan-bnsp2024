<?php
// Koneksi ke database
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();
  $host = $_ENV['DB_HOST'] ?? 'localhost';
  $user = $_ENV['DB_USERNAME'] ?? 'root';
  $pass = $_ENV['DB_PASSWORD'] ?? '';
  $db   = $_ENV['DB_NAME'] ?? 'db_bnsp2024';
} else {
  // fallback jika .env atau composer tidak ada
  $host = "localhost";
  $user = "root";
  $pass = "";
  $db   = "db_bnsp2024";
}

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

$kode = isset($_GET['kode']) ? $_GET['kode'] : '';

if ($kode) {
  $stmt = mysqli_prepare($conn, "DELETE FROM t_master_barang WHERE kode_barang = ?");
  mysqli_stmt_bind_param($stmt, "s", $kode);
  if (mysqli_stmt_execute($stmt)) {
    $msg = "Produk berhasil dihapus.";
  } else {
    $msg = "Gagal menghapus produk.";
  }
  mysqli_stmt_close($stmt);
} else {
  $msg = "Kode barang tidak ditemukan.";
}

mysqli_close($conn);

// Redirect kembali ke admin.php dengan pesan (opsional)
header("Location: admin.php?msg=" . urlencode($msg));
exit;
