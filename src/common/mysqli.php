<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$dbHost = (getenv("DATABASE_HOST") ?? "wolinski_4a");
$dbUser = (getenv("DATABASE_USER") ?? "root");
$dbPass = (getenv("DATABASE_PASSWORD") ?? "");
$dbName = (getenv("DATABASE_DB") ?? "");

$mysqli = new mysqli(
    $dbHost,
    $dbUser,
    $dbPass,
    $dbName
);
