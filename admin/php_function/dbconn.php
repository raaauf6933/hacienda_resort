<?php

$dbUsername = "root";
$dbPassword = "";
$dbServername = "localhost";
$dbName = "hacienda_db";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName) or die("connection failed");
