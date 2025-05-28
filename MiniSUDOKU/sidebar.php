<div class="avatar">
    <?php if (!$isLoggedIn): ?>
        <img src="avatars/incognito.jpg" alt="Avatar">
    <?php else: ?>
        <!--dziewczynka lub chłopiec-->
    <?php endif; ?>
</div>

<?php if (!$isLoggedIn): ?>
    <button>Zaloguj się</button>
<?php else: ?>
    <button>Wyloguj się</button>
<?php endif; ?>

<hr>
<div class="menu">
    <button class="locked" disabled>Moje osiągnięcia <i class="bi bi-lock"></i></button>
    <button class="locked" disabled>Historia <i class="bi bi-lock"></i></button>
    <form method="post">
        <button type="submit" name="new_game" style="border: 3px solid #5d37a6;">Nowa gra</button>
    </form>
</div>