<?php
session_start();
require_once("../common/mysqli.php");

if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if (empty($_POST["id"])) die;

$id = $_POST["id"];

if ($_SESSION["loggedUser"]["role"] != "admin" && $_SESSION["loggedUser"]["role"] != "moderator") die;

$albumsStmt = $mysqli->prepare("SELECT * FROM photos_comments WHERE id=(?)");
$albumsStmt->bind_param("i", $id);
$albumsStmt->execute();

$userAlbums = $albumsStmt->get_result();

if ($userAlbums->num_rows != 1) die;

try {
    $modifyStmt = $mysqli->prepare("UPDATE photos_comments SET isAccepted=1 WHERE id=(?)");
    $modifyStmt->bind_param("i", $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    var_export($error);
    echo $error;
}
