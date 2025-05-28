<?php
    session_start();
    $isLoggedIn = isset($_SESSION['user']);
    function generateValidSudoku() {
        $baseGrid = [[1, 2, 3, 4],
                     [3, 4, 1, 2],
                     [2, 1, 4, 3],
                     [4, 3, 2, 1]];
    
        shuffle($baseGrid);
        for ($i = 0; $i < 4; $i += 2) {
            if (rand(0, 1)) {
                [$baseGrid[$i], $baseGrid[$i + 1]] = [$baseGrid[$i + 1], $baseGrid[$i]];
            }
        }
    
        $transposed = array_map(null, ...$baseGrid);
        shuffle($transposed);
        for ($i = 0; $i < 4; $i += 2) {
            if (rand(0, 1)) {
                [$transposed[$i], $transposed[$i + 1]] = [$transposed[$i + 1], $transposed[$i]];
            }
        }
    
        $finalGrid = array_map(null, ...$transposed);
    
        $shown = [];
        while (count($shown) < 6) {
            $i = rand(0, 3);
            $j = rand(0, 3);
            $shown["$i-$j"] = true;
        }
    
        $_SESSION['full_solution'] = $finalGrid;
        $_SESSION['shown'] = $shown;
    }
    
    if (!isset($_SESSION['full_solution']) || isset($_POST['new_game'])) {
        generateValidSudoku();
    }    
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
                <div class="board-wrapper">
                    <div class="grid">
                        <?php for ($i = 0; $i < 4; $i++): ?>
                            <?php for ($j = 0; $j < 4; $j++): 
                                $name = "cell[$i][$j]";
                                $isPrefilled = isset($_SESSION['shown']["$i-$j"]);
                                $value = $isPrefilled
                                    ? $_SESSION['full_solution'][$i][$j]
                                    : ($_POST['cell'][$i][$j] ?? '');
                                ?>
                                <input
                                    type="text"
                                    name="<?= $name ?>"
                                    maxlength="1"
                                    pattern="[1-4]"
                                    class="cell <?= $isPrefilled ? 'prefilled' : 'userfill' ?>"
                                    value="<?= htmlspecialchars($value) ?>"
                                    <?= $isPrefilled ? 'readonly' : '' ?>
                                >
                            <?php endfor; ?>
                        <?php endfor; ?>
                    </div>
                    <div class="score-panel">
                        <p>Twój wynik:</p>
                        <div class="score">
                            <?php
                            if (isset($_POST['solve'])) {
                                $correct = 0;
                                $total = 0;
                                foreach ($_POST['cell'] as $i => $row) {
                                    foreach ($row as $j => $value) {
                                        $total++;
                                        if ($_SESSION['full_solution'][$i][$j] == $value) {
                                            $correct++;
                                        }
                                    }
                                }
                                echo round(($correct / $total) * 100) . "%";
                            } else {
                                echo "0%";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="controls">
                    <button type="submit" name="solve">Rozwiąż</button>
                </div>
            </form>
        </main>

    </div>
</body>
</html>
