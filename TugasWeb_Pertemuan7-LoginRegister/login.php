<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if (isset($_GET['msg']) && $_GET['msg'] === 'registered') {
    $success = 'Registrasi berhasil! Silakan login.';
}
if (isset($_GET['msg']) && $_GET['msg'] === 'logged_out') {
    $success = 'Anda telah logout.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = 'Email dan password wajib diisi!';
    } else {
        $file = 'users.json';
        $users = [];
        
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true) ?? [];
        }

        $foundUser = null;
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $foundUser = $user;
                break;
            }
        }

        if ($foundUser && password_verify($password, $foundUser['password'])) {
            $_SESSION['user_id'] = $foundUser['id'];
            $_SESSION['username'] = $foundUser['nama'];
            $_SESSION['email'] = $foundUser['email'];
            
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tugas Rutin 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>🔐 Login</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit">Login</button>
        </form>

        <p class="link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>