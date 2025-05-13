<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    echo json_encode(['success' => false, 'message' => 'User belum login.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'koneksi.php';

    $email = $_SESSION['email'];
    $score = isset($_POST['score']) ? intval($_POST['score']) : 0;
    $time_played = isset($_POST['time']) ? intval($_POST['time']) : 0;
    $created_at = date('Y-m-d H:i:s');

    // Simpan ke tabel dashboard
    $stmt1 = $conn->prepare("INSERT INTO dashboard (email, skor, waktu_bermain, waktu_pencapaian) VALUES (?, ?, ?, ?)");
    $stmt1->bind_param("siis", $email, $score, $time_played, $created_at);
    $result1 = $stmt1->execute();

    // Periksa apakah ini skor tertinggi untuk leaderboard
    $stmt2 = $conn->prepare("SELECT skor FROM leaderboard WHERE email = ?");
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $stmt2->store_result();

    if ($stmt2->num_rows > 0) {
        $stmt2->bind_result($existing_score);
        $stmt2->fetch();

        if ($score > $existing_score) {
            $stmt3 = $conn->prepare("UPDATE leaderboard SET skor = ?, waktu_bermain = ?, waktu_pencapaian = ? WHERE email = ?");
            $stmt3->bind_param("iiss", $score, $time_played, $created_at, $email);
            $stmt3->execute();
        }
    } else {
        $stmt4 = $conn->prepare("INSERT INTO leaderboard (email, skor, waktu_bermain, waktu_pencapaian) VALUES (?, ?, ?, ?)");
        $stmt4->bind_param("siis", $email, $score, $time_played, $created_at);
        $stmt4->execute();
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Metode tidak valid.']);
}