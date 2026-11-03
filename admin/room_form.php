<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
wajibRole(['ADMIN']);

$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomor        = trim($_POST['nomor'] ?? '');
    $room_type_id = (int)($_POST['room_type_id'] ?? 0);
    $status       = $_POST['status'] ?? 'AKTIF';

    if ($nomor && $room_type_id) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rooms (room_type_id, nomor, status) VALUES (:room_type_id, :nomor, :status)");
            $stmt->execute([
                ':room_type_id' => $room_type_id,
                ':nomor'        => $nomor,
                ':status'       => $status
            ]);
            header("Location: rooms.php");
            exit;
        } catch (PDOException $e) {
            $pesan = 'Gagal: Nomor kamar tersebut sudah terdaftar.';
        }
    } else {
        $pesan = 'Semua kolom wajib diisi!';
    }
}

$room_types = $pdo->query("SELECT * FROM room_types")->fetchAll();
require_once __DIR__ . '/../includes/header.php';
?>

<h1>Tambah Kamar Fisik Baru</h1>
<?php if ($pesan): ?><p style="color: red;"><?= e($pesan) ?></p><?php endif; ?>

<form method="post" style="background: #fff; padding: 20px; border: 1px solid #ddd; max-width: 400px; border-radius: 5px;">
    <label>Nomor Kamar:<br><input type="text" name="nomor" required placeholder="Contoh: 103"></label><br>
    <label>Tipe Kamar:<br>
        <select name="room_type_id" required style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px;">
            <option value="">-- Pilih Tipe Kamar --</option>
            <?php foreach ($room_types as $rt): ?>
                <option value="<?= $rt['id'] ?>"><?= e($rt['nama']) ?> (Rp <?= number_format($rt['tarif'], 0, ',', '.') ?>)</option>
            <?php endforeach; ?>
        </select>
    </label><br>
    <label>Status Kamar:<br>
        <select name="status" style="width:100%; padding:8px; margin-top:5px; margin-bottom:15px;">
            <option value="AKTIF">AKTIF</option>
            <option value="PERBAIKAN">PERBAIKAN</option>
        </select>
    </label><br>
    <button type="submit">Simpan Kamar</button>
    <a href="rooms.php" class="btn" style="background-color: #7f8c8d; text-decoration: none; margin-left: 10px;">Batal</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>