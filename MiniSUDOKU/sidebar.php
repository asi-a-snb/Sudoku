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
    <button <?= !$isLoggedIn ? 'class="locked" disabled' : '' ?>>Moje osiągnięcia</button>
    <button <?= !$isLoggedIn ? 'class="locked" disabled' : '' ?>>Historia</button>
    <form method="post">
        <button type="submit" name="new_game" style="border: 3px solid #5d37a6;">Nowa gra</button>
    </form>
</div>
