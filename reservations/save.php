<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/validation.php';

wajibRole(['RESEPSIONIS']);                        

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Metode tidak diizinkan.');
}

$guest_id = (int)($_POST['guest_id'] ?? 0);
$room_id  = (int)($_POST['room_id'] ?? 0);
$checkin  = trim($_POST['checkin'] ?? '');
$checkout = trim($_POST['checkout'] ?? '');
$user_id  = (int)$_SESSION['user_id'];

// FIX 2: validasi format dan urutan tanggal
$errors = validasiRentangTanggal($checkin, $checkout);
if ($errors) {
    http_response_code(422);
    exit(implode("\n", $errors));
}

$tolak = function (int $kode, string $pesan) use ($pdo) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code($kode);
    exit($pesan);
};

try {
    $pdo->beginTransaction();

    // ID tamu harus ada
    $s = $pdo->prepare('SELECT id FROM guests WHERE id = :id');
    $s->execute([':id' => $guest_id]);
    if (!$s->fetch()) {
        $tolak(422, 'Tamu tidak ditemukan.');
    }

    // kamar harus ada dan AKTIF; baris dikunci agar aman dari request bersamaan
    $s = $pdo->prepare("SELECT room_type_id FROM rooms WHERE id = :id AND status = 'AKTIF' FOR UPDATE");
    $s->execute([':id' => $room_id]);
    $kamar = $s->fetch();
    if (!$kamar) {
        $tolak(422, 'Kamar tidak ditemukan atau sedang tidak aktif.');
    }

    // cek bentrok
    $s = $pdo->prepare(
        "SELECT COUNT(*) FROM reservations
         WHERE room_id = :room_id
           AND status IN ('BOOKED','CHECKED_IN')
           AND checkin < :checkout AND checkout > :checkin"
    );
    $s->execute([':room_id' => $room_id, ':checkin' => $checkin, ':checkout' => $checkout]);
    if ($s->fetchColumn() > 0) {
        $tolak(409, 'Gagal: Kamar sudah dipesan pada rentang tanggal tersebut.');
    }

    // tarif diambil dari server, bukan dari browser
    $s = $pdo->prepare('SELECT tarif FROM room_types WHERE id = :id');
    $s->execute([':id' => $kamar['room_type_id']]);
    $tarif = $s->fetchColumn();

    $kode = 'RSV-' . strtoupper(uniqid());
    $ins = $pdo->prepare(
        "INSERT INTO reservations (kode, guest_id, room_id, user_id, checkin, checkout, tarif, status)
         VALUES (:kode, :guest_id, :room_id, :user_id, :checkin, :checkout, :tarif, 'BOOKED')"
    );
    $ins->execute([
        ':kode' => $kode, ':guest_id' => $guest_id, ':room_id' => $room_id,
        ':user_id' => $user_id, ':checkin' => $checkin, ':checkout' => $checkout,
        ':tarif' => $tarif,
    ]);

    $pdo->commit();
} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log('Gagal simpan reservasi: ' . $e->getMessage());
    http_response_code(500);
    exit('Terjadi kesalahan. Silakan coba lagi.');
}

header('Location: index.php?pesan=sukses');
exit;