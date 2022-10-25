<?php
session_start();

require_once("../common/mysqli.php");

$errors = array();
$props = array();

issetGuard("login", "Podaj login");
issetGuard("password", "Podaj hasło");

redirectIfErrors();

$login = $_POST["login"];
$password = $_POST["password"];

$stmt = $mysqli->prepare("SELECT * FROM users WHERE login=(?) AND password=(?)");

$hash = md5($password);

$stmt->bind_param("ss", $login, $hash);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows < 1) {
    array_push($errors, "Niepoprawne dane logowania");
}

if ($result->num_rows > 1) {
    array_push($errors, "Skontaktuj się z administratorem");
}

redirectIfErrors();

$user = $result->fetch_assoc();

if ($user["active"] == false) {
    array_push($errors, "Konto zablokowane");
}
redirectIfErrors();

$_SESSION["loggedUser"] = $user;

header("Location: /");
exit();

function issetGuard($name, $message)
{
    global $errors;
    if (empty($_POST[$name])) {
        array_push($errors, $message);
    }
}

function redirectIfErrors()
{
    global $errors;
    $_SESSION["login_errors"] = $errors ?? [];
    if (sizeof($errors) > 0) {
        header("Location: /logreg.php");
        exit();
    }
}
