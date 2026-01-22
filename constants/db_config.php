<?php
$servername = getenv("DB_HOST");
$dbname     = getenv("DB_NAME");
$username   = getenv("DB_USER");
$password   = getenv("DB_PASS");
$conn = new PDO(
    "mysql:host=$servername;dbname=$dbname;charset=utf8",
    $username,
    $password
);
?>