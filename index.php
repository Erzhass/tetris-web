<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$email = htmlspecialchars($_SESSION['email']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - Erzha.rf.gd</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body,
    html {
        height: 100%;
        font-family: 'Segoe UI', sans-serif;
        background-color: #e8f0ff;
        overflow: hidden;
    }

    .navbar {
        background-color: #1e3a8a;
        color: white;
        padding: 16px 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .navbar .logo {
        font-size: 24px;
        font-weight: bold;
        color: #22c55e;
    }

    .navbar ul {
        list-style: none;
        display: flex;
        gap: 24px;
    }

    .navbar ul li a {
        color: white;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .navbar ul li a:hover {
        color: #93c5fd;
    }

    .container {
        height: calc(100% - 64px);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .content-box {
        background-color: white;
        padding: 40px;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        width: 100%;
        text-align: center;
    }

    .content-box h1 {
        font-size: 28px;
        color: #1e3a8a;
        margin-bottom: 16px;
    }

    .content-box h1 span {
        color: #22c55e;
    }

    .content-box p {
        font-size: 16px;
        margin-bottom: 28px;
    }

    .btn {
        background-color: #1e40af;
        color: white;
        padding: 12px 24px;
        font-size: 16px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin: 5px;
    }

    .btn:hover {
        background-color: #2563eb;
    }

    @media (max-width: 600px) {
        .content-box {
            padding: 30px 20px;
        }

        .btn {
            width: 100%;
        }
    }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Erzha.rf.gd</div>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="leaderboard.php">Leaderboard</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>

    <!-- Konten -->
    <div class="container">
        <div class="content-box">
            <h1>Selamat Datang di <span>tetrisku.com</span></h1>
            <p>Halo, <strong><?= $email ?></strong>! Selamat bermain di web saya</p>
            <a href="game.php" class="btn">Main Tetris</a>
            <a href="game1.php" class="btn">Main Ular</a>
            <a href="game2.php" class="btn">Main 2048</a>
        </div>
    </div>

</body>

</html>