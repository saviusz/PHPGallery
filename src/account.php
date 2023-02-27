<?php
session_start();

require_once("./common/mysqli.php");

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
    <link rel="stylesheet" href="./style/account.css">

    <script src="./javascript/admin.js"></script>
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
                    <span class="name">Nazwa</span>
                    <span><?= $_SESSION["loggedUser"]["login"] ?></span>
                </div>
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
                    <input type="password" name="password">
                </div>

                <button type="submit">Aktualizuj dane</button>
            </form>
            <div class="errors">
                <?php
                $errors = $_SESSION["user_mod_errors"] ?? [];
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                ?>
                        <div><?= $error ?></div>
                <?php
                    }
                }

                ?>
            </div>
        </section>
        <?php
        $stmt = $mysqli->prepare("SELECT * FROM albums WHERE authorId=(?)");
        $stmt->bind_param("i", $_SESSION["loggedUser"]["id"]);
        $stmt->execute();

        $albums = $stmt->get_result();
        ?>
        <section id="albums">
            <h2>Albumy</h2>
            <div class="table">
                <?php while ($album = $albums->fetch_assoc()) : ?>
                    <div>
                        <span class="text"><?= $album["title"] ?></span>
                        <button onclick="changeAlbumTitle(<?= $album['id'] ?>, '<?= $album['title'] ?>' )">Zmień nazwę</button>
                        <button onclick="deleteAlbum(<?= $album['id'] ?>)">Usuń album</button>
                    </div>
                <?php endwhile; ?>
            </div>
        </section>
        <?php
        $albumId = $_GET["albumId"] ?? 0;

        $albumsStmt = $mysqli->prepare("SELECT * FROM albums WHERE authorId=(?)");
        $albumsStmt->bind_param("i", $_SESSION["loggedUser"]["id"]);
        $albumsStmt->execute();

        $albums = $albumsStmt->get_result();


        $stmt = $mysqli->prepare("SELECT photos.id, photos.description, photos.albumId, albums.title, albums.authorId FROM photos INNER JOIN albums ON photos.albumId = albums.id WHERE authorId=(?) AND albumId=(?)");
        $stmt->bind_param("ii", $_SESSION["loggedUser"]["id"], $albumId);
        $stmt->execute();

        $photos = $stmt->get_result();
        ?>
        <section id="photos">
            <h2>Zdjęcia w albumach</h2>
            <div>
                Wybierz album:
                <select onchange="changeAlbum(this)">
                    <option disabled <?= 0 == $albumId ? "selected" : "" ?>>Nie wybrano</option>
                    <?php while ($album = $albums->fetch_assoc()) : ?>
                        <option value="<?= $album["id"] ?>" <?= $album["id"] == $albumId ? "selected" : "" ?>><?= $album["title"] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="table">
                <?php while ($photo = $photos->fetch_assoc()) : ?>
                    <div class="photo-row">
                        <img src="./photo/<?= $photo["albumId"] ?>/<?= $photo["id"] ?>.min.webp" alt="">
                        <span class="text"><?= $photo["description"] ?></span>
                        <button onclick="modifyPhoto(<?= $photo['id'] ?>, '<?= $photo['description'] ?>')">Edytuj opis</button>
                        <button onclick="deletePhoto(<?= $photo['id'] ?>)">Usuń zdjęcie</button>
                    </div>
                <?php endwhile;
                if ($photos->num_rows < 1) : ?>
                    Brak zdjęć do pokazania
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>

</html>