<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_bnsp2024";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}
// Ambil data dari form
$kode_barang     = isset($_POST['kode_barang']) ? $_POST['kode_barang'] : '';
$nama_konsumen    = isset($_POST['nama_konsumen']) ? $_POST['nama_konsumen'] : '';
$jumlah          = isset($_POST['jumlah_pesanan']) ? (int)$_POST['jumlah_pesanan'] : 0;
$nomor_whatsapp  = isset($_POST['nomor_whatsapp']) ? $_POST['nomor_whatsapp'] : '';

if ($kode_barang && $nama_konsumen && $jumlah > 0 && $nomor_whatsapp) {
  $sql = "INSERT INTO t_order (tgl_order, kode_barang, nama_konsumen, jumlah_pesanan, nomor_whatsapp)
            VALUES (NOW(), ?, ?, ?, ?)";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "ssis", $kode_barang, $nama_konsumen, $jumlah, $nomor_whatsapp);

  if (mysqli_stmt_execute($stmt)) {
    // Kurangi stok barang
    $update = mysqli_query($conn, "UPDATE t_master_barang SET stok = stok - $jumlah WHERE kode_barang = '$kode_barang' AND stok >= $jumlah");
    if (mysqli_affected_rows($conn) > 0) {
      $msg = "Pesanan berhasil! Terima kasih, $nama_konsumen.";
    } else {
      $msg = "Stok tidak mencukupi atau terjadi kesalahan saat update stok.";
    }
  } else {
    $msg = "Gagal menyimpan pesanan.";
  }
  mysqli_stmt_close($stmt);
} else {
  $msg = "Data tidak lengkap.";
}
mysqli_close($conn);
?>

<!doctype html>
<html data-theme="light" lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <link rel="stylesheet" href="css/pico.min.css">
  <title>Proses Order</title>
</head>

<body>
  <main class="container">
    <h1>Status Pemesanan</h1>
    <article>
      <p><?= htmlspecialchars($msg) ?></p>
      <a href="index.php" role="button">Kembali ke Beranda</a>
    </article>
  </main>
</body>

</html>