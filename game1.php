<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Game Ular Lucu 🐍</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        background-color: #e3f2fd;
        font-family: Arial, sans-serif;
        overflow: hidden;
    }

    h1 {
        color: purple;
        text-align: center;
        margin-top: 20px;
    }

    #score {
        text-align: center;
        font-size: 18px;
        color: green;
        margin-bottom: 10px;
    }

    canvas {
        background: #111;
        border: 4px dashed purple;
        display: block;
        margin: 0 auto;
        max-width: 90vw;
        max-height: 90vh;
        width: 400px;
        height: 400px;
    }

    #startButton {
        margin-top: 20px;
        padding: 10px 20px;
        font-size: 18px;
        background: purple;
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    #gameOverBox {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border: 3px solid purple;
        border-radius: 15px;
        display: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    #mobile-controls {
        display: none;
        margin-top: 10px;
    }

    .control-button {
        width: 60px;
        height: 60px;
        font-size: 24px;
        margin: 5px;
        background-color: #4caf50;
        color: white;
        border: none;
        border-radius: 12px;
    }

    .arrow-row {
        display: flex;
        justify-content: center;
    }

    /* Tampilkan kontrol di perangkat kecil */
    @media only screen and (max-width: 768px) {
        #mobile-controls {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    }

    /* Tombol kembali */
    #back-button {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #2196f3;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 14px;
        text-decoration: none;
        z-index: 999;
    }

    #back-button:hover {
        background: #1976d2;
    }
    </style>
</head>

<body>
    <!-- Tombol kembali -->
    <a href="index.php" id="back-button">← Kembali</a>

    <h1>Game Ular Lucu 🐍</h1>
    <div id="score">Buah dimakan: 0</div>

    <button id="startButton">Mulai</button>
    <canvas id="gameCanvas" width="400" height="400" style="display: none;"></canvas>

    <div id="mobile-controls">
        <div><button class="control-button" onclick="setDirection('up')">⬆️</button></div>
        <div class="arrow-row">
            <button class="control-button" onclick="setDirection('left')">⬅️</button>
            <button class="control-button" onclick="setDirection('down')">⬇️</button>
            <button class="control-button" onclick="setDirection('right')">➡️</button>
        </div>
    </div>

    <div id="gameOverBox">
        <h2>Game Over!</h2>
        <p id="finalScore"></p>
        <button onclick="restartGame()"
            style="padding: 8px 20px; background: purple; color: white; border: none; border-radius: 10px;">Main
            Lagi</button>
    </div>

    <script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");
    const scoreText = document.getElementById("score");
    const gameOverBox = document.getElementById("gameOverBox");
    const finalScoreText = document.getElementById("finalScore");
    const startButton = document.getElementById("startButton");

    let snake = [{
        x: 10,
        y: 10
    }];
    let food = {
        x: 15,
        y: 15
    };
    let dx = 1,
        dy = 0;
    const gridSize = 20;
    const tileCount = canvas.width / gridSize;
    let score = 0;
    let startTime;
    let gameLoop;

    startButton.addEventListener("click", () => {
        startGame();
    });

    function startGame() {
        startButton.style.display = "none";
        canvas.style.display = "block";
        score = 0;
        snake = [{
            x: 10,
            y: 10
        }];
        dx = 1;
        dy = 0;
        food = {
            x: Math.floor(Math.random() * tileCount),
            y: Math.floor(Math.random() * tileCount)
        };
        scoreText.innerText = "Buah dimakan: 0";
        startTime = new Date();
        gameLoop = setInterval(updateGame, 100);
    }

    function drawGrid() {
        ctx.strokeStyle = "#444";
        for (let i = 0; i < tileCount; i++) {
            ctx.beginPath();
            ctx.moveTo(i * gridSize, 0);
            ctx.lineTo(i * gridSize, canvas.height);
            ctx.stroke();

            ctx.beginPath();
            ctx.moveTo(0, i * gridSize);
            ctx.lineTo(canvas.width, i * gridSize);
            ctx.stroke();
        }
    }

    function updateGame() {
        let head = {
            x: snake[0].x + dx,
            y: snake[0].y + dy
        };

        if (
            head.x < 0 || head.x >= tileCount ||
            head.y < 0 || head.y >= tileCount ||
            snake.some(s => s.x === head.x && s.y === head.y)
        ) {
            clearInterval(gameLoop);
            const endTime = new Date();
            const duration = ((endTime - startTime) / 1000 / 60).toFixed(2);
            finalScoreText.innerHTML = `Kamu bertahan selama ${duration} menit dan makan ${score} buah! 🍎`;
            gameOverBox.style.display = "block";
            return;
        }

        snake.unshift(head);

        if (head.x === food.x && head.y === food.y) {
            score++;
            scoreText.innerText = "Buah dimakan: " + score;
            food = {
                x: Math.floor(Math.random() * tileCount),
                y: Math.floor(Math.random() * tileCount)
            };
        } else {
            snake.pop();
        }

        ctx.fillStyle = "#111";
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        drawGrid();

        ctx.fillStyle = "lime";
        snake.forEach(part =>
            ctx.fillRect(part.x * gridSize, part.y * gridSize, gridSize, gridSize)
        );

        ctx.fillStyle = "red";
        ctx.beginPath();
        ctx.arc(
            food.x * gridSize + gridSize / 2,
            food.y * gridSize + gridSize / 2,
            gridSize / 2,
            0,
            Math.PI * 2
        );
        ctx.fill();
    }

    document.addEventListener("keydown", function(e) {
        if (e.key === "ArrowUp" && dy !== 1) {
            dx = 0;
            dy = -1;
        } else if (e.key === "ArrowDown" && dy !== -1) {
            dx = 0;
            dy = 1;
        } else if (e.key === "ArrowLeft" && dx !== 1) {
            dx = -1;
            dy = 0;
        } else if (e.key === "ArrowRight" && dx !== -1) {
            dx = 1;
            dy = 0;
        }
    });

    function setDirection(dir) {
        if (dir === "up" && dy !== 1) {
            dx = 0;
            dy = -1;
        }
        if (dir === "down" && dy !== -1) {
            dx = 0;
            dy = 1;
        }
        if (dir === "left" && dx !== 1) {
            dx = -1;
            dy = 0;
        }
        if (dir === "right" && dx !== -1) {
            dx = 1;
            dy = 0;
        }
    }

    function restartGame() {
        gameOverBox.style.display = "none";
        startGame();
    }
    </script>
</body>

</html>