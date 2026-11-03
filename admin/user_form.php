<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama     = trim($_POST['nama'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role_id  = (int)($_POST['role_id'] ?? 0);

    if ($nama && $username && $password && $role_id) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (role_id, nama, username, password_hash) VALUES (:role_id, :nama, :username, :password_hash)");
            $stmt->execute([
                ':role_id'       => $role_id,
                ':nama'          => $nama,
                ':username'      => $username,
                ':password_hash' => $password_hash
            ]);
            header("Location: users.php");
            exit;
        } catch (PDOException $e) {
            $pesan = 'Gagal: Username mungkin sudah digunakan.';
        }
    } else {
        $pesan = 'Semua kolom wajib diisi!';
    }
}

$roles = $pdo->query("SELECT * FROM roles")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Tambah Akun Pengguna Baru</h1>
<?php if ($pesan): ?><p style="color: red;"><?= e($pesan) ?></p><?php endif; ?>

<form method="post" style="background: #fff; padding: 20px; border: 1px solid #ddd; max-width: 400px; border-radius: 5px;">
    <label>Nama Lengkap:<br><input type="text" name="nama" required></label><br>
    <label>Username:<br><input type="text" name="username" required></label><br>
    <label>Password:<br><input type="password" name="password" required></label><br>
    <label>Role / Hak Akses:<br>
        <select name="role_id" required style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px;">
            <option value="">-- Pilih Role --</option>
            <?php foreach ($roles as $r): ?>
                <option value="<?= $r['id'] ?>"><?= e($r['nama']) ?></option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <button type="submit">Simpan Akun</button>
    <a href="users.php" style="margin-left: 10px;">Batal</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>