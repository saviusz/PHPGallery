<?php
session_start();
if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

require_once("./common/mysqli.php");

$stmt = $mysqli->prepare(
    "SELECT albums.id,albums.title
    FROM albums
    WHERE albums.authorId=?"
);
$stmt->bind_param("i", $_SESSION["loggedUser"]["id"]);
$stmt->execute();
$albums = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="pl">

<head>
    <?php include "./include/headers.php" ?>
    <link rel="stylesheet" href="./style/add-photo.css">
    <link rel="stylesheet" href="./style/gallery.css">
    <title>Dodaj album</title>
</head>

<body>
    <?php include("./include/menu.php") ?>
    <main>
        <?php if (empty($_GET["album"])) : ?>
            <h1>Wybierz album:</h1>
            <section class="album-form">
                <?php
                if ($albums->num_rows == 0){
                    header('Location: add-album.php');
                    exit;
                }              
                elseif ($albums->num_rows == 1) {
                    $album = $albums->fetch_assoc();
                    header('Location: add-photo.php?album=' . $album['id']);
                    exit;
                } else while ($album = $albums->fetch_assoc()) : ?>

                    <a href="?album=<?= $album["id"] ?>"><?= $album["title"] ?></a>
                <?php endwhile; ?>
            </section>
        <?php else : ?>
            <?php
            $albumId = $_GET["album"];

            $stmt = $mysqli->prepare(
                "SELECT albums.id,albums.title
                    FROM albums
                    WHERE albums.authorId=? AND albums.id=?"
            );
            $stmt->bind_param("ii", $_SESSION["loggedUser"]["id"], $albumId);
            $stmt->execute();
            $albumsOfId = $stmt->get_result();
            if ($albumsOfId->num_rows != 1) {
                header('Location: add-photo.php');
                exit;
            }
            ?>
            <h1>Dodaj zdjęcie do albumu <?= $albumsOfId->fetch_assoc()["title"] ?></h1>
            <section class="photo-form">
                <form action="./files/add-photo.php" method="post" enctype="multipart/form-data">
                    <div>
                        <span class="name">Plik</span>
                        <input type="file" name="photo" id="photo" accept="image/*" required>
                    </div>
                    <div>
                        <span class="name">Opis</span>
                        <textarea name="description" id="descrtipion" maxlength="255"></textarea>
                    </div>
                    <input type="hidden" name="album" value="<?= $albumId ?>">
                    <button type="submit">Dodaj zdjęcie</button>
                </form>
                <div class="errors">
                    <?php
                    $errors = $_SESSION["photo_errors"] ?? [];
                    if (is_array($errors)) foreach ($errors as $error) : ?>
                        <div><?= $error ?></div>
                    <?php endforeach; ?>
                </div>
            </section>
            <section class="gallery">
                <?php
                $imagesStmt = $mysqli->prepare(
                    "SELECT photos.id
                    FROM photos
                    WHERE albumId=?
                    ORDER BY photos.createdAt DESC"
                );
                $imagesStmt->bind_param("i", $albumId);
                $imagesStmt->execute();
                $images = $imagesStmt->get_result();
                while ($image = $images->fetch_assoc()) :
                ?>
                    <div class="image-wrapper">
                        <img src="<?= "./photo/{$albumId}/{$image["id"]}.min.webp" ?>" alt="">
                    </div>
                <?php endwhile; ?>
            </section>
        <?php endif; ?>
    </main>
    <?php include "./include/footer.php" ?>
</body>

</html>

<?php
$_SESSION["photo_errors"] = array();
