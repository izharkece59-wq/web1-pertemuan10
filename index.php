<?php
require 'koneksi.php';
$sql = 'SELECT p.id_produk, p.nama_produk, p.harga, p.stok, k.nama_kategori
        FROM produk AS p
        INNER JOIN kategori AS k ON p.id_kategori = k.id_kategori
        ORDER BY p.id_produk';
$hasil = mysqli_query($koneksi, $sql);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 30px; color: #333; }
        .container { max-width: 900px; margin: 0 auto; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        h1 { margin-top: 0; color: #2c3e50; font-size: 24px; border-bottom: 2px solid #eaeaea; padding-bottom: 10px; }
        .btn { display: inline-block; background: #3498db; color: #fff; padding: 8px 14px; text-decoration: none; border-radius: 4px; font-size: 14px; transition: background 0.2s; }
        .btn:hover { background: #2980b9; }
        .btn-danger { background: #e74c3c; border: none; cursor: pointer; padding: 6px 10px; border-radius: 4px; color: #fff; font-size: 13px; }
        .btn-danger:hover { background: #c0392b; }
        .btn-warning { background: #f39c12; color: #fff; padding: 6px 10px; border-radius: 4px; text-decoration: none; font-size: 13px; }
        .btn-warning:hover { background: #d68910; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #eaeaea; }
        th { background-color: #f8f9fa; color: #2c3e50; font-weight: 600; }
        tr:hover { background-color: #f9f9f9; }
        .action-links { display: flex; gap: 6px; align-items: center; }
        .empty-msg { color: #7f8c8d; font-style: italic; margin-top: 15px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Daftar Produk</h1>
    <p><a href="tambah.php" class="btn">+ Tambah Produk</a></p>
    <table>
        <tr>
            <th>Nama Produk</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
        <?php while ($baris = mysqli_fetch_assoc($hasil)): ?>
        <tr>
            <td><?= e($baris['nama_produk']) ?></td>
            <td><?= e($baris['nama_kategori']) ?></td>
            <td>Rp <?= e(number_format((float) $baris['harga'], 0, ',', '.')) ?></td>
            <td><?= e($baris['stok']) ?></td>
            <td>
                <div class="action-links">
                    <a href="edit.php?id=<?= e($baris['id_produk']) ?>" class="btn-warning">Edit</a>
                    <form method="post" action="hapus.php" style="margin:0;">
                        <input type="hidden" name="id" value="<?= e($baris['id_produk']) ?>">
                        <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                    </form>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php if (mysqli_num_rows($hasil) == 0): ?>
        <p class="empty-msg">Belum ada produk yang tersimpan.</p>
    <?php endif; ?>
</div>
</body>
</html>