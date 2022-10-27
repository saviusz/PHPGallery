<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli(
    $_ENV["DATABASE_HOST"],
    $_ENV["DATABASE_USER"],
    $_ENV["DATABASE_PASSWORD"],
    $_ENV["DATABASE_DB"]
);
