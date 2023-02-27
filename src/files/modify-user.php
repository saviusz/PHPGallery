<?php
session_start();

if (empty($_SESSION["loggedUser"])) {
    header("Location: ./logreg.php");
    exit();
}

require_once("../common/mysqli.php");

$errors = array();
$props = array();

issetGuard("password", "Podaj hasło");
redirectIfErrors();

matchGuard(md5($_POST["password"]), $_SESSION["loggedUser"]["password"], "Podane hasło nie zgadza się");
redirectIfErrors();

if (!empty($_POST["n_password"])) {
    issetGuard("nr_password", "Powtórz nowe hasło");

    redirectIfErrors();

    $password = $_POST["n_password"];
    $rpassword = $_POST["nr_password"];

    minLengthGuard($password, 8, "Hasło musi mieć min. 8 znaków");
    maxLengthGuard($password, 20, "Hasło może mieć max. 20 znakow");

    RegexGuard($password, '@[A-Z]@', "Hasło powinno zawierać min. 1 dużą literę");
    RegexGuard($password, '@[a-z]@', "Hasło powinno zawierać min. 1 małą literę");
    RegexGuard($password, '@[0-9]@', "Hasło powinno zawierać min. 1 cyfrę");

    if ($password != $rpassword) {
        array_push($errors, "Hasła są różne");
    }

    redirectIfErrors();

    try {
        $hash = md5($password);

        $modifyStmt = $mysqli->prepare("UPDATE users SET password=(?) WHERE id=(?)");
        $modifyStmt->bind_param("si", $hash, $_SESSION["loggedUser"]["id"]);
        $modifyStmt->execute();
    } catch (Throwable $error) {
        array_push($errors, "Unknown error");
        redirectIfErrors();
    }
}

if (!empty($_POST["email"])) {
    $email = $_POST["email"];

    try {
        $modifyStmt = $mysqli->prepare("UPDATE users SET email=(?) WHERE id=(?)");
        $modifyStmt->bind_param("si", $email, $_SESSION["loggedUser"]["id"]);
        $modifyStmt->execute();
    } catch (Throwable $error) {
        array_push($errors, "Unknown error");
        redirectIfErrors();
    }
}

redirectIfErrors();

$stmt = $mysqli->prepare("SELECT * FROM users WHERE id=(?)");
$stmt->bind_param("i", $_SESSION["loggedUser"]["id"]);
$stmt->execute();

$_SESSION["loggedUser"] = $stmt->get_result()->fetch_assoc();

header("Location: ../account.php");
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

function RegexGuard($var, $regex, $message)
{
    global $errors;
    if (checkRegex($var, $regex)) array_push($errors, $message);
}

function checkRegex($var, $regex)
{
    $matches = preg_match($regex, $var);
    return empty($matches);
}

function matchGuard($var1, $var2, $message)
{
    global $errors;
    if ($var1 != $var2) array_push($errors, $message);
}

function redirectIfErrors()
{
    global $errors;
    $_SESSION["user_mod_errors"] = $errors ?? [];
    if (sizeof($errors) > 0) {
        header("Location: ../account.php");
        exit();
    }
}
