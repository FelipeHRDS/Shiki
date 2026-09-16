<?php

$serverName = "localhost";
$dbUsername = "AdminMyShiki";
$dbPassword = $_SERVER['DB_PASSWORD'];
$dbName = "Catalogos";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);
$conn->set_charset("utf8");

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());
}