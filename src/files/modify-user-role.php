<?php
session_start();
require_once("../common/mysqli.php");


if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

if (empty($_POST["id"])) die;
if (empty($_POST["role"])) die;

$role = $_POST["role"];
$id = $_POST["id"];


$selectStmt = $mysqli->prepare("SELECT * FROM users WHERE id=(?)");
$selectStmt->bind_param("i", $id);
$selectStmt->execute();

$users = $selectStmt->get_result();

if ($users->num_rows != 1) {
    echo $users->num_rows;
    die;
}

try {
    $modifyStmt = $mysqli->prepare("UPDATE users SET role=(?) WHERE id=(?)");
    $modifyStmt->bind_param("si", $role, $id);
    $modifyStmt->execute();
} catch (Throwable $error) {
    die;
}
