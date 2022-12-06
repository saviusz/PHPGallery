<?php
session_start();

if (empty($_SESSION["loggedUser"])) {
    header("Location: ../logreg.php");
    exit();
}

require_once("../common/mysqli.php");

$errors = array();
$props = array();

issetGuard("title", "Podaj nazwę albumu");

redirectIfErrors();

$title = trim($_POST["title"]);

minLengthGuard($title, 1, "Tytuł nie może być pusty");
maxLengthGuard($title, 100, "Tytuł musi być krótszy niż 100 znaków");

redirectIfErrors();

try {
    $createStmt = $mysqli->prepare("INSERT INTO albums(title,createdAt,authorId) VALUES (?,NOW(),?)");
    $createStmt->bind_param("si", $title, $_SESSION["loggedUser"]["id"]);
    $createStmt->execute();
} catch (Throwable $error) {
    array_push($errors, "Unknown error");
    redirectIfErrors();
}

mkdir("../photo/$createStmt->insert_id");

header("Location: ../add-photo.php");
exit();

// Functions


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
    $_SESSION["album_errors"] = $errors ?? [];
    if (sizeof($errors) > 0) {
        header("Location: ../add-album.php");
        exit();
    }
}