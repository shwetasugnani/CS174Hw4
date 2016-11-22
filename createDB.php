<?php
function initializeDB()
{
    $config = require("config.php");

    $con = mysqli_connect($config['host'], $config['username'], $config['password']);


    $stmt = mysqli_prepare($con, 'DROP DATABASE IF EXISTS chartData');
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($con,'CREATE DATABASE chartData;' );
    mysqli_stmt_execute($stmt);

    mysqli_select_db($con,"chartData");

    $stmt = mysqli_prepare($con,'DROP TABLE IF EXISTS chartData;');
    mysqli_stmt_execute($stmt);

    $stmt = mysqli_prepare($con,'CREATE TABLE `Data` (

  `hashedData` varchar(5000) NOT NULL,
  `title` varchar(500) NOT NULL,
  `data` varchar(5000) NOT NULL
);');
    mysqli_stmt_execute($stmt);

}


