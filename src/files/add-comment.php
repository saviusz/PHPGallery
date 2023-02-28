<?php
session_start();

if (empty($_SESSION["loggedUser"])) {
    header("Location: ../logreg.php");
    exit();
}

var_dump($_POST["id"]);

require_once("../common/mysqli.php");

$errors = array();
$props = array();

issetGuard("id", "Podaj id");
issetGuard("comment", "Podaj komentarz");

redirectIfErrors();

$id = $_POST["id"];
$comment = $_POST["comment"];

try {
    $createStmt = $mysqli->prepare("INSERT INTO photos_comments(photoId,authorId,createdAt,content,isAccepted) VALUES (?,?,NOW(),?,0)");
    $createStmt->bind_param("iis", $id, $_SESSION["loggedUser"]["id"], $comment);
    $createStmt->execute();
} catch (Throwable $error) {
    array_push($errors, "Unknown error");
    redirectIfErrors();
}

header("Location: ../photo.php?id=$id");
exit();

function issetGuard($name, $message)
{
    global $errors;
    if (empty($_POST[$name])) {
        array_push($errors, $message);
    }
}

function minLengthGuard($var, $min, $message)
{
    global $errors;
    if (strlen($var) < $min) array_push($errors, $message);
}

function maxLengthGuard($var, $max, $message)
{
    global $errors;
    if (strlen($var) > $max) array_push($errors, $message);
}

function redirectIfErrors()
{
    global $errors;
    $_SESSION["comment_errors"] = $errors ?? [];
    if (sizeof($errors) > 0) {
        if (empty($_POST["id"])) {
            header("Location: ../index.php");
            exit();
        }
        header("Location: ../photo.php?id=" . $_POST["id"]);
        exit();
    }
}
