<?php
// Ambil data produk dari parameter GET (jika ada)
$kode_barang = isset($_GET['kode']) ? $_GET['kode'] : '';
$nama_barang = isset($_GET['nama']) ? $_GET['nama'] : '';
$harga_jual  = isset($_GET['harga']) ? $_GET['harga'] : '';
?>

<!doctype html>
<html data-theme="light" lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light">
  <link rel="stylesheet" href="css/pico.min.css">
  <title>Order Produk</title>
</head>

<body>
  <main class="container">
    <h1>Form Pemesanan Produk</h1>
    <form method="post" action="proses_order.php">
      <input type="hidden" name="kode_barang" value="<?= htmlspecialchars($kode_barang) ?>">
      <input type="hidden" name="nama_barang" value="<?= htmlspecialchars($nama_barang) ?>">

      <label>
        Nama Barang
        <input type="text" value="<?= htmlspecialchars($nama_barang) ?>" disabled>
      </label>
      <label>
        Harga Jual
        <input type="text" value="Rp<?= number_format($harga_jual, 0, ',', '.') ?>" disabled>
      </label>
      <label>
        Nama Pembeli
        <input type="text" name="nama_konsumen" required>
      </label>
      <label>
        Jumlah Beli
        <input type="number" name="jumlah_pesanan" min="1" required>
      </label>
      <label>
        Nomor Whatsapp
        <input type="text" name="nomor_whatsapp" required>
      </label>
      <button type="submit">Pesan Sekarang</button>
      <a href="index.php" role="button" class="secondary">Kembali</a>
    </form>
  </main>
</body>

</html>