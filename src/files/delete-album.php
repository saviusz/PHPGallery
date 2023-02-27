<?php
session_start();
require_once("../common/mysqli.php");


if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if (empty($_POST["id"])) die;

$id = $_POST["id"];

$albumsStmt = $mysqli->prepare("SELECT * FROM albums WHERE authorId=(?) AND id=(?)");
$albumsStmt->bind_param("ii", $_SESSION["loggedUser"]["id"], $id);
$albumsStmt->execute();

$userAlbums = $albumsStmt->get_result();

if ($userAlbums->num_rows != 1) die;

try {
    $modifyStmt = $mysqli->prepare("DELETE FROM albums WHERE id=(?)");
    $modifyStmt->bind_param("i", $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    echo $error;
}

$dirname = "../photo/" . $id;

array_map("unlink", glob("$dirname/*"));
array_map("rmdir", glob("$dirname/*"));
array_map("rmdir", glob("$dirname"));
