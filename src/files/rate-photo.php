<?php
session_start();


if (empty($_SESSION["loggedUser"])) {
    die("Error");
}

issetGuard("id", "Id cannot be empty");
issetGuard("rating", "Rating cannot be empty");

require_once("../common/mysqli.php");

try {
    $createStmt = $mysqli->prepare(
        "INSERT INTO photos_ratings(photoId,userId,rating)
        VALUES (?,?,?)
        ON DUPLICATE KEY UPDATE
        rating=?"
    );
    $createStmt->bind_param("iiii", $_POST["id"], $_SESSION["loggedUser"]["id"], $_POST["rating"], $_POST["rating"]);
    $createStmt->execute();
} catch (Throwable $error) {
    array_push($errors, "Unknown error");
    redirectIfErrors();
}


function issetGuard($name, $message)
{
    if (empty($_POST[$name])) {
        http_response_code(400);
        die($message);
    }
}
