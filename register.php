<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($nama) || empty($email) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } else {
        $nama = htmlspecialchars($nama);
        $email = htmlspecialchars($email);

        $file = 'users.json';
        $users = [];
        
        if (file_exists($file)) {
            $json = file_get_contents($file);
            $users = json_decode($json, true) ?? [];
        }

        $isDuplicate = false;
        foreach ($users as $user) {
            if ($user['email'] === $email) {
                $isDuplicate = true;
                break;
            }
        }

        if ($isDuplicate) {
            $error = 'Email sudah terdaftar! Gunakan email lain.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $newUser = [
                'id' => count($users) + 1,
                'nama' => $nama,
                'email' => $email,
                'password' => $hashedPassword,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $users[] = $newUser;
            file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT));
            
            $success = 'Registrasi berhasil! Silakan login.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tugas Rutin 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>📝 Registrasi</h2>
        
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter" required>
            </div>
            <button type="submit">Daftar</button>
        </form>

        <p class="link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>