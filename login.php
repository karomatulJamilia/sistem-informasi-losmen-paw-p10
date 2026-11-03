<?php
// Pastikan file koneksi dan fungsi sudah dipanggil
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

// Proses autentikasi login (sesuaikan dengan logika login Anda sebelumnya)
$pesan = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT u.*, r.nama AS role_nama FROM users u JOIN roles r ON r.id = u.role_id WHERE u.username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role_nama'];
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Username atau password salah!";
        }
    } else {
        $pesan = "Semua kolom wajib diisi!";
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Informasi Losmen</title>
    <link rel="stylesheet" href="/PAW_B/losmen/public/assets/app.css">
    <style>
        /* CSS Khusus Halaman Login agar Berada di Tengah & Elegan */
        body {
            background: linear-gradient(rgba(44, 26, 10, 0.7), rgba(44, 26, 10, 0.7)), url('https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1400&q=80') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .login-card h2 {
            color: #4a3319;
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1.8em;
        }
        .login-card label {
            font-weight: 500;
            color: #4a3319;
            font-size: 14px;
        }
        .login-card input[type="text"],
        .login-card input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-top: 5px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-btn {
            width: 100%;
            background-color: #8c6239;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            margin-top: 10px;
        }
        .login-btn:hover {
            background-color: #6b4f31;
        }
        .error-msg {
            color: #e74c3c;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h2>Login Losmen</h2>
        
        <?php if ($pesan): ?>
            <div class="error-msg"><?= e($pesan) ?></div>
        <?php endif; ?>

        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required placeholder="Masukkan username">

            <label>Password</label>
            <input type="password" name="password" required placeholder="Masukkan password">

            <button type="submit" class="login-btn">Masuk</button>
        </form>
    </div>

</body>
</html>