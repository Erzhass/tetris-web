<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

$email = $_SESSION['email'];

$conn = new mysqli("localhost", "root", "", "tetris");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$query = $conn->prepare("SELECT skor, waktu_bermain, waktu_pencapaian FROM dashboard WHERE email = ? ORDER BY waktu_pencapaian DESC");
$query->bind_param("s", $email);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Riwayat Permainan</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html,
    body {
        height: 100%;
        font-family: 'Segoe UI', sans-serif;
        background-color: #e8f0ff;
        /* biru muda */
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        margin-top: 60px;
    }

    h1 {
        text-align: center;
        color: #1e3a8a;
        margin-bottom: 24px;
    }

    .email-info {
        font-size: 16px;
        text-align: center;
        margin-bottom: 24px;
    }

    .email-info span {
        font-weight: bold;
        color: #1e40af;
    }

    .back-link {
        text-align: right;
        margin-bottom: 20px;
    }

    .back-link a {
        display: inline-block;
        padding: 10px 18px;
        background-color: #1e40af;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    .back-link a:hover {
        background-color: #2563eb;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table th,
    table td {
        border: 1px solid #d1d5db;
        padding: 12px;
        text-align: center;
    }

    table thead {
        background-color: #dbeafe;
        /* biru sangat muda */
    }

    table tbody tr:nth-child(even) {
        background-color: #f0f9ff;
    }

    .empty-row td {
        padding: 20px;
        color: #555;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Profil Anda - Riwayat Permainan</h1>

        <div class="back-link">
            <a href="index.php">← Kembali ke Beranda</a>
        </div>

        <div class="email-info">
            Email: <span><?= htmlspecialchars($email) ?></span>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Skor</th>
                    <th>Waktu Bermain (detik)</th>
                    <th>Waktu Pencapaian</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['skor'] ?></td>
                    <td><?= $row['waktu_bermain'] ?></td>
                    <td><?= $row['waktu_pencapaian'] ?></td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                <tr class="empty-row">
                    <td colspan="3">Belum ada riwayat permainan.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>

</html>