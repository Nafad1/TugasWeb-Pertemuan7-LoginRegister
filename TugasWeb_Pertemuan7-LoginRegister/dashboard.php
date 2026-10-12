<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Tugas Rutin 7</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container dashboard-card">
        <h2>🏠 Dashboard</h2>
        <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</p>
        <p>Email: <?= htmlspecialchars($_SESSION['email']) ?></p>
        
        <a href="logout.php" class="btn-logout">Logout</a>
    </div>
</body>
</html>