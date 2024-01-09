<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kasir</title>
    <style>
        * {
            margin: 5px;
        }
        form {
            display: block;
        }
        table, tr, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>

<?php

// Inisialisasi produk dalam array
$produk = array(
    array("id" => 1, "nama" => "Mobil Mobilan", "harga" => 10000),
    array("id" => 2, "nama" => "Bola", "harga" => 15000),
    array("id" => 3, "nama" => "Robot", "harga" => 20000)
);

// Inisialisasi session untuk menyimpan keranjang belanja
session_start();

// Memanggil fungsi untuk menangani aksi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["tambah_ke_keranjang"])) {
        tambahKeKeranjang($_POST["produk_id"], $_POST["jumlah"]);
    } elseif (isset($_POST["selesai_transaksi"])) {
        selesaiTransaksi();
    }
}

// Fungsi untuk menambahkan produk ke keranjang
function tambahKeKeranjang($produkId, $jumlah) {
    global $produk;

    // Cek apakah produkId valid
    $produkId = intval($produkId);
    $produkIndex = array_search($produkId, array_column($produk, 'id'));

    if ($produkIndex !== false) {
        // Jika produk sudah ada di keranjang, tambahkan jumlahnya
        $keranjangIndex = array_search($produkId, array_column($_SESSION['keranjang'], 'id'));
        if ($keranjangIndex !== false) {
            $_SESSION['keranjang'][$keranjangIndex]['jumlah'] += $jumlah;
        } else {
            // Jika produk belum ada di keranjang, tambahkan produk baru
            $item = array(
                'id' => $produk[$produkIndex]['id'],
                'nama' => $produk[$produkIndex]['nama'],
                'harga' => $produk[$produkIndex]['harga'],
                'jumlah' => $jumlah
            );
            $_SESSION['keranjang'][] = $item;
        }
    }
}

// Fungsi untuk menyelesaikan transaksi
function selesaiTransaksi() {
    // Logika untuk menyelesaikan transaksi, bisa disesuaikan sesuai kebutuhan
    // Pada contoh ini, kita hanya akan menghapus isi keranjang
    $_SESSION['keranjang'] = array();
    echo '<script>alert("Terima kasih! Transaksi selesai.");</script>';
}
// Fungsi untuk reset table
function resetTable() {
    // Logika untuk menyelesaikan transaksi, bisa disesuaikan sesuai kebutuhan
    // Pada contoh ini, kita hanya akan menghapus isi keranjang
    $_SESSION['keranjang'] = array();
    echo '<script>alert("Tabel Telah di Reset");</script>';
}
// Memproses formulir jika tombol diklik
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["reset_Table"])) {
    resetTable();
}
?>

<h2>Aplikasi Kasir</h2>

<!-- Form untuk menambah item ke keranjang -->
<form method="post" action="">
    <div class="inputProduk">
        <label for="produk_id">Pilih Produk:</label>
        <select name="produk_id">
            <?php
            foreach ($produk as $item) {
                echo "<option value='{$item['id']}'>{$item['nama']} - Rp{$item['harga']}</option>";
            }
            ?>
        </select>
    </div>

    <div class="jumlahInput">
        <label for="jumlah">Jumlah:</label>
        <input type="number" name="jumlah" value="1" required>
    </div>


    <button type="submit" name="tambah_ke_keranjang">Tambah ke Keranjang</button>
</form>

<!-- Tabel untuk menampilkan item di keranjang -->
<table>
    <tr>
        <th>Nama Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
    </tr>
    <?php
    $totalBelanja = 0;
    if (isset($_SESSION['keranjang']) && !empty($_SESSION['keranjang'])) {
        foreach ($_SESSION['keranjang'] as $item) {
            $harga = $item['harga'];
            $hargaAngka = "Rp " . number_format($harga, 0, ',', '.');
            $totalItem = $item['harga'] * $item['jumlah'];
            $totalItemAngka = "Rp " . number_format($totalItem, 0, ',', '.');
            $totalBelanja += $totalItem;
            echo "<tr>
                    <td>{$item['nama']}</td>
                    <td>{$hargaAngka}</td>
                    <td>{$item['jumlah']}</td>
                    <td>{$totalItemAngka}</td>
                </tr>";
        }
    }
    ?>
    <tr>
        <td colspan="3">Total Belanja</td>
        <td><?php echo "Rp " . number_format($totalBelanja, 0, ',', '.'); ?></td>
    </tr>
</table>

<!-- Tombol untuk menyelesaikan pembelian -->
<form method="post" action="">
    <button type="submit" name="selesai_transaksi">Selesai Transaksi</button>
    <button type="submit" name="reset_Table">Reset Tabel</button>

</form>

</body>
</html>
