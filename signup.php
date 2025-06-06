<?php
include "koneksi.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        $query = "INSERT INTO users (email, password) VALUES ('$email', '$password')";
        if (mysqli_query($conn, $query)) {
            $success = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Gagal mendaftar: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Tetris Online</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-blue-100 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-lg rounded-lg p-8 w-full max-w-md border border-blue-400">
        <h2 class="text-2xl font-bold text-center text-blue-700 mb-6">Sign Up</h2>

        <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4 border border-red-300 text-center">
            <?= $error ?>
        </div>
        <?php endif; ?>

        <?php if ($success): ?>
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4 border border-green-300 text-center">
            <?= $success ?>
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
                Sign Up
            </button>
        </form>

        <p class="text-center text-sm mt-4 text-gray-700">
            Sudah punya akun?
            <a href="login.php" class="text-blue-500 hover:underline">Login</a>
        </p>
    </div>

    <script>
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