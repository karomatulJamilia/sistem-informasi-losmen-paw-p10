<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
// Hanya RESEPSIONIS yang boleh membuka form atau menyimpan reservasi
wajibRole(['RESEPSIONIS']);

$tamu = ambilTamu($pdo);
$kamar = $pdo->query("SELECT r.id, r.nomor, rt.nama AS tipe, rt.tarif FROM rooms r JOIN room_types rt ON rt.id = r.room_type_id WHERE r.status='AKTIF'")->fetchAll();
?>
<h1>Buat Reservasi Baru</h1>
<form method="post" action="save.php">
    <label>Pilih Tamu:<br>
        <select name="guest_id" required style="width:300px;">
            <option value="">-- Pilih Tamu --</option>
            <?php foreach ($tamu as $t): ?>
                <option value="<?= $t['id'] ?>"><?= e($t['nama']) ?> (<?= e($t['telepon']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Pilih Kamar:<br>
        <select name="room_id" required style="width:300px;">
            <option value="">-- Pilih Kamar --</option>
            <?php foreach ($kamar as $k): ?>
                <option value="<?= $k['id'] ?>"><?= e('Kamar ' . $k['nomor'] . ' - ' . $k['tipe'] . ' (Rp ' . number_format($k['tarif'], 0, ',', '.') . ')') ?></option>
            <?php endforeach; ?>
        </select>
    </label><br><br>

    <label>Tanggal Check-in:<br>
        <input type="date" name="checkin" required style="width:300px;">
    </label><br><br>

    <label>Tanggal Check-out:<br>
        <input type="date" name="checkout" required style="width:300px;">
    </label><br><br>

    <button type="submit" class="btn">Simpan</button>
    <a href="index.php" class="btn" style="background-color: #7f8c8d; text-decoration: none; margin-left: 10px;">Batal</a>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>