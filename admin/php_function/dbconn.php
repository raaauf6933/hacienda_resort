<?php

$dbUsername = "hacienda_db";
$dbPassword = "hacienda_db";
$dbServername = "localhost";
$dbName = "hacienda_db";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName) or die("connection failed");
