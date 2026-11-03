<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}

$nama = trim($_POST['nama'] ?? '');
if ($nama === '' || mb_strlen($nama) > 120) {
    exit('Nama wajib diisi dan maksimal 120 karakter.');
}

if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    exit('Format email tidak valid.');
}

$data = [
    'nama'         => $nama,
    'no_identitas' => $_POST['no_identitas'] ?? '',
    'telepon'      => $_POST['telepon'] ?? '',
    'email'        => $_POST['email'] ?? '',
];

try {
    simpanTamu($pdo, $data);
    header('Location: index.php?pesan=tersimpan');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Data belum berhasil disimpan.');
}