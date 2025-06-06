<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Game 2048 🎮</title>
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 0;
        background: #faf8ef;
        font-family: 'Arial', sans-serif;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding-top: 60px;
    }

    a#back-button {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #2196f3;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        z-index: 1000;
    }

    a#back-button:hover {
        background: #1976d2;
    }

    h1 {
        color: #776e65;
    }

    #game {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        width: 90vmin;
        max-width: 400px;
        height: 90vmin;
        max-height: 400px;
        background: #bbada0;
        padding: 10px;
        border-radius: 10px;
        touch-action: none;
        /* Hindari scroll saat swipe */
    }

    .tile {
        background: #cdc1b4;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2em;
        font-weight: bold;
        color: #776e65;
    }

    .tile-0 {
        background: #cdc1b4;
        color: transparent;
    }

    .tile-2 {
        background: #eee4da;
    }

    .tile-4 {
        background: #ede0c8;
    }

    .tile-8 {
        background: #f2b179;
        color: #f9f6f2;
    }

    .tile-16 {
        background: #f59563;
        color: #f9f6f2;
    }

    .tile-32 {
        background: #f67c5f;
        color: #f9f6f2;
    }

    .tile-64 {
        background: #f65e3b;
        color: #f9f6f2;
    }

    .tile-128 {
        background: #edcf72;
        color: #f9f6f2;
    }

    .tile-256 {
        background: #edcc61;
        color: #f9f6f2;
    }

    .tile-512 {
        background: #edc850;
        color: #f9f6f2;
    }

    .tile-1024 {
        background: #edc53f;
        color: #f9f6f2;
    }

    .tile-2048 {
        background: #edc22e;
        color: #f9f6f2;
    }

    #gameOverBox {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 20px;
        border: 3px solid #888;
        border-radius: 10px;
        text-align: center;
        display: none;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        z-index: 1001;
    }

    #gameOverBox button {
        padding: 10px 20px;
        background: #2196f3;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    #gameOverBox button:hover {
        background: #1976d2;
    }
    </style>
</head>

<body>

    <a href="index.php" id="back-button">← Kembali</a>

    <h1>Game 2048 🎮</h1>
    <div id="game"></div>

    <div id="gameOverBox">
        <h2>Game Over 😢</h2>
        <p>Tidak ada lagi langkah yang bisa dilakukan!</p>
        <button onclick="startGame()">Main Lagi</button>
    </div>

    <script>
    const game = document.getElementById("game");
    const gameOverBox = document.getElementById("gameOverBox");

    let board = [];

    function startGame() {
        board = Array.from({
            length: 4
        }, () => Array(4).fill(0));
        addTile();
        addTile();
        draw();
        gameOverBox.style.display = "none";
    }

    function addTile() {
        const empty = [];
        for (let r = 0; r < 4; r++) {
            for (let c = 0; c < 4; c++) {
                if (board[r][c] === 0) empty.push({
                    r,
                    c
                });
            }
        }
        if (empty.length === 0) return;
        const {
            r,
            c
        } = empty[Math.floor(Math.random() * empty.length)];
        board[r][c] = Math.random() < 0.9 ? 2 : 4;
    }

    function draw() {
        game.innerHTML = "";
        for (let row of board) {
            for (let val of row) {
                const tile = document.createElement("div");
                tile.className = `tile tile-${val}`;
                tile.textContent = val || "";
                game.appendChild(tile);
            }
        }
    }

    function slide(row) {
        const arr = row.filter(v => v !== 0);
        for (let i = 0; i < arr.length - 1; i++) {
            if (arr[i] === arr[i + 1]) {
                arr[i] *= 2;
                arr[i + 1] = 0;
            }
        }
        return arr.filter(v => v !== 0).concat(Array(4 - arr.filter(v => v !== 0).length).fill(0));
    }

    function rotateClockwise(mat) {
        return mat[0].map((_, i) => mat.map(row => row[i]).reverse());
    }

    function rotateCounterClockwise(mat) {
        return rotateClockwise(rotateClockwise(rotateClockwise(mat)));
    }

    function move(direction) {
        let rotated = board;
        if (direction === "up") rotated = rotateCounterClockwise(board);
        if (direction === "right") rotated = rotateClockwise(rotateClockwise(board));
        if (direction === "down") rotated = rotateClockwise(board);

        const newBoard = rotated.map(slide);

        if (JSON.stringify(rotated) !== JSON.stringify(newBoard)) {
            if (direction === "up") board = rotateClockwise(newBoard);
            else if (direction === "right") board = rotateClockwise(rotateClockwise(newBoard));
            else if (direction === "down") board = rotateCounterClockwise(newBoard);
            else board = newBoard;

            addTile();
            draw();
            if (isGameOver()) {
                gameOverBox.style.display = "block";
            }
        }
    }

    function isGameOver() {
        for (let r = 0; r < 4; r++) {
            for (let c = 0; c < 4; c++) {
                if (board[r][c] === 0) return false;
                if (c < 3 && board[r][c] === board[r][c + 1]) return false;
                if (r < 3 && board[r][c] === board[r + 1][c]) return false;
            }
        }
        return true;
    }

    document.addEventListener("keydown", e => {
        switch (e.key) {
            case "ArrowLeft":
                e.preventDefault();
                move("left");
                break;
            case "ArrowRight":
                e.preventDefault();
                move("right");
                break;
            case "ArrowUp":
                e.preventDefault();
                move("up");
                break;
            case "ArrowDown":
                e.preventDefault();
                move("down");
                break;
        }
    });

    // ✅ Deteksi swipe
    let startX = 0;
    let startY = 0;

    game.addEventListener("touchstart", e => {
        if (e.touches.length === 1) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        }
    });

    game.addEventListener("touchend", e => {
        if (e.changedTouches.length === 1) {
            const dx = e.changedTouches[0].clientX - startX;
            const dy = e.changedTouches[0].clientY - startY;
            const absX = Math.abs(dx);
            const absY = Math.abs(dy);

            if (Math.max(absX, absY) > 30) {
                if (absX > absY) {
                    move(dx > 0 ? "right" : "left");
                } else {
                    move(dy > 0 ? "down" : "up");
                }
            }
        }
    });

    startGame();
    </script>

</body>

</html>