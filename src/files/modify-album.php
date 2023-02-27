<?php
session_start();
require_once("../common/mysqli.php");


if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if (empty($_POST["title"])) die;
if (empty($_POST["id"])) die;

$title = $_POST["title"];
$id = $_POST["id"];
if ($_SESSION["loggedUser"]["role"] == "admin") {
    $albumsStmt = $mysqli->prepare("SELECT * FROM albums WHERE id=(?)");
    $albumsStmt->bind_param("i", $id);
} else {
    $albumsStmt = $mysqli->prepare("SELECT * FROM albums WHERE authorId=(?) AND id=(?)");
    $albumsStmt->bind_param("ii", $_SESSION["loggedUser"]["id"], $id);
}
$albumsStmt->execute();

$userAlbums = $albumsStmt->get_result();

if ($userAlbums->num_rows != 1) {
    echo $userAlbums->num_rows;
    die;
}

try {
    $modifyStmt = $mysqli->prepare("UPDATE albums SET title=(?) WHERE id=(?)");
    $modifyStmt->bind_param("si", $title, $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    die;
}
