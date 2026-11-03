<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/auth.php';
// Hanya role RESEPSIONIS yang boleh mengakses halaman ini
wajibRole(['RESEPSIONIS']); 


?>
<h1>Tambah Tamu</h1>
<form method="post" action="save.php">
    <label>Nama (Wajib):<br>
        <input name="nama" maxlength="120" required style="width:300px;">
    </label><br><br>
    <label>No. Identitas:<br>
        <input name="no_identitas" maxlength="40" style="width:300px;">
    </label><br><br>
    <label>Telepon:<br>
        <input name="telepon" maxlength="25" style="width:300px;">
    </label><br><br>
    <label>Email:<br>
        <input type="email" name="email" maxlength="120" style="width:300px;">
    </label><br><br>
    <button type="submit">Simpan</button>
    <a href="index.php">Batal</a>
</form>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>