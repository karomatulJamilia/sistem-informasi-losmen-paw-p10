<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
wajibLogin();

$role = $_SESSION['role'] ?? '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Sistem Informasi Losmen</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="/PAW_B/losmen/public/assets/app.css">
</head>
<body>
    <nav style="background:#2c3e50; color:white; padding:12px 20px;">
        <strong>Halo, <?= e($_SESSION['nama']) ?> (<?= e($role) ?>)</strong> |
        <a href="/PAW_B/losmen/index.php">Dashboard</a> |

        <!-- MENU KHUSUS RESEPSIONIS (Operasional Tamu & Reservasi) -->
        <?php if ($role === 'RESEPSIONIS'): ?>
            <a href="/PAW_B/losmen/guests/index.php">Data Tamu</a> 
            <a href="/PAW_B/losmen/reservations/index.php">Kelola Reservasi</a>
        <?php endif; ?>

        <!-- MENU KHUSUS ADMIN (Administrasi Sistem, Kamar, & Akun) -->
        <?php if ($role === 'ADMIN'): ?>
            <a href="/PAW_B/losmen/admin/rooms.php">Kelola Kamar</a>
            <a href="/PAW_B/losmen/admin/users.php">Kelola Akun</a>
        <?php endif; ?>

        <!-- MENU KHUSUS MANAJER & ADMIN (Melihat Laporan & Ringkasan) -->
        <?php if ($role === 'MANAJER' || $role === 'ADMIN'): ?>
            <a href="/PAW_B/losmen/reports/revenue.php">Laporan Pendapatan</a>
        <?php endif; ?>

        <a href="/PAW_B/losmen/logout.php" style="color:#ff6b6b; float:right;">Logout</a>
    </nav>
    <div class="content-wrapper">
    <div style="padding: 20px;">