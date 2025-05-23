<?php
session_start();
$isLoggedIn = isset($_SESSION['user']);
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>MiniSUDOKU</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>MiniSUDOKU</h1>
    </header>
    <div class="container">
        <aside class="sidebar">
            <div class="avatar">
                <img src="" alt="Avatar">
            </div>
            <?php if (!$isLoggedIn): ?>
                <button>Zaloguj się</button>
            <?php else: ?>
                <p>Witaj, <?= $_SESSION['user'] ?></p>
            <?php endif; ?>
            <hr>
            <div class="menu">
                <button class="locked" disabled>Moje osiągnięcia 🔒</button>
                <button class="locked" disabled>Historia 🔒</button>
                <form method="post">
                    <button type="submit" name="new_game">Nowa gra</button>
                </form>
            </div>
        </aside>
        <!--Cokolwiek-->
        <main class="sudoku-board">
            <form method="post">
                <div class="grid">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                        <?php for ($j = 0; $j < 4; $j++): ?>
                            <input type="text" name="cell[<?= $i ?>][<?= $j ?>]" maxlength="1" pattern="[1-4]" class="cell">
                        <?php endfor; ?>
                    <?php endfor; ?>
                </div>
                <div class="controls">
                    <button type="submit" name="solve">Rozwiąż</button>
                </div>
            </form>
        </main>
    </div>
</body>
</html>
