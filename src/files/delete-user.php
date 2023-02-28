<?php
session_start();
require_once("../common/mysqli.php");


if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if (empty($_POST["id"])) die;

$id = $_POST["id"];

$selectStmt = $mysqli->prepare("SELECT * FROM users WHERE id=(?)");
$selectStmt->bind_param("i", $id);
$selectStmt->execute();

$users = $selectStmt->get_result();

if ($users->num_rows != 1) die;

$userAlbumsStmt = $mysqli->prepare("SELECT id FROM albums WHERE authorId=(?)");
$userAlbumsStmt->bind_param("i", $id);
$userAlbumsStmt->execute();

$userAlbums = $userAlbumsStmt->get_result();


try {
    $modifyStmt = $mysqli->prepare("DELETE FROM users WHERE id=(?)");
    $modifyStmt->bind_param("i", $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    echo $error;
}


while ($album = $userAlbums->fetch_assoc()) {
    $dirname = "../photo/" . $album["id"];
    array_map("unlink", glob("$dirname/*"));
    array_map("rmdir", glob("$dirname/*"));
    array_map("rmdir", glob("$dirname"));
}
