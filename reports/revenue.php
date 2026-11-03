<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

// Hanya Manajer dan Admin yang boleh melihat laporan pendapatan
wajibRole(['MANAJER', 'ADMIN']);
require_once __DIR__ . '/../includes/header.php';

// Ambil ringkasan pendapatan dari database
$sql = "SELECT r.kode, g.nama AS nama_tamu, rm.nomor AS nomor_kamar, 
               r.checkin, r.checkout, r.tarif, r.status
        FROM reservations r
        JOIN guests g ON g.id = r.guest_id
        JOIN rooms rm ON rm.id = r.room_id
        WHERE r.status != 'CANCELLED'
        ORDER BY r.checkin DESC";
$laporan = $pdo->query($sql)->fetchAll();

// Hitung total pendapatan keseluruhan
$sqlTotal = "SELECT SUM(tarif) AS total FROM reservations WHERE status != 'CANCELLED'";
$totalPendapatan = $pdo->query($sqlTotal)->fetchColumn() ?: 0;
?>

<h1>Laporan Pendapatan & Ringkasan Reservasi</h1>
<p style="font-size: 1.2em; background: #e8f4f8; padding: 15px; border-radius: 5px;">
    <strong>Total Pendapatan (Aktif/Selesai): </strong> 
    <span style="color: #27ae60;">Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></span>
</p>

<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#eee;">
            <th>Kode Reservasi</th>
            <th>Nama Tamu</th>
            <th>Nomor Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Tarif</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($laporan as $row): ?>
        <tr>
            <td><?= e($row['kode']) ?></td>
            <td><?= e($row['nama_tamu']) ?></td>
            <td><?= e($row['nomor_kamar']) ?></td>
            <td><?= e($row['checkin']) ?></td>
            <td><?= e($row['checkout']) ?></td>
            <td>Rp <?= number_format($row['tarif'], 0, ',', '.') ?></td>
            <td><strong><?= e($row['status']) ?></strong></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>