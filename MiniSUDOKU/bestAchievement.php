<?php
    session_start();
    $conn = mysqli_connect("localhost", "root", "", "minisudoku"); //połączenie
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Osiągnięcie</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .achievement-display {
            text-align: center;
            font-family: Arial, sans-serif;
            color: #333;
            margin-top: 30px;
        }

        .achievement-display img.crown {
            width: 80px;
            height: auto;
            margin-bottom: 15px;
        }

        .achievement-display .points {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .achievement-display .percent-correct,
        .achievement-display .time {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .achievement-display .date {
            font-size: 18px;
            color: #666;
        }
    </style>
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
            <?php
                if ($isLoggedIn) {
                    $userId = $_SESSION['user']['id'];
                    
                    $query = "SELECT * FROM achievements WHERE user_id = ? ORDER BY points DESC LIMIT 1";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, "i", $userId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($achievement = mysqli_fetch_assoc($result)) {
                        $minutes = floor($achievement['time_seconds'] / 60);
                        $seconds = $achievement['time_seconds'] % 60;
                        $timeFormatted = sprintf("%02d:%02d", $minutes, $seconds);

                        $dateFormatted = date("d.m.Y H:i", strtotime($achievement['date']));

                        echo '
                        <div class="achievement-display">
                            <img src="images/crown.png" alt="Korona" class="crown" />
                            <div class="points">' . $achievement['points'] . ' punktów</div>
                            <div class="percent-correct">Poprawność: ' . $achievement['precent_correct'] . '%</div>
                            <div class="time">Czas: ' . $timeFormatted . '</div>
                            <div class="date">' . $dateFormatted . '</div>
                        </div>';
                    } else {
                        echo '<p style="text-align:center; color:#666;">Brak osiągnięć do wyświetlenia.</p>';
                    }
                } else {
                    echo '<p style="text-align:center; color:#666;">Zaloguj się, aby zobaczyć swoje osiągnięcia.</p>';
                }
                ?>
        </main>
    </div>
</body>
</html>
<?php
    mysqli_close($conn);
?>