<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

wajibRole(['RESEPSIONIS']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM guests WHERE id = ?");
$stmt->execute([$id]);
$tamu = $stmt->fetch();

if (!$tamu) {
    die("Data tamu tidak ditemukan.");
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama'] ?? '');
    $telepon = trim($_POST['telepon'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    if ($nama && $telepon) {
        $update = $pdo->prepare("UPDATE guests SET nama = :nama, telepon = :telepon, alamat = :alamat WHERE id = :id");
        $update->execute([
            ':nama' => $nama,
            ':telepon' => $telepon,
            ':alamat' => $alamat,
            ':id' => $id
        ]);
        header("Location: index.php");
        exit;
    } else {
        $pesan = "Nama dan Telepon wajib diisi!";
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1>Edit Data Tamu</h1>
<?php if ($pesan): ?><p style="color: red;"><?= e($pesan) ?></p><?php endif; ?>

<form method="post" style="background: #fff; padding: 15px; border: 1px solid #ddd; max-width: 400px;">
    <label>Nama Tamu:<br><input type="text" name="nama" value="<?= e($tamu['nama']) ?>" required></label><br>
    <label>Nomor Telepon:<br><input type="text" name="telepon" value="<?= e($tamu['telepon']) ?>" required></label><br>
    <button type="submit">Simpan Perubahan</button>
    <a href="index.php" style="margin-left: 10px;">Batal</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>