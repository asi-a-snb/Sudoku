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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"> 
</head>
<body>
    <header>
        <?php
            include_once "header.php";
        ?>
    </header>
    <div class="container">
        <aside class="sidebar">
            <?php
                include_once "sidebar.php";
            ?>
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
