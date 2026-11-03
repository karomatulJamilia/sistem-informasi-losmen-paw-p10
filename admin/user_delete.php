<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    // Mencegah admin menghapus akunnya sendiri yang sedang login
    if ($id === (int)$_SESSION['user_id']) {
        die("Tidak dapat menghapus akun yang sedang aktif digunakan login.");
    }
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}
header("Location: users.php");
exit;