<?php
session_start();
include "koneksi.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["email"] = $row["email"];
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Login - Tetris Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-100 min-h-screen flex items-center justify-center">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md border border-blue-400">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Login</h2>

        <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 border border-red-300 text-center">
            <?= $error ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <input type="email" name="email" placeholder="Email"
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required />

            <input type="password" name="password" id="passwordInput" placeholder="Password"
                class="w-full p-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                required />

            <div class="flex items-center space-x-2">
                <input type="checkbox" id="togglePassword"
                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded" />
                <label for="togglePassword" class="text-sm text-gray-700">Tampilkan Password</label>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded shadow">
                Login
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-700">
            Belum punya akun?
            <a href="signup.php" class="text-blue-500 hover:underline">Daftar Sekarang</a>
        </p>
    </div>

    <script>
    // Toggle password visibility
    document.addEventListener("DOMContentLoaded", function() {
        const toggle = document.getElementById("togglePassword");
        const password = document.getElementById("passwordInput");

        toggle.addEventListener("change", function() {
            password.type = this.checked ? "text" : "password";
        });
    });
    </script>
</body>

</html>