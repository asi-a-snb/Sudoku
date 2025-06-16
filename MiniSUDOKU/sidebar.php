<?php
        $isLoggedIn = isset($_SESSION['user']); //sesja logowania użytkownika
        $username = $isLoggedIn ? $_SESSION['user']['username'] : ''; //nazwa zalogowanego użytkownika
        $avatar = $isLoggedIn ? $_SESSION['user']['avatar_path'] : 'avatars/incognito.jpg'; //avatar użytkownika
        
        //Logowanie
        $loginError = ''; //błąd
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) { //logowanie się
            $inputUser = $_POST['username'];
            $inputPass = $_POST['password'];
        
            $query = "SELECT * FROM users WHERE username = ?"; //zapytanie do bazy z wyborem użytkowników
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, "s", $inputUser);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        
            if ($row = mysqli_fetch_assoc($result)) {
                if (password_verify($inputPass, $row['password_hash'])) { //sprawdzanie hasła
                    $_SESSION['user'] = $row; //sesja użytkownika
                    header("Location: mainPage.php");
                    exit;
                } else {
                    $loginError = "Nieprawidłowe hasło."; //brak spójności hała
                }
            } else {
                $loginError = "Nie znaleziono użytkownika."; //brak użytkownika o takiej nazwie
            }
        }
        
        // Wylogowanie
        if (isset($_POST['logout'])) {
            session_destroy();
            header("Location: mainPage.php");
            exit;
        }
?>
<div class="avatar">
    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar">
</div>

<?php if (!$isLoggedIn): ?>
    <?php if (!empty($loginError)): ?>
        <p style="color:red;"><?= $loginError ?></p>
    <?php endif; ?>
    
    <form method="post" style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
        <input type="text" name="username" placeholder="Nazwa użytkownika" required>
        <input type="password" name="password" placeholder="Hasło" required>
        <button type="submit" name="login">Zaloguj się</button>
    </form>
    <a href="register.php">Stwórz konto</a>
<?php else: ?>
    <p><?= htmlspecialchars($username) ?></p>
    <form method="post">
        <button type="submit" name="logout">Wyloguj się</button>
    </form>
<?php endif; ?>

<hr>

<div class="menu">
    <?php if ($isLoggedIn): ?>
        <form method="post" action="bestAchievement.php" style="margin-bottom: 10px;">
            <button type="submit">Moje osiągnięcia</button>
        </form>
        <form method="post" action="history.php" style="margin-bottom: 10px;">
            <button type="submit">Historia</button>
        </form>
    <?php else: ?>
        <button class="locked" disabled>Moje osiągnięcia</button>
        <button class="locked" disabled>Historia</button>
    <?php endif; ?>
    <form method="post" action="mainPage.php" style="margin-top:10px;">
        <button type="submit" name="new_game" style="border: 3px solid #5d37a6;">Nowa gra</button>
    </form>
</div>

