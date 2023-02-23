<?php
session_start();
if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <title>Moje konto</title>
    <link rel="stylesheet" href="./style/admin-panel.css">
</head>

<body>
    <nav>
        <section>
            <h3> Na tej stronie </h3>
            <a href="#data">Moje dane</a>
            <a href="#albums">Moje albumy</a>
            <a href="#photos">Moje zdjęcia</a>
        </section>
        <section>
            <h3> Inne strony </h3>
            <a href="./admin.php">Panel admina</a>
            <a href="./index.php">Strona główna</a>
        </section>
    </nav>
    <main>
        <section id="data">
            <h2>Dane</h2>
            <form action="./files/modify-user.php" method="post">
                <div>
                    <span class="name">Nowe hasło</span>
                    <input type="password" name="n_password">
                </div>
                <div>
                    <span class="name">Powtórz nowe hasło</span>
                    <input type="password" name="nr_password">
                </div>

                <div>
                    <span class="name">Nowy email</span>
                    <input type="email" name="email">
                </div>

                <br>

                <div>
                    <span class="name">Stare hasło</span>
                    <input type="password" name="r_password">
                </div>

                <button type="submit">Aktualizuj dane</button>
            </form>
        </section>
        <section id="albums">
            <div class="table">
                <div>
                    <span>Nazwa albumu</span>
                    <button onclick="changeAlbumTitle()">Zmień nazwę</button>
                    <button onclick="changeAlbumTitle()">Usuń album</button>
                </div>
            </div>
        </section>
        <section id="photos">

        </section>
    </main>
</body>

</html>