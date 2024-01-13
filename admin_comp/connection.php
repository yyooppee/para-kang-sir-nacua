<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "mimis pet shop";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{
    die("Failed to connect!");
}
?>