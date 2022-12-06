<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = (getenv("DATABASE_HOST") ?? "localhost");
$dbUser = /*(getenv("DATABASE_USER") ?? "root")*/"root";
$dbPass = (getenv("DATABASE_PASSWORD") ?? "");
$dbName = /*(getenv("DATABASE_DB") ?? "wolinski_4a")*/"wolinski_4a";

$mysqli = new mysqli(
    $dbHost,
    $dbUser,
    $dbPass,
    $dbName
);

$mysqli->set_charset("utf8");
