<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "minisudoku"); //połączenie
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia osiągnięć</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <?php
            include_once "header.php"; //nagłówek
        ?>
    </header>
    <div class="container">
        <aside class="sidebar">
            <?php
                include_once "sidebar.php";  //nawigacja boczna
            ?>
        </aside>
        <main class="sudoku-board">

        </main>
    </div>
</body>
</html>
<?php
    mysqli_close($conn);
?>