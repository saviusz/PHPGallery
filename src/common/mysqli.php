<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = (getenv("DATABASE_HOST") ?? "localhost");
$dbUser = (getenv("DATABASE_USER") ?? "root");
$dbPass = (getenv("DATABASE_PASSWORD") ?? "");
$dbName = (getenv("DATABASE_DB") ?? "wolinski_4a");

$mysqli = new mysqli(
    $dbHost,
    $dbUser,
    $dbPass,
    $dbName
);
