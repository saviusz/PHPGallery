<?php
session_start();
require_once("../common/mysqli.php");

if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}



if (empty($_POST["id"])) die;
if (!isset($_POST["state"])) die;


$id = $_POST["id"];
$state = $_POST["state"] ?? 0;

if ($_SESSION["loggedUser"]["role"] != "admin" && $_SESSION["loggedUser"]["role"] != "moderator") die;

$albumsStmt = $mysqli->prepare("SELECT * FROM users WHERE users.id=(?)");
$albumsStmt->bind_param("i", $id);
$albumsStmt->execute();

$users = $albumsStmt->get_result();

if ($users->num_rows != 1) die;

try {
    $modifyStmt = $mysqli->prepare("UPDATE users SET active=(?) WHERE id=(?)");
    $modifyStmt->bind_param("ii", $state, $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    var_export($error);
    echo $error;
}
