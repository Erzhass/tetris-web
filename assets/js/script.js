document
  .getElementById("loginForm")
  .addEventListener("submit", function (event) {
    event.preventDefault(); // Mencegah form dari pengiriman default

    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");

    // Contoh validasi sederhana
    if (username === "admin" && password === "password") {
      alert("Login berhasil! Selamat menonton anime!");
      // Redirect ke halaman lain jika perlu
      // window.location.href = 'homepage.html';
    } else {
      errorMessage.textContent = "Username atau password salah!";
    }
  });
