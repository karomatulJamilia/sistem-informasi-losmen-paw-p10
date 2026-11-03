<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';

wajibRole(['ADMIN']);

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM rooms WHERE id = ?");
$stmt->execute([$id]);
$kamar = $stmt->fetch();

if (!$kamar) {
    die("Kamar tidak ditemukan.");
}

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor = trim($_POST['nomor'] ?? '');
    $room_type_id = (int)($_POST['room_type_id'] ?? 0);
    $status = $_POST['status'] ?? 'AKTIF';

    if ($nomor && $room_type_id) {
        $update = $pdo->prepare("UPDATE rooms SET room_type_id = :room_type_id, nomor = :nomor, status = :status WHERE id = :id");
        $update->execute([
            ':room_type_id' => $room_type_id,
            ':nomor' => $nomor,
            ':status' => $status,
            ':id' => $id
        ]);
        header("Location: rooms.php");
        exit;
    } else {
        $pesan = "Semua kolom wajib diisi!";
    }
}

$room_types = $pdo->query("SELECT * FROM room_types")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Edit Kamar Fisik</h1>
<?php if ($pesan): ?><p style="color: red;"><?= e($pesan) ?></p><?php endif; ?>

<form method="post" style="background: #fff; padding: 15px; border: 1px solid #ddd; max-width: 400px;">
    <label>Nomor Kamar:<br><input type="text" name="nomor" value="<?= e($kamar['nomor']) ?>" required></label><br>
    <label>Tipe Kamar:<br>
        <select name="room_type_id" required style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px;">
            <?php foreach ($room_types as $rt): ?>
                <option value="<?= $rt['id'] ?>" <?= $rt['id'] == $kamar['room_type_id'] ? 'selected' : '' ?>>
                    <?= e($rt['nama']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Status Kamar:<br>
        <select name="status" style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px;">
            <option value="AKTIF" <?= $kamar['status'] === 'AKTIF' ? 'selected' : '' ?>>AKTIF</option>
            <option value="PERBAIKAN" <?= $kamar['status'] === 'PERBAIKAN' ? 'selected' : '' ?>>PERBAIKAN</option>
        </select>
    </label><br>
    <button type="submit">Simpan Perubahan</button>
    <a href="rooms.php" style="margin-left: 10px;">Batal</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>