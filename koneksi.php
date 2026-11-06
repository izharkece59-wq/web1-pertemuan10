<?php
$host = '127.0.0.1';
$namaDatabase = 'web1_pertemuan09';
$pengguna = 'root';
$kataSandi = '';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $koneksi = mysqli_connect($host, $pengguna, $kataSandi, $namaDatabase);
    mysqli_set_charset($koneksi, 'utf8mb4');
} catch (mysqli_sql_exception $e) {
    exit('Koneksi database gagal. Periksa server, nama database, dan akun lokal.');
}

function e($nilai) {
    return htmlspecialchars((string) $nilai, ENT_QUOTES, 'UTF-8');
}