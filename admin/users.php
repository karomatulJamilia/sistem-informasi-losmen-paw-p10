<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$users = $pdo->query("SELECT u.id, u.nama, u.username, r.nama AS role, u.aktif FROM users u JOIN roles r ON r.id = u.role_id")->fetchAll();

require_once __DIR__ . '/../includes/header.php';
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Kelola Akun Pengguna</h1>
    <a href="user_form.php" class="btn" style="text-decoration: none; padding: 10px 15px; background-color: #27ae60; color: white; border-radius: 4px;">+ Tambah Akun Baru</a>
</div>

<table border="1" cellpadding="6" cellspacing="0" style="width:100%; border-collapse:collapse;">
    <thead>
        <tr style="background:#eee;">
            <th>Nama</th>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= e($u['nama']) ?></td>
            <td><?= e($u['username']) ?></td>
            <td><?= e($u['role']) ?></td>
            <td><?= $u['aktif'] ? 'Aktif' : 'Non-aktif' ?></td>
            <td>
                <a href="user_edit.php?id=<?= $u['id'] ?>" class="btn" style="padding: 4px 8px; font-size: 12px;">Edit</a>
                <?php if ($u['id'] !== $_SESSION['user_id']): ?>
                    <a href="user_delete.php?id=<?= $u['id'] ?>" class="btn" style="padding: 4px 8px; font-size: 12px; background-color: #e74c3c;" onclick="return confirm('Yakin ingin menghapus akun ini?')">Hapus</a>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>