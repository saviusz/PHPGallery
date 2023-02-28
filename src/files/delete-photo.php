<?php
session_start();
require_once("../common/mysqli.php");


if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if (empty($_POST["id"])) die;

$id = $_POST["id"];

if ($_SESSION["loggedUser"]["role"] == "admin" || $_SESSION["loggedUser"]["role"] == "moderator") {
    $albumsStmt = $mysqli->prepare("SELECT * FROM photos WHERE photos.id=(?)");
    $albumsStmt->bind_param("i", $id);
} else {
    $albumsStmt = $mysqli->prepare("SELECT * FROM photos JOIN albums ON photos.albumId = albums.id WHERE authorId=(?) AND photos.id=(?)");
    $albumsStmt->bind_param("ii", $_SESSION["loggedUser"]["id"], $id);
}
$albumsStmt->execute();

$userPhoto = $albumsStmt->get_result();

if ($userPhoto->num_rows != 1) die;

try {
    $modifyStmt = $mysqli->prepare("DELETE FROM photos WHERE id=(?)");
    $modifyStmt->bind_param("i", $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    echo $error;
}

$dirname = "../photo/" . $userPhoto->fetch_assoc()["albumId"] . "/" . $id;

array_map("unlink", glob("$dirname.*"));
