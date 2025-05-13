-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Bulan Mei 2025 pada 15.34
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tetris`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dashboard`
--

CREATE TABLE `dashboard` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `skor` int(11) NOT NULL,
  `waktu_bermain` int(11) NOT NULL,
  `waktu_pencapaian` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dashboard`
--

INSERT INTO `dashboard` (`id`, `email`, `skor`, `waktu_bermain`, `waktu_pencapaian`) VALUES
(1, 'erzha@gmail.com', 100, 185, '2025-05-07 07:22:10'),
(2, 'halo@gmail.com', 800, 252, '2025-05-07 08:25:09'),
(3, 'endra@gmail.com', 0, 8, '2025-05-07 08:46:21'),
(4, 'endra@gmail.com', 900, 314, '2025-05-07 09:03:02'),
(5, 'endra@gmail.com', 1900, 457, '2025-05-07 09:27:55'),
(6, 'er@gmail.com', 1900, 541, '2025-05-13 14:52:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `leaderboard`
--

CREATE TABLE `leaderboard` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `skor` int(11) NOT NULL,
  `waktu_bermain` int(11) NOT NULL,
  `waktu_pencapaian` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `leaderboard`
--

INSERT INTO `leaderboard` (`id`, `email`, `skor`, `waktu_bermain`, `waktu_pencapaian`) VALUES
(1, 'erzha@gmail.com', 100, 185, '2025-05-07 07:22:10'),
(2, 'halo@gmail.com', 800, 252, '2025-05-07 08:25:09'),
(3, 'endra@gmail.com', 1900, 457, '2025-05-07 09:27:55'),
(4, 'er@gmail.com', 1900, 541, '2025-05-13 14:52:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `password`) VALUES
(1, 'erzha@gmail.com', '$2y$10$wiY2ejQIgobU88mpRPnrZelBtFE8/6il39pMMs2kxajjnazBTabSe'),
(2, 'halo@gmail.com', '$2y$10$tm0DmvMFpnFm38fJOwHZueZ6jH4x1X8v1tMVArBkrOctAiNb54OjC'),
(3, 'era@gmail.com', '$2y$10$yIZB2DICw7edi2WPOexUmOh8SuTN6Y7vM93pz3I0i0/WvqH8YyKEa'),
(4, 'endra@gmail.com', '$2y$10$J3yzYKb9GHdYaLfP3Je7w.04WCxy.NUb36LmtGWeGImGDQOT0dfBi'),
(5, 'er@gmail.com', '$2y$10$zV4seOiX0mQWbtLUXRJsauqBI6UewVs.J5VWs7tvWtzR4P.katcYC');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dashboard`
--
ALTER TABLE `dashboard`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `leaderboard`
--
ALTER TABLE `leaderboard`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dashboard`
--
ALTER TABLE `dashboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `leaderboard`
--
ALTER TABLE `leaderboard`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
