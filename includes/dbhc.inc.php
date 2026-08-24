<?php

$serverName = "localhost";
$dbUsername = "AdminMyShiki";
$dbPassword = $_SERVER['DB_PASSWORD'];
$dbName = "Catalogos";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (mysqli_connect_errno()) {
    die("Connection failed: " . mysqli_connect_error());

$conn->set_charset("utf8");
}