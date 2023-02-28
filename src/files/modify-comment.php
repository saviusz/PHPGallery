<?php
session_start();
require_once("../common/mysqli.php");


if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if ($_SESSION["loggedUser"]["role"] != "admin") die;

if (empty($_POST["content"])) die;
if (empty($_POST["id"])) die;

$content = $_POST["content"];
$id = $_POST["id"];

$albumsStmt = $mysqli->prepare("SELECT * FROM photos_comments WHERE id=(?)");
$albumsStmt->bind_param("i", $id);
$albumsStmt->execute();

$userAlbums = $albumsStmt->get_result();

if ($userAlbums->num_rows != 1) {
    echo $userAlbums->num_rows;
    die;
}

try {
    $modifyStmt = $mysqli->prepare("UPDATE photos_comments SET content=(?) WHERE id=(?)");
    $modifyStmt->bind_param("si", $content, $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    die;
}
