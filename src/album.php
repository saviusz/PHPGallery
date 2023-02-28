<?php
session_start();
require_once("./common/mysqli.php");

if (empty($_GET["id"])) {
    header("Location: ./index.php");
    exit();
}

$id = $_GET["id"];

$numberOnPage = 20;
$page = $_GET["page"] ?? 1;

$numberStmt = $mysqli->prepare(
    "SELECT CEILING(COUNT(photos.id) / $numberOnPage) as number
    FROM photos
    WHERE photos.albumId=? AND photos.isAccepted=1"
);
$numberStmt->bind_param("i", $id);
$numberStmt->execute();
$res = $numberStmt->get_result();
$numberOfPages = $res->fetch_assoc()["number"];

$page = intval($page > $numberOfPages && $page < 1 ? 1 : $page);

$stmt = $mysqli->prepare(
    "SELECT photos.id
    FROM photos
    WHERE photos.albumId=? AND photos.isAccepted=1
    ORDER BY photos.createdAt DESC
    LIMIT ? OFFSET ?"
);
$startFrom = ($numberOnPage * ($page - 1));

$stmt->bind_param("iii", $id, $numberOnPage, $startFrom);
$stmt->execute();
$images = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <link rel="stylesheet" href="./style/gallery.css">
    <link rel="stylesheet" href="./style/album.css">
    <title>Galeria</title>
    <script src="./javascript/gallery.js"></script>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <a href="./">Wróć do albumów</a>
        <section class="gallery">
            <?php while ($row = $images->fetch_assoc()) : ?>
                <a href="<?= "./photo.php?id={$row["id"]}" ?>" class="image-wrapper">
                    <img src="<?= "./photo/$id/{$row["id"]}.min.webp" ?>" alt="">
                </a>
            <?php endwhile; ?>
        </section>
        <div class="pagination">
            <?php if ($page > 1) : ?> <button onclick="setPage(<?= $page - 1 ?>)">Poprzednia</button><?php endif ?>
            <?php for ($iter = max(1, $page - 2); $iter < (min($page + 2, $numberOfPages) + 1); $iter++) : ?>
                <button onclick="setPage(<?= $iter ?>)" class="<?= $page == $iter ? "selected" : "" ?>"><?= $iter ?></button>
            <?php endfor;  ?>
            <?php if ($page < $numberOfPages) : ?><button onclick="setPage(<?= $page + 1 ?>)">Następna</button><?php endif ?>
        </div>
        <a href="./">Wróć do albumów</a>
    </main>

    <?php include("./include/footer.php") ?>
</body>

</html>