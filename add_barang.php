<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_bnsp2024";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $kode_barang = isset($_POST['kode_barang']) ? trim($_POST['kode_barang']) : '';
  $nama_barang = isset($_POST['nama_barang']) ? trim($_POST['nama_barang']) : '';
  $harga_jual  = isset($_POST['harga_jual']) ? (int)$_POST['harga_jual'] : 0;
  $harga_beli  = isset($_POST['harga_beli']) ? (int)$_POST['harga_beli'] : 0;
  $satuan      = isset($_POST['satuan']) ? trim($_POST['satuan']) : '';
  $kategori    = isset($_POST['kategori']) ? trim($_POST['kategori']) : '';
  $stok        = isset($_POST['stok']) ? (int)$_POST['stok'] : 0;

  if ($kode_barang && $nama_barang && $harga_jual > 0 && $harga_beli > 0 && $satuan && $kategori && $stok >= 0) {
    $stmt = mysqli_prepare($conn, "INSERT INTO t_master_barang (kode_barang, nama_barang, harga_jual, harga_beli, satuan, kategori, stok) VALUES (?, ?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssiiisi", $kode_barang, $nama_barang, $harga_jual, $harga_beli, $satuan, $kategori, $stok);
    if (mysqli_stmt_execute($stmt)) {
      $msg = "Produk berhasil ditambahkan.";
      header("Location: admin.php?msg=" . urlencode($msg));
      exit;
    } else {
      $msg = "Gagal menambah produk.";
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
  <title>Tambah Produk</title>
</head>

<body>
  <main class="container">
    <h1>Tambah Produk Baru</h1>
    <?php if ($msg): ?>
      <article style="color:red"><?= htmlspecialchars($msg) ?></article>
    <?php endif; ?>
    <form method="post" action="">
      <label>
        Kode Barang
        <input type="text" placeholder="Contoh: PKA001" name="kode_barang" required>
      </label>
      <label>
        Nama Barang
        <input type="text" name="nama_barang" required>
      </label>
      <label>
        Harga Jual
        <input type="number" name="harga_jual" min="1" required>
      </label>
      <label>
        Harga Beli
        <input type="number" name="harga_beli" min="1" required>
      </label>
      <label>
        Satuan
        <input type="text" name="satuan" required>
      </label>
      <label>
        Kategori
        <input type="text" name="kategori" required>
      </label>
      <label>
        Stok
        <input type="number" name="stok" min="0" required>
      </label>
      <button type="submit">Simpan</button>
      <a href="admin.php" role="button" class="secondary">Kembali</a>
    </form>
  </main>
</body>

</html>