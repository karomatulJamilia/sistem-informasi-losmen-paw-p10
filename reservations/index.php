<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$reservasi = ambilReservasi($pdo);
?>
<h1>Data Reservasi</h1>
<p><a href="form.php">+ Tambah Reservasi Baru</a></p>
<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#eee;">
            <th>Kode</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Check-in</th>
            <th>Check-out</th>
            <th>Tarif</th>
            <th>Status</th>
            <th>Petugas</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reservasi as $r): ?>
        <tr>
            <td><?= e($r['kode']) ?></td>
            <td><?= e($r['nama_tamu']) ?></td>
            <td><?= e($r['nomor_kamar'] . ' (' . $r['tipe_kamar'] . ')') ?></td>
            <td><?= e($r['checkin']) ?></td>
            <td><?= e($r['checkout']) ?></td>
            <td>Rp <?= number_format($r['tarif'], 0, ',', '.') ?></td>
            <td><strong><?= e($r['status']) ?></strong></td>
            <td><?= e($r['petugas']) ?></td>
            <td>
                <?php if ($_SESSION['role'] === 'RESEPSIONIS'): ?>
                    <?php if ($r['status'] === 'BOOKED'): ?>
                    <form method="post" action="status.php" style="display:inline;" class="form-status">
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <input type="hidden" name="status" value="CHECKED_IN">
                        <button type="submit">Check-In</button>
                    </form>
                    <?php endif; ?>
                    <?php if ($r['status'] === 'CHECKED_IN'): ?>
                    <form method="post" action="status.php" style="display:inline;" class="form-status">
                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                        <input type="hidden" name="status" value="CHECKED_OUT">
                        <button type="submit">Check-Out</button>
                    </form>
                    <?php endif; ?>
                <?php else: ?>
                    <span style="color: gray; font-style: italic;">Hanya lihat (Read-only)</span>
                <?php endif; ?>
           </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$('.form-status').on('submit', function (e) {
    if (!confirm('Yakin ingin mengubah status reservasi ini?')) {
        e.preventDefault();
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>