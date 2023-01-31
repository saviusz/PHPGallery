<?php
session_start();
require_once("./common/mysqli.php");

if (empty($_GET["id"])) {
    header("Location: ./index.php");
    exit();
}

$id = $_GET["id"];

$stmt = $mysqli->prepare(
    "SELECT photos.id,
    photos.description,
    photos.albumId,
    albums.title,
    users.login,
    ROUND(AVG(photos_ratings.rating),2) as rating,
    COUNT(photos_ratings.rating) as ratings_number
    FROM photos
    JOIN albums ON photos.albumId = albums.id
    JOIN users ON albums.authorId = users.id
    JOIN photos_ratings ON photos_ratings.photoId = photos.id
    WHERE photos.id=? AND photos.isAccepted=1"
);

$stmt->bind_param("i", $id);
$stmt->execute();
$image = $stmt->get_result()->fetch_assoc();

$imagePath = "./photo/{$image["albumId"]}/{$image["id"]}";

$commentsStmt = $mysqli->prepare(
    "SELECT photos_comments.*, users.login as author
    FROM photos_comments
    JOIN users ON users.id = photos_comments.authorId
    WHERE photos_comments.photoId=? AND photos_comments.isAccepted=1
    ORDER BY photos_comments.createdAt DESC"
);

$commentsStmt->bind_param("i", $id);
$commentsStmt->execute();

$comments = $commentsStmt->get_result();

$userRatingStmt = $mysqli->prepare("SELECT rating FROM photos_ratings WHERE photoId=? AND userId=?");
$userRatingStmt->bind_param("ii", $id, $_SESSION["loggedUser"]["id"]);
$userRatingStmt->execute();

$userRating = $userRatingStmt->get_result()->fetch_assoc()["rating"];
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
            <a href="./album.php?id=<?= $image["albumId"] ?>">Wróć do albumów</a>

            <div class="info">
                <p><?= $image["description"] ?></p>
                <h2><?= $image["title"] ?> - <span class="author"><?= $image["login"] ?></span></h2>
                <div class="rating">
                    <div id="stars" data-rating=<?= $userRating ?> data-id="<?= $image["id"] ?>"></div>
                    <div><?= $image["rating"] ?> / 10 (<?= $image["ratings_number"] ?>)</div>
                </div>
            </div>
            <div class="comments_container">
                <div class="comments">
                    <?php while ($comment = $comments->fetch_assoc()) : ?>
                        <?php
                        $formatDate = new DateTimeImmutable($comment["createdAt"]);
                        ?>
                        <div class="comment">
                            <div class="comment_heading">
                                <span class="author"><?= $comment["author"] ?></span>
                                <span class="date"> <?= $formatDate->format("j.m.Y \o H:i") ?></span>
                            </div>
                            <?= $comment["content"] ?>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="errors">
                    <?php
                    $errors = $_SESSION["comment_errors"] ?? [];
                    if (is_array($errors) && sizeof($errors) > 0) : ?>
                        <?php foreach ($errors as $error) : ?>
                            <div><?= $error ?></div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php
                    $_SESSION["comment_errors"] = [];
                    ?>
                </div>
                <?php if (!empty($_SESSION["loggedUser"])) : ?><form class="comment_inputs" method="POST" action="./files/add-comment.php">
                        <input type="text" name="comment">
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <button>Dodaj</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <?php include("./include/footer.php") ?>
</body>

</html>