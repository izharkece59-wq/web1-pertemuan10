<?php
require 'koneksi.php';
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_VALIDATE_INT) : false;
if ($id === false || $id === null || $id < 1) {
    http_response_code(400);
    exit('ID produk tidak valid.');
}

$ambil = mysqli_prepare($koneksi, 'SELECT id_kategori, nama_produk, harga, stok FROM produk WHERE id_produk = ?');
mysqli_stmt_bind_param($ambil, 'i', $id);
mysqli_stmt_execute($ambil);
$hasilProduk = mysqli_stmt_get_result($ambil);
$produk = mysqli_fetch_assoc($hasilProduk);
mysqli_stmt_close($ambil);

if (!$produk) {
    http_response_code(404);
    exit('Produk tidak ditemukan.');
}

$kategori = mysqli_query($koneksi, 'SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori');
$error = '';
$nama = isset($_POST['nama_produk']) ? trim($_POST['nama_produk']) : $produk['nama_produk'];
$hargaInput = isset($_POST['harga']) ? $_POST['harga'] : $produk['harga'];
$stokInput = isset($_POST['stok']) ? $_POST['stok'] : $produk['stok'];
$kategoriInput = isset($_POST['id_kategori']) ? $_POST['id_kategori'] : $produk['id_kategori'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $harga = filter_var($hargaInput, FILTER_VALIDATE_FLOAT);
    $stok = filter_var($stokInput, FILTER_VALIDATE_INT);
    $idKategori = filter_var($kategoriInput, FILTER_VALIDATE_INT);

    if ($nama === '' || $harga === false || $harga < 0 || $stok === false || $stok < 0 || $idKategori === false || $idKategori < 1) {
        $error = 'Isi semua data dengan benar.';
    } else {
        $cek = mysqli_prepare($koneksi, 'SELECT COUNT(*) FROM kategori WHERE id_kategori = ?');
        mysqli_stmt_bind_param($cek, 'i', $idKategori);
        mysqli_stmt_execute($cek);
        mysqli_stmt_bind_result($cek, $jumlahKategori);
        mysqli_stmt_fetch($cek);
        mysqli_stmt_close($cek);

        if ((int) $jumlahKategori === 0) {
            $error = 'Kategori tidak ditemukan.';
        } else {
            $sql = 'UPDATE produk SET id_kategori = ?, nama_produk = ?, harga = ?, stok = ? WHERE id_produk = ?';
            $stmt = mysqli_prepare($koneksi, $sql);
            mysqli_stmt_bind_param($stmt, 'isdii', $idKategori, $nama, $harga, $stok, $id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            header('Location: index.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px; color: #333; }
        .container { max-width: 500px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h1 { margin-top: 0; color: #2c3e50; font-size: 22px; border-bottom: 2px solid #eaeaea; padding-bottom: 10px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #555; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-size: 14px; }
        input:focus, select:focus { border-color: #3498db; outline: none; }
        .btn { background: #f39c12; color: #fff; border: none; padding: 10px 15px; border-radius: 4px; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn:hover { background: #d68910; }
        .btn-back { background: #95a5a6; color: #fff; text-decoration: none; padding: 10px 15px; border-radius: 4px; display: inline-block; font-size: 14px; margin-left: 8px; }
        .btn-back:hover { background: #7f8c8d; }
        .error-msg { background: #fde8e8; color: #c0392b; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 14px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit Produk</h1>
    <?php if ($error !== ''): ?><div class="error-msg"><?= e($error) ?></div><?php endif; ?>
    <form method="post">
        <div class="form-group">
            <label>Nama Produk:</label>
            <input type="text" name="nama_produk" value="<?= e($nama) ?>" required>
        </div>
        <div class="form-group">
            <label>Harga (Rp):</label>
            <input type="number" name="harga" min="0.01" step="0.01" value="<?= e($hargaInput) ?>" required>
        </div>
        <div class="form-group">
            <label>Stok:</label>
            <input type="number" name="stok" min="0" step="1" value="<?= e($stokInput) ?>" required>
        </div>
        <div class="form-group">
            <label>Kategori:</label>
            <select name="id_kategori" required>
                <?php while ($item = mysqli_fetch_assoc($kategori)): ?>
                <?php $terpilih = (string) $kategoriInput === (string) $item['id_kategori']; ?>
                <option value="<?= e($item['id_kategori']) ?>" <?= $terpilih ? 'selected' : '' ?>>
                    <?= e($item['nama_kategori']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div style="margin-top: 20px;">
            <button type="submit" class="btn">Simpan Perubahan</button>
            <a href="index.php" class="btn-back">Kembali</a>
        </div>
    </form>
</div>
</body>
</html>