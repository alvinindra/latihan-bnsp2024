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

$sql = "SELECT * FROM t_master_barang";
$result = mysqli_query($conn, $sql);
?>

<!doctype html>
<html data-theme="light" lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <link rel="stylesheet" href="css/pico.min.css">
  <title>Admin - Toko Online</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <main class="container">
    <h1>Admin Panel - Toko Online</h1>
    <a href="add_barang.php" class="contrast" style="margin-bottom: 1rem; display:inline-block;">Tambah Produk</a>
    <div class="mt-4 !grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
      <?php if ($result && mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <article>
            <header class="!bg-gray-200">
              <small class="font-semibold"><?php echo $row['nama_barang']; ?></small>
            </header>
            <ul class="px-4">
              <li>Harga: <strong><?php echo "Rp" . number_format($row['harga_jual'], 0, ',', '.'); ?></strong></li>
              <li>Stok: <strong><?php echo $row['stok']; ?></strong></li>
            </ul>
            <footer style="display: flex; gap: 4px; flex-wrap: wrap;">
              <a href="order.php?kode=<?= urlencode($row['kode_barang']) ?>&nama=<?= urlencode($row['nama_barang']) ?>&harga=<?= urlencode($row['harga_jual']) ?>">
                <button>Beli</button>
              </a>
              <a href="edit_barang.php?kode=<?= urlencode($row['kode_barang']) ?>">
                <button class="secondary">Edit</button>
              </a>
              <a href="delete_barang.php?kode=<?= urlencode($row['kode_barang']) ?>" onclick="return confirm('Yakin ingin menghapus produk ini?')">
                <button class="contrast">Delete</button>
              </a>
            </footer>
          </article>
        <?php endwhile; ?>
      <?php else: ?>
        <p>Tidak ada produk tersedia.</p>
      <?php endif; ?>
    </div>
  </main>
</body>

</html>