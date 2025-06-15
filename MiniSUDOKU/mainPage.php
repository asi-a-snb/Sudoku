<?php
    session_start();
    $isLoggedIn = isset($_SESSION['user']); //sesja logowania
    function generateValidSudoku() {  //funkcja generowania tablicy sudoku
        $baseGrid = [[1, 2, 3, 4],    
                     [3, 4, 1, 2],
                     [2, 1, 4, 3],
                     [4, 3, 2, 1]]; //podstawowa baza sudoku
    
        shuffle($baseGrid);    //mieszanie wierszy - zamiana miejsc
        for ($i = 0; $i < 4; $i += 2) {
            if (rand(0, 1)) {
                [$baseGrid[$i], $baseGrid[$i + 1]] = [$baseGrid[$i + 1], $baseGrid[$i]];
            }
        }
    
        $transposed = array_map(null, ...$baseGrid);  //zamiana kolumn na wiersze
        shuffle($transposed); //mieszanie kolumn "wierszy"
        for ($i = 0; $i < 4; $i += 2) {
            if (rand(0, 1)) {
                [$transposed[$i], $transposed[$i + 1]] = [$transposed[$i + 1], $transposed[$i]];
            }
        }
    
        $finalGrid = array_map(null, ...$transposed); //wracanie do układu wierszy i kolumn
    
        $shown = []; //losowanie 5 widocznych liczb
        while (count($shown) < 6) {
            $i = rand(0, 3);
            $j = rand(0, 3);
            $shown["$i-$j"] = true;
        }
    
        $_SESSION['full_solution'] = $finalGrid; //"zapis" poprawnego sudoku
        $_SESSION['shown'] = $shown; //"zapis" pokazywanych liczb
    }
    
    if (!isset($_SESSION['full_solution']) || isset($_POST['new_game'])) {
        generateValidSudoku();  //generowanie nowego sudoku
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
            <form method="post">
            <div id="overlay" class="overlay" style="<?php echo isset($_POST['solve']) ? 'display: none;' : ''; ?>">
                <button type="button" id="startTimerBtn"><i class="bi bi-play"></i></button>
            </div>

                <div class="board-wrapper">
                    <div class="grid">
                        <?php 
                            for ($i = 0; $i < 4; $i++) {
                                for ($j = 0; $j < 4; $j++) {
                                    $name = "cell[$i][$j]";
                                    $isPrefilled = isset($_SESSION['shown']["$i-$j"]);
                                    $value = $isPrefilled
                                        ? $_SESSION['full_solution'][$i][$j]
                                        : (isset($_POST['cell'][$i][$j]) ? $_POST['cell'][$i][$j] : '');
                            
                                    $class = $isPrefilled ? 'prefilled' : 'userfill';
                                    $readonly = $isPrefilled ? 'readonly' : '';
                                    $isIncorrect = isset($_POST['solve']) && !$isPrefilled && $_SESSION['full_solution'][$i][$j] != $value;
                                    $extraClass = $isIncorrect ? ' incorrect' : '';
                                    $disabled = isset($_POST['solve']) ? 'readonly' : '';
                                    echo '<input type="text" name="' . $name . '" maxlength="1" pattern="[1-4]" class="cell ' . $class . $extraClass . '" value="' . htmlspecialchars($value) . '" ' . $readonly . ' ' . $disabled . '>';

                                }
                            }                            
                        ?>
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
                                $percentage = round(($correct / $total) * 100);
                                $elapsed = $_POST['elapsed_time'] ?? 0;
                                $points = max(0, 10000 - ($elapsed * 10) - ((100 - $percentage) * 100));

                                echo "$percentage%<br>Punkty: $points<br><span id='live-time'>Czas: $elapsed s</span>";
                            } else {
                                echo "0%<br>Punkty: 0<br>Czas: 0 s";
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="controls">
                    <button type="submit" name="solve" id="solveBtn">Rozwiąż</button>
                    <button type="button" id="stopTimerBtn"><i class="bi bi-stop-circle"></i></button>
                    <button type="button" id="clearBoardBtn"><i class="bi bi-x-circle"></i></button>
                </div>
                <input type="hidden" name="elapsed_time" id="elapsed_time" value="0">
            </form>
        </main>
    </div>

    <script>
        let startTime = 0;
        let interval = null;

        function startTimer() {
            document.getElementById('overlay').style.display = 'none';
            startTime = Date.now() - (parseInt(document.getElementById('elapsed_time').value) * 1000 || 0);
            interval = setInterval(() => {
                const elapsed = Math.floor((Date.now() - startTime) / 1000);
                document.getElementById('elapsed_time').value = elapsed;
            }, 1000);
        }

        function stopTimer() {
            clearInterval(interval);
            document.getElementById('overlay').style.display = 'flex';
        }

        function clearBoard() {
            document.querySelectorAll('.userfill').forEach(input => input.value = '');
        }

        window.onload = () => {
            document.getElementById('startTimerBtn').addEventListener('click', startTimer);
            document.getElementById('stopTimerBtn').addEventListener('click', stopTimer);
            document.getElementById('clearBoardBtn').addEventListener('click', clearBoard);
        }
    </script>

</body>
</html>
