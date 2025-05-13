<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Game Tetris</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
    canvas {
        background: #111827;
        display: block;
        border: 4px solid #1f2937;
    }
    </style>
</head>

<body class="bg-gray-900 text-white flex flex-col items-center justify-center min-h-screen p-4 relative">
    <a href="index.php" class="absolute top-4 left-4 bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">⬅️ Kembali</a>
    <h1 class="text-3xl font-bold text-blue-400 mb-2">🎮 Tetris</h1>

    <div class="mb-2 flex gap-6 text-lg text-gray-300">
        <div>Skor: <span id="score">0</span></div>
        <div>Waktu: <span id="time">0</span> detik</div>
    </div>

    <div class="mt-4 flex items-start gap-8">
        <!-- Canvas Tetris -->
        <canvas id="tetris" width="300" height="400"></canvas>

        <!-- Sidebar -->
        <div class="flex flex-col gap-4">
            <button id="pause-button" class="bg-green-600 px-4 py-2 rounded hover:bg-green-700">⏸️ Pause</button>

            <!-- Game Over Panel -->
            <div id="game-over" class="hidden mt-2 bg-gray-800 p-4 rounded shadow w-64">
                <h2 class="text-xl font-bold text-red-400 text-center">🎮 Game Over</h2>
                <p class="mt-2 text-center">Skor akhir: <span id="final-score"></span></p>
                <p class="text-center">Waktu bermain: <span id="final-time"></span> detik</p>
                <button id="save-button" class="mt-4 w-full bg-blue-600 px-4 py-2 rounded hover:bg-blue-700">💾 Simpan
                    Skor</button>
                <p id="save-status" class="mt-2 text-center font-semibold"></p>
            </div>
        </div>
    </div>

    <script>
    const canvas = document.getElementById('tetris');
    const context = canvas.getContext('2d');
    context.scale(20, 20);

    const scoreElem = document.getElementById('score');
    const timeElem = document.getElementById('time');
    const finalScore = document.getElementById('final-score');
    const finalTime = document.getElementById('final-time');
    const gameOverElem = document.getElementById('game-over');
    const saveButton = document.getElementById('save-button');
    const saveStatus = document.getElementById('save-status');

    let score = 0;
    let timeCounter = 0;
    let gameOver = false;
    let isPaused = false;

    const colors = [null, '#ff0d72', '#0dc2ff', '#0dff72', '#f538ff', '#ff8e0d', '#ffe138', '#3877ff'];
    const arena = createMatrix(15, 20);

    const player = {
        pos: {
            x: 0,
            y: 0
        },
        matrix: null,
    };

    function createMatrix(w, h) {
        const matrix = [];
        while (h--) matrix.push(new Array(w).fill(0));
        return matrix;
    }

    function createPiece(type) {
        if (type === 'T') return [
            [0, 1, 0],
            [1, 1, 1],
            [0, 0, 0]
        ];
        if (type === 'O') return [
            [2, 2],
            [2, 2]
        ];
        if (type === 'L') return [
            [0, 3, 0],
            [0, 3, 0],
            [0, 3, 3]
        ];
        if (type === 'J') return [
            [0, 4, 0],
            [0, 4, 0],
            [4, 4, 0]
        ];
        if (type === 'I') return [
            [0, 5, 0, 0],
            [0, 5, 0, 0],
            [0, 5, 0, 0],
            [0, 5, 0, 0]
        ];
        if (type === 'S') return [
            [0, 6, 6],
            [6, 6, 0],
            [0, 0, 0]
        ];
        if (type === 'Z') return [
            [7, 7, 0],
            [0, 7, 7],
            [0, 0, 0]
        ];
    }

    function drawMatrix(matrix, offset) {
        matrix.forEach((row, y) => {
            row.forEach((value, x) => {
                if (value !== 0) {
                    context.fillStyle = colors[value];
                    context.fillRect(x + offset.x, y + offset.y, 1, 1);
                }
            });
        });
    }

    function draw() {
        context.fillStyle = '#1f2937';
        context.fillRect(0, 0, canvas.width, canvas.height);
        drawMatrix(arena, {
            x: 0,
            y: 0
        });
        drawMatrix(player.matrix, player.pos);
    }

    function merge(arena, player) {
        player.matrix.forEach((row, y) => {
            row.forEach((value, x) => {
                if (value !== 0) {
                    arena[y + player.pos.y][x + player.pos.x] = value;
                }
            });
        });
    }

    function collide(arena, player) {
        const m = player.matrix;
        const o = player.pos;
        for (let y = 0; y < m.length; ++y) {
            for (let x = 0; x < m[y].length; ++x) {
                if (m[y][x] !== 0 && (arena[y + o.y] && arena[y + o.y][x + o.x]) !== 0) {
                    return true;
                }
            }
        }
        return false;
    }

    function arenaSweep() {
        let lines = 0;
        outer: for (let y = arena.length - 1; y >= 0; --y) {
            for (let x = 0; x < arena[y].length; ++x) {
                if (arena[y][x] === 0) continue outer;
            }
            arena.splice(y, 1);
            arena.unshift(new Array(arena[0].length).fill(0));
            lines++;
            y++;
        }
        if (lines > 0) {
            score += lines * 100;
            scoreElem.textContent = score;
        }
    }

    function playerReset() {
        const pieces = 'TJLOSZI';
        player.matrix = createPiece(pieces[Math.floor(Math.random() * pieces.length)]);
        player.pos.y = 0;
        player.pos.x = Math.floor(arena[0].length / 2 - player.matrix[0].length / 2);
        if (collide(arena, player)) {
            gameOver = true;
            finalScore.textContent = score;
            finalTime.textContent = timeCounter;
            gameOverElem.classList.remove("hidden");
        }
    }

    function playerDrop() {
        player.pos.y++;
        if (collide(arena, player)) {
            player.pos.y--;
            merge(arena, player);
            arenaSweep();
            playerReset();
        }
        dropCounter = 0;
    }

    function playerMove(dir) {
        player.pos.x += dir;
        if (collide(arena, player)) {
            player.pos.x -= dir;
        }
    }

    function rotate(matrix, dir) {
        for (let y = 0; y < matrix.length; ++y) {
            for (let x = 0; x < y; ++x) {
                [matrix[x][y], matrix[y][x]] = [matrix[y][x], matrix[x][y]];
            }
        }
        if (dir > 0) matrix.forEach(row => row.reverse());
        else matrix.reverse();
    }

    function playerRotate(dir) {
        const pos = player.pos.x;
        let offset = 1;
        rotate(player.matrix, dir);
        while (collide(arena, player)) {
            player.pos.x += offset;
            offset = -(offset + (offset > 0 ? 1 : -1));
            if (offset > player.matrix[0].length) {
                rotate(player.matrix, -dir);
                player.pos.x = pos;
                return;
            }
        }
    }

    let dropCounter = 0;
    let dropInterval = 400;
    let lastTime = 0;

    function update(time = 0) {
        if (isPaused || gameOver) return;
        const deltaTime = time - lastTime;
        lastTime = time;
        dropCounter += deltaTime;
        if (dropCounter > dropInterval) playerDrop();
        draw();
        requestAnimationFrame(update);
    }

    function updateTime() {
        if (!gameOver && !isPaused) {
            timeCounter++;
            timeElem.textContent = timeCounter;
        }
        setTimeout(updateTime, 1000);
    }

    function showSaveButton() {
        saveButton.onclick = function() {
            const formData = new URLSearchParams();
            formData.append('score', score);
            formData.append('time', timeCounter);

            fetch('save_skor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: formData.toString()
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        saveStatus.textContent = "✅ Skor berhasil disimpan!";
                        saveStatus.classList.remove("text-red-400");
                        saveStatus.classList.add("text-green-400");
                    } else {
                        saveStatus.textContent = "❌ Gagal menyimpan skor: " + data.message;
                        saveStatus.classList.add("text-red-400");
                    }
                });
        };
    }

    function pauseGame() {
        isPaused = !isPaused;
        const btn = document.getElementById('pause-button');
        if (isPaused) {
            btn.textContent = '▶️ Lanjut';
        } else {
            btn.textContent = '⏸️ Pause';
            update();
        }
    }

    document.getElementById('pause-button').addEventListener('click', pauseGame);

    document.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft') playerMove(-1);
        else if (event.key === 'ArrowRight') playerMove(1);
        else if (event.key === 'ArrowDown') playerDrop();
        else if (event.key === 'ArrowUp') playerRotate(1);
    });

    playerReset();
    update();
    updateTime();
    showSaveButton();
    </script>
</body>

</html>