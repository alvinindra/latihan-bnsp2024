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
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body>
  <main class="container mx-auto">
    <div class="max-w-md mx-auto">
      <a href="index.php" class="w-full"><button class="!py-2 w-full secondary">Kembali</button></a>
      <h1 class="!mt-4">Form Pemesanan Produk</h1>
      <form id="orderForm" method="post" action="proses_order.php">
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
          Nama Pembeli <span style="color: red;">*</span>
          <input type="text" name="nama_konsumen" required>
        </label>
        <label>
          Jumlah Beli <span style="color: red;">*</span>
          <input type="number" name="jumlah_pesanan" min="1" required>
        </label>
        <label>
          Nomor Whatsapp <span style="color: red;">*</span>
          <input type="text" name="nomor_whatsapp" required>
        </label>
        <label>
          Cara Bayar
          <select name="cara_bayar" required>
            <option value="COD" selected>COD (Bayar di Tempat)</option>
            <option value="Transfer Bank">Transfer Bank</option>
            <option value="E-Wallet">E-Wallet</option>
          </select>
        </label>
        <label>
          Cara Pengiriman
          <select name="cara_kirim" required>
            <option value="Kurir">Kurir</option>
            <option value="Ambil Sendiri">Ambil Sendiri</option>
            <option value="Ekspedisi">Ekspedisi</option>
            <option value="COD" selected>COD</option>
          </select>
        </label>
        <button type="submit">Pesan Sekarang</button>
      </form>
      <script>
        document.getElementById('orderForm').addEventListener('submit', function(e) {
          e.preventDefault();
          const nama = document.querySelector('[name="nama_konsumen"]').value;
          const jumlah = document.querySelector('[name="jumlah_pesanan"]').value;
          const wa = document.querySelector('[name="nomor_whatsapp"]').value;
          const barang = "<?= htmlspecialchars($nama_barang) ?>";
          const bayar = document.querySelector('[name="cara_bayar"]').value;
          const kirim = document.querySelector('[name="cara_kirim"]').value;
          const penjual_wa = "+628174980170"; // WA penjual

          let pesan = `Halo, saya ingin order:\nBarang: ${barang}\nJumlah: ${jumlah}\nNama: ${nama}\nNo. WA: ${wa}\nCara Bayar: ${bayar}\nPengiriman: ${kirim}`;
          let url = `https://wa.me/${penjual_wa}?text=${encodeURIComponent(pesan)}`;
          window.open(url, '_blank');
          this.submit();
        });
      </script>
    </div>
  </main>
</body>

</html>