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

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
error_reporting(E_ALL);
ini_set('display_errors', 1);

$msg = '';
// Ambil data barang berdasarkan kode
$kode = isset($_GET['kode']) ? $_GET['kode'] : '';
$data = null;

if ($kode) {
  $stmt = mysqli_prepare($conn, "SELECT * FROM t_master_barang WHERE kode_barang = ?");
  mysqli_stmt_bind_param($stmt, "s", $kode);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $data = mysqli_fetch_assoc($result);
  mysqli_stmt_close($stmt);

  if (!$data) {
    $msg = "Data barang tidak ditemukan.";
  }
} else {
  $msg = "Kode barang tidak ditemukan.";
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kode_barang = isset($_POST['kode_barang']) ? trim($_POST['kode_barang']) : '';
  $nama_barang = isset($_POST['nama_barang']) ? trim($_POST['nama_barang']) : '';
  $harga_jual  = isset($_POST['harga_jual']) ? (int)$_POST['harga_jual'] : 0;
  $harga_beli  = isset($_POST['harga_beli']) ? (int)$_POST['harga_beli'] : 0;
  $satuan      = isset($_POST['satuan']) ? trim($_POST['satuan']) : '';
  $kategori    = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
  $stok        = isset($_POST['stok']) ? (int)$_POST['stok'] : 0;

  if ($kode_barang && $nama_barang && $harga_jual > 0 && $harga_beli > 0 && $satuan && $kategori && $stok >= 0) {
    $stmt = mysqli_prepare($conn, "UPDATE t_master_barang SET nama_barang=?, harga_jual=?, harga_beli=?, satuan=?, kategori=?, stok=? WHERE kode_barang=?");
    mysqli_stmt_bind_param($stmt, "siissis", $nama_barang, $harga_jual, $harga_beli, $satuan, $kategori, $stok, $kode_barang);
    if (mysqli_stmt_execute($stmt)) {
      $msg = "Produk berhasil diupdate.";
      header("Location: admin.php?msg=" . urlencode($msg));
      exit;
    } else {
      $msg = "Gagal update produk.";
    }
    mysqli_stmt_close($stmt);
  } else {
    $msg = "Data tidak lengkap atau salah.";
  }
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
  <title>Edit Produk</title>
</head>

<body>
  <main class="container">
    <h1>Edit Produk</h1>
    <?php if ($msg): ?>
      <article style="color:red"><?= htmlspecialchars($msg) ?></article>
    <?php endif; ?>
    <?php if ($data): ?>
      <form method="post" action="">
        <input type="hidden" name="kode_barang" value="<?= htmlspecialchars($data['kode_barang']) ?>">
        <label>
          Kode Barang
          <input type="text" value="<?= htmlspecialchars($data['kode_barang']) ?>" disabled>
        </label>
        <label>
          Nama Barang
          <input type="text" name="nama_barang" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
        </label>
        <label>
          Harga Jual
          <input type="number" name="harga_jual" min="1" value="<?= htmlspecialchars($data['harga_jual']) ?>" required>
        </label>
        <label>
          Harga Beli
          <input type="number" name="harga_beli" min="1" value="<?= htmlspecialchars($data['harga_beli']) ?>" required>
        </label>
        <label>
          Satuan
          <input type="text" name="satuan" value="<?= htmlspecialchars($data['satuan']) ?>" required>
        </label>
        <label>
          Kategori
          <input type="text" name="kategori" value="<?= htmlspecialchars($data['kategori']) ?>" required>
        </label>
        <label>
          Stok
          <input type="number" name="stok" min="0" value="<?= htmlspecialchars($data['stok']) ?>" required>
        </label>
        <button type="submit">Update</button>
        <a href="admin.php" role="button" class="secondary">Kembali</a>
      </form>
    <?php endif; ?>
  </main>
</body>

</html>