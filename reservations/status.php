<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}

$id     = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

$allowed = ['CHECKED_IN', 'CHECKED_OUT', 'CANCELLED'];
if (!in_array($status, $allowed, true)) {
    exit('Status tidak valid.');
}

$stmt = $pdo->prepare("UPDATE reservations SET status = :status WHERE id = :id");
$stmt->execute([':status' => $status, ':id' => $id]);

header('Location: index.php?pesan=status_diubah');
exit;