<?php
$mysqli = new mysqli(
    $_ENV["DATABASE_HOST"],
    $_ENV["DATABASE_USER"],
    $_ENV["DATABASE_PASSWORD"],
    $_ENV["DATABASE_DB"]
);

