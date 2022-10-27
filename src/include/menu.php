<?php
session_start();
$user = $_SESSION["loggedUser"];
$isLogged = !empty($user);
?>

<section class="menu">
    <nav>
        <a href="/">Galeria</a>
        <a href="<?= $isLogged ? "/add-album.php" : "/logreg.php" ?>">Załóż album</a>
        <a href="<?= $isLogged ? "/add-photo.php" : "/logreg.php" ?>">Dodaj zdjęcie</a>
        <a href="/best-photos.php">Najlepiej oceniane</a>
        <a href="/newest-photos.php">Najnowsze</a>
        <?php if ($isLogged) : ?>
            <a href="/account.php">Moje konto</a>
            <a href="/auth/logout.php">Wyloguj</a>
            <?php if ($user["role"] == "moderator" || $user["role"] == "admin") : ?>
                <a href="/admin/">Panel administracyjny</a>
            <? endif ?>
        <?php else : ?>
            <a href="/logreg.php">Zaloguj</a>
            <a href="/logreg.php">Zarejestruj</a>
        <?php endif ?>
    </nav>
</section>