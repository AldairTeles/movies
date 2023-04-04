<?php

$db = "moviestar";
$host = "localhost";
$user = "root";
$pass = "root";

$conn = new PDO("mysql:dbname=$db;host=$host;", $user, $pass);

// HABILITAR ERRO

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);