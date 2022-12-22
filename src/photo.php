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
    WHERE photos.albumId=? AND photos.isAccepted=1"
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

            <?php var_export($image) ?>

            <div class="info">
                <p><?= $image["description"] ?></p>
                <h2><?= $image["title"] ?> - <span class="author"><?= $image["login"] ?></span></h2>
                <div id="stars" data-rating=0 data-id="<?= $image["id"] ?>"></div>
            </div>
        </div>
    </main>

    <?php include("./include/footer.php") ?>
</body>

</html>