<?php
session_start();
require_once("./common/mysqli.php");

$stmt = $mysqli->prepare(
    "SELECT photos.id, photos.albumId, AVG(photos_ratings.rating) as rating
    FROM photos
    INNER JOIN photos_ratings
    ON photos_ratings.photoId = photos.id
    WHERE photos.isAccepted=1
    GROUP BY photos.id
    ORDER BY rating DESC
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
    <title>Najlepsze zdjęcia</title>
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