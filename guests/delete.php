<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';


wajibRole('RESEPSIONIS');

$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM guests WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
       
        echo "<script>alert('Tamu tidak dapat dihapus karena sudah memiliki riwayat reservasi!'); window.location='index.php';</script>";
        exit;
    }
}
header("Location: index.php");
exit;