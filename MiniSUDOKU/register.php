<?php

    $conn = mysqli_connect("localhost", "root", "", "minisudoku"); //połączenie z bazą

    if (!$conn) {
        die("Błąd połączenia z bazą danych: " . mysqli_connect_error()); //błąd połączenia z bazą
    }

    $registerError = ""; //error tworzenia konta

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username']; //nazwa użytkownika
        $password = $_POST['password']; //hasło
        $avatar = $_POST['avatar']; //wybranie avataru

        // Sprawdzenie czy nazwa użytkownika się nie powtarza w bazie
        $stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE username = ?");
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $registerError = "Użytkownik o tej nazwie już istnieje."; //gdy istnieje "error"
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT); //kodowanie hasła
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, password_hash, avatar_path) VALUES (?, ?, ?)"); //wprowadzenie nowego użytkownika do bazy
            mysqli_stmt_bind_param($stmt, "sss", $username, $hashed, $avatar);
            mysqli_stmt_execute($stmt);
            header("Location: mainPage.php"); //przekierowanie do strony głównej
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="pl-PL">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MiniSUDOKU rejestracja</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="register-box">
        <h2>Stwórz konto</h2>
        <hr>
        <form method="post">
            <label for="username">Nazwa użytkownika</label>
            <input type="text" name="username" id="username" placeholder="Nazwa użytkownika" required>

            <label for="password">Hasło</label>
            <input type="password" name="password" id="password" placeholder="Hasło" required>

            <div class="avatar-choice">
                <label class="avatar-option">
                    <input type="radio" name="avatar" value="avatars/boy.jpg" checked>
                    <img src="avatars/boy.jpg" alt="Chłopiec">
                </label>
                <label class="avatar-option">
                    <input type="radio" name="avatar" value="avatars/girl.jpg">
                    <img src="avatars/girl.jpg" alt="Dziewczynka">
                </label>
            </div>

            <div class="buttons">
                <a href="mainPage.php" class="back-button">Powrót</a>
                <button type="submit">Zarejestruj się</button>
            </div>

            <?php if (!empty($registerError)): ?>
                <p class="error"><?= $registerError ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
