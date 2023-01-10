<?php
session_start();
require_once("./common/mysqli.php");

if (empty($_GET["id"])) {
    header("Location: ./index.php");
    exit();
}

$id = $_GET["id"];

$stmt = $mysqli->prepare(
    "SELECT photos.id,photos.description,photos.albumId,albums.title,users.login
    FROM photos
    JOIN albums ON photos.albumId = albums.id
    JOIN users ON albums.authorId = users.id
    WHERE photos.id=? AND photos.isAccepted=1"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$image = $stmt->get_result()->fetch_assoc();

$imagePath = "./photo/{$image["albumId"]}/{$image["id"]}";
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <title>Galeria</title>
    <link rel="stylesheet" href="./style/photo.css">
    <script src="./javascript/stars.js" defer></script>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <img src="<?= $imagePath ?>.webp" alt="elo" class="image">
        <div class="background">
            <img src="<?= $imagePath ?>.min.webp" alt="elo">
        </div>
        <div class="content">
            <a href="./album.php?id=<?= $image["albumId"]?>">Wróć do albumów</a>

            <div class="info">
                <p><?= $image["description"] ?></p>
                <h2><?= $image["title"] ?> - <span class="author"><?= $image["login"] ?></span></h2>
                <div id="stars" data-rating=0 data-id="<?= $image["id"] ?>"></div>
            </div>
            <div class="comments_container">
                <div class="comments">
                    <?php for ($i = 0; $i < 16; $i++) : ?>
                        <div class="comment">
                            <span class="author">Człowiek</span>
                            trochę komentarza idzie tutaj potrzebu trochę kometrzebu tro chę komenta rza idzie tuta j potrzebu
                        </div>
                        <?php endfor; ?>
                </div>
            <form class="comment_inputs">
                <input type="text" name="comment">
                <button>Dodaj</button>
            </form>
            </div>
        </div>
    </main>

    <?php include("./include/footer.php") ?>
</body>

</html>