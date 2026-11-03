<?php
function e($value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function ambilTamu(PDO $pdo): array {
    $sql = "SELECT id, nama, telepon, email FROM guests ORDER BY nama";
    return $pdo->query($sql)->fetchAll();
}

function simpanTamu(PDO $pdo, array $data): int {
    $sql = "INSERT INTO guests (nama, no_identitas, telepon, email) 
            VALUES (:nama, :identitas, :telepon, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nama'      => trim($data['nama']),
        ':identitas' => trim($data['no_identitas'] ?? '') ?: null,
        ':telepon'   => trim($data['telepon'] ?? '') ?: null,
        ':email'     => trim($data['email'] ?? '') ?: null,
    ]);
    return (int)$pdo->lastInsertId();
}

function ambilReservasi(PDO $pdo): array {
    $sql = "SELECT r.id, r.kode, g.nama AS nama_tamu, rm.nomor AS nomor_kamar,
                   rt.nama AS tipe_kamar, r.checkin, r.checkout,
                   r.tarif, r.status, u.nama AS petugas
            FROM reservations r
            JOIN guests g ON g.id = r.guest_id
            JOIN rooms rm ON rm.id = r.room_id
            JOIN room_types rt ON rt.id = rm.room_type_id
            JOIN users u ON u.id = r.user_id
            ORDER BY r.checkin DESC";
    return $pdo->query($sql)->fetchAll();
}