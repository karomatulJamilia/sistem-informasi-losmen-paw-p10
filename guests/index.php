<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$tamu = ambilTamu($pdo);
?>
<h1>Data Tamu</h1>
<p><a href="form.php">+ Tambah Tamu</a></p>
<input id="cari" type="search" placeholder="Cari nama/telepon" style="padding:5px; width:250px; margin-bottom:10px;">
<table border="1" cellpadding="6" cellspacing="0" id="tabelTamu" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#eee;">
            <th>Nama</th>
            <th>Telepon</th>
            <th>Email</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tamu as $t): ?>
        <tr>
            <td><?= e($t['nama']) ?></td>
            <td><?= e($t['telepon'] ?? '-') ?></td>
            <td><?= e($t['email'] ?? '-') ?></td>
            <td>
                <a href="edit.php?id=<?= $t['id'] ?>" class="btn" style="padding: 4px 8px; font-size: 12px;">Edit</a>
                <a href="delete.php?id=<?= $t['id'] ?>" class="btn" style="padding: 4px 8px; font-size: 12px; background-color: #e74c3c;" onclick="return confirm('Yakin ingin menghapus data tamu ini?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<script>
$('#cari').on('input', function () {
    const q = $(this).val().toLowerCase();
    $('#tabelTamu tbody tr').each(function () {
        $(this).toggle($(this).text().toLowerCase().includes(q));
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>