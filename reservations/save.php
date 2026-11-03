<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}

$guest_id = (int)($_POST['guest_id'] ?? 0);
$room_id  = (int)($_POST['room_id'] ?? 0);
$checkin  = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$user_id  = $_SESSION['user_id'];

if ($checkout <= $checkin) {
    exit('Tanggal check-out harus setelah tanggal check-in.');
}

// Validasi bentrok tanggal kamar[cite: 10]
$stmtCek = $pdo->prepare(
    "SELECT COUNT(*) FROM reservations 
     WHERE room_id = :room_id 
     AND status IN ('BOOKED', 'CHECKED_IN')
     AND checkin < :checkout AND checkout > :checkin"
);
$stmtCek->execute([
    ':room_id'  => $room_id,
    ':checkin'  => $checkin,
    ':checkout' => $checkout
]);
if ($stmtCek->fetchColumn() > 0) {
    exit('Gagal: Kamar sudah dipesan pada rentang tanggal tersebut.');
}

// Ambil tarif master kamar saat ini
$stmtTarif = $pdo->prepare("SELECT rt.tarif FROM rooms r JOIN room_types rt ON rt.id = r.room_type_id WHERE r.id = :room_id");
$stmtTarif->execute([':room_id' => $room_id]);
$tarif = $stmtTarif->fetchColumn();

$kode = 'RSV-' . strtoupper(uniqid());

$sql = "INSERT INTO reservations (kode, guest_id, room_id, user_id, checkin, checkout, tarif, status) 
        VALUES (:kode, :guest_id, :room_id, :user_id, :checkin, :checkout, :tarif, 'BOOKED')";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':kode'     => $kode,
    ':guest_id' => $guest_id,
    ':room_id'  => $room_id,
    ':user_id'  => $user_id,
    ':checkin'  => $checkin,
    ':checkout' => $checkout,
    ':tarif'    => $tarif
]);

header('Location: index.php?pesan=sukses');
exit;