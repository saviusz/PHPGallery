<?php
session_start();
$user = $_SESSION["loggedUser"];
$isLogged = !empty($user);

function hrefIsselected($href)
{
    return "href=$href class=" . (parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) == $href ? "selected" : "");
}

?>

<section class="menu">
    <nav>
        <a <?= hrefIsselected("/") ?>>Galeria</a>
        <a href="<?= $isLogged ? "/add-album.php" : "/logreg.php" ?>">Załóż album</a>
        <a href="<?= $isLogged ? "/add-photo.php" : "/logreg.php" ?>">Dodaj zdjęcie</a>
        <a <?= hrefIsselected("/best-photos.php") ?>>Najlepiej oceniane</a>
        <a <?= hrefIsselected("/newest-photos.php") ?>>Najnowsze</a>
        <?php if ($isLogged) : ?>
            <a href="/account.php">Moje konto</a>
            <a href="/auth/logout.php">Wyloguj</a>
            <?php if ($user["role"] == "moderator" || $user["role"] == "admin") : ?>
                <a href="/admin/">Panel administracyjny</a>
            <? endif ?>
        <?php else : ?>
            <a <?= hrefIsselected("/logreg.php") ?>>Zaloguj</a>
            <a <?= hrefIsselected("/logreg.php") ?>>Zarejestruj</a>
        <?php endif ?>
    </nav>
</section>