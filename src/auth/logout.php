<?php
session_start();
setcookie(session_name(), '', 100);
session_unset();
session_destroy();
$_SESSION = array();

$location = $_SERVER['HTTP_REFERER'];
header("Location: " . $location);
