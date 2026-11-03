<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    die("Pengguna tidak ditemukan.");
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $role_id = (int)($_POST['role_id'] ?? 0);
    $password = $_POST['password'] ?? '';

    if ($nama && $username && $role_id) {
        if (!empty($password)) {
            // Jika password diisi, update beserta password hash baru
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET nama = :nama, username = :username, role_id = :role_id, password_hash = :password_hash WHERE id = :id");
            $update->execute([':nama' => $nama, ':username' => $username, ':role_id' => $role_id, ':password_hash' => $password_hash, ':id' => $id]);
        } else {
            // Jika password kosong, jangan ubah passwordnya
            $update = $pdo->prepare("UPDATE users SET nama = :nama, username = :username, role_id = :role_id WHERE id = :id");
            $update->execute([':nama' => $nama, ':username' => $username, ':role_id' => $role_id, ':id' => $id]);
        }
        header("Location: users.php");
        exit;
    } else {
        $pesan = "Nama, Username, dan Role wajib diisi!";
    }
}

$roles = $pdo->query("SELECT * FROM roles")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Edit Akun Pengguna</h1>
<?php if ($pesan): ?><p style="color: red;"><?= e($pesan) ?></p><?php endif; ?>

<form method="post" style="background: #fff; padding: 15px; border: 1px solid #ddd; max-width: 400px;">
    <label>Nama Lengkap:<br><input type="text" name="nama" value="<?= e($user['nama']) ?>" required></label><br>
    <label>Username:<br><input type="text" name="username" value="<?= e($user['username']) ?>" required></label><br>
    <label>Password Baru (Kosongkan jika tidak ingin diubah):<br><input type="password" name="password"></label><br>
    <label>Role / Hak Akses:<br>
        <select name="role_id" required style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px;">
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>" <?= $r['id'] == $user['role_id'] ? 'selected' : '' ?>><?= e($r['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Simpan Perubahan</button>
    <a href="users.php" style="margin-left: 10px;">Batal</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>