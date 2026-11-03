<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
wajibLogin();

require_once __DIR__ . '/includes/header.php';
?>

<!-- Bagian Banner Dashboard dengan Background Gambar & Teks Kontras -->
<div class="dashboard-hero">
    <h1>Dashboard Sistem Informasi Losmen</h1>
    <p>Selamat datang kembali, <strong><?= e($_SESSION['nama']) ?></strong>. Anda masuk sebagai <strong><?= e($_SESSION['role']) ?></strong>.</p>
    <p style="margin-top: 15px;">Gunakan menu navigasi di atas untuk mengelola data operasional, kamar, atau laporan sesuai dengan hak akses Anda.</p>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>