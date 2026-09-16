<?php

$serverName = "localhost";
$dbUsername = "AdminMyShiki";
$dbPassword = $_SERVER['DB_PASSWORD'];
$dbName = "MyShiki";

$conn = mysqli_connect($serverName, $dbUsername, $dbPassword, $dbName);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}