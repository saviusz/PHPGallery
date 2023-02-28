<?php
session_start();
require_once("./common/mysqli.php");

$stmt = $mysqli->prepare(
    "SELECT photos.id, photos.albumId
    FROM photos
    WHERE photos.isAccepted=1
    ORDER BY photos.createdAt DESC
    LIMIT 20"
);

$stmt->execute();
$images = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <link rel="stylesheet" href="./style/gallery.css">
    <title>Najnowsze zdjęcia</title>
    <script src="./javascript/gallery.js"></script>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <section class="gallery">
            <?php while ($row = $images->fetch_assoc()) : ?>
                <a href="<?= "./photo.php?id={$row["id"]}" ?>" class="image-wrapper">
                    <img src="<?= "./photo/{$row["albumId"]}/{$row["id"]}.min.webp" ?>" alt="">
                </a>
            <?php endwhile; ?>
        </section>
    </main>
    <?php include("./include/footer.php") ?>
</body>

</html>