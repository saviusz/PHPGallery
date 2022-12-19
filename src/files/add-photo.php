<?php
session_start();

if (empty($_SESSION["loggedUser"])) {
    header("Location: ../logreg.php");
    exit();
}

require_once("../common/mysqli.php");

$errors = array();
$props = array();

issetGuard("album", "Unexpected error");

if (!isset($_FILES["photo"])) {
    array_push($errors, "Musisz podać obrazek");
}

redirectIfErrors();

$image = $_FILES["photo"];
$albumId = $_POST["album"];

if (!empty($_POST["desc"])) {
    $desc = trim($_POST["desc"]);
    maxLengthGuard($title, 255, "Opis musi być krótszy niż 255 znaków");

    redirectIfErrors();
}

if (!exif_imagetype($image["tmp_name"])) {
    array_push($errors, "Przesyłany plik musi być grafiką");
}

$desc = $desc ?? "";

try {
    $createStmt = $mysqli->prepare("INSERT INTO photos(description,albumId,createdAt,isAccepted) VALUES (?,?,NOW(),0)");
    $createStmt->bind_param("si", $desc, $albumId);
    $createStmt->execute();
} catch (Throwable $error) {
    array_push($errors, /* "Unknown error" */ $error);
    redirectIfErrors();
}

$path = "../photo/$albumId/$createStmt->insert_id";

move_uploaded_file($image["tmp_name"], $path);

$path = realpath($path);


list($width, $height) = getimagesize($path);

$scaleFactorBig = max(max($width, $height) / 1200, 1);
$scaleFactorMiniature = max(min($width, $height) / 180, 1);


$realImage = match (exif_imagetype($path)) {
    IMAGETYPE_GIF => imagecreatefromgif($path),
    IMAGETYPE_PNG => imagecreatefrompng($path),
    IMAGETYPE_JPEG => imagecreatefromjpeg($path),
    IMAGETYPE_WEBP => imagecreatefromwebp($path)
};
$resizedImage = imagecreatetruecolor($width / $scaleFactorBig, $height /  $scaleFactorBig);
$miniature = imagecreatetruecolor($width / $scaleFactorMiniature, $height / $scaleFactorMiniature);

imagecopyresampled(
    $resizedImage,
    $realImage,
    0,
    0,
    0,
    0,
    $width / $scaleFactorBig,
    $height / $scaleFactorBig,
    $width,
    $height
);
imagecopyresampled(
    $miniature,
    $realImage,
    0,
    0,
    0,
    0,
    $width / $scaleFactorMiniature,
    $height / $scaleFactorMiniature,
    $width,
    $height
);

imagewebp($miniature, "$path.min.webp");
imagewebp($resizedImage, "$path.webp");

header("Location: ../add-photo.php?album=" . $albumId);
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
    $_SESSION["photo_errors"] = $errors ?? [];
    if (sizeof($errors) > 0) {
        header("Location: ../add-photo.php?album=" . $_POST["album"]);
        exit();
    }
}
