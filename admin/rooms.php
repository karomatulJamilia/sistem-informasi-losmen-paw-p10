<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$kamar = $pdo->query("SELECT r.id, r.nomor, rt.nama AS tipe, rt.tarif, r.status 
                      FROM rooms r 
                      JOIN room_types rt ON rt.id = r.room_type_id")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Kelola Kamar Fisik</h1>
    <a href="room_form.php" class="btn" style="text-decoration: none; padding: 10px 15px; background-color: #27ae60; color: white; border-radius: 4px;">+ Tambah Kamar Baru</a>
</div>

<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#eee;">
            <th>Nomor Kamar</th>
            <th>Tipe Kamar</th>
            <th>Tarif Dasar</th>
            <th>Status Kamar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($kamar as $k): ?>
        <tr>
            <td><?= e($k['nomor']) ?></td>
            <td><?= e($k['tipe']) ?></td>
            <td>Rp <?= number_format($k['tarif'], 0, ',', '.') ?></td>
            <td><strong><?= e($k['status']) ?></strong></td>
            <td>
                <a href="room_edit.php?id=<?= $k['id'] ?>" class="btn" style="padding: 4px 8px; font-size: 12px;">Edit</a>
                <a href="room_delete.php?id=<?= $k['id'] ?>" class="btn" style="padding: 4px 8px; font-size: 12px; background-color: #e74c3c;" onclick="return confirm('Yakin ingin menghapus kamar ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>