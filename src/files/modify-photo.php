<?php
session_start();
require_once("../common/mysqli.php");

if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}


if (empty($_POST["description"])) die;
if (empty($_POST["id"])) die;

$desc = $_POST["description"];
$id = $_POST["id"];

$albumsStmt = $mysqli->prepare("SELECT * FROM photos JOIN albums ON photos.albumId = albums.id WHERE authorId=(?) AND photos.id=(?)");
$albumsStmt->bind_param("ii", $_SESSION["loggedUser"]["id"], $id);
$albumsStmt->execute();

$userAlbums = $albumsStmt->get_result();

if ($userAlbums->num_rows != 1) die;

try {
    $modifyStmt = $mysqli->prepare("UPDATE photos SET description=(?) WHERE id=(?)");
    $modifyStmt->bind_param("si", $desc, $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    var_export($error);
    echo $error;
}
