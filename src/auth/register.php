<?php
session_start();

require_once("../common/mysqli.php");

$errors = array();
$props = array();

issetGuard("login", "Podaj login");
issetGuard("password", "Podaj hasło");
issetGuard("rpassword", "Powtórz hasło");
issetGuard("email", "Podaj email");

redirectIfErrors();

$login = $_POST["login"];
$password = $_POST["password"];
$rpassword = $_POST["rpassword"];
$email = $_POST["email"];

minLengthGuard($login, 8, "Login musi być dłużyszy niż 8 znaków");
maxLengthGuard($login, 16, "Login musi być dkrótszy niż 16 znaków");

minLengthGuard($password, 8, "Hasło musi mieć min. 8 znaków");
maxLengthGuard($password, 20, "Hasło może mieć max. 20 znakow");

RegexGuard($password, '@[A-Z]@', "Hasło powinno zawierać min. 1 dużą literę");
RegexGuard($password, '@[a-z]@', "Hasło powinno zawierać min. 1 małą literę");
RegexGuard($password, '@[0-9]@', "Hasło powinno zawierać min. 1 cyfrę");

if ($password != $rpassword) {
    array_push($errors, "Hasła są różne");
}

redirectIfErrors();

$stmt = $mysqli->prepare("SELECT * FROM users WHERE login=(?)");

$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    array_push($errors, "Login już istnieje");
}

redirectIfErrors();

try {
    $hash = md5($password);

    $createStmt = $mysqli->prepare("INSERT INTO users(login, password, email, registered_at) VALUES (?,?,?,NOW())");
    $createStmt->bind_param("sss", $login, $hash, $email);
    $createStmt->execute();
} catch (Throwable $error) {
    array_push($errors, "Unknown error");
    redirectIfErrors();
}

$stmt->execute();

$_SESSION["loggedUser"] = $stmt->get_result()->fetch_assoc();

header("Location: /auth/register-ok.php");
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

function redirectIfErrors()
{
    global $errors;
    $_SESSION["reg_errors"] = $errors ?? [];
    if (sizeof($errors) > 0) {
        header("Location: /logreg.php");
        exit();
    }
}
