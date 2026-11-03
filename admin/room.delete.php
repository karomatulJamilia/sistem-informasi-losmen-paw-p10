<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM rooms WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Gagal hapus jika kamar sudah terikat dengan data reservasi aktif
        echo "<script>alert('Kamar tidak dapat dihapus karena sudah terikat dengan data reservasi!'); window.location='rooms.php';</script>";
        exit;
    }
}
header("Location: rooms.php");
exit;