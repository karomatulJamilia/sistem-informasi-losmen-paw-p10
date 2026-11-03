<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function wajibLogin(): void {
    if (empty($_SESSION['user_id'])) {
        header('Location: /PAW_B/losmen/login.php');
        exit;
    }
}

function wajibRole(array $roles): void {
    wajibLogin();
    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        http_response_code(403);
        exit('Anda tidak memiliki hak akses.');
    }
}