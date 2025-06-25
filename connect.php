<?php

$servername = "localhost";
$username = "toothEase";
$password = "1234";
$dbname = "student_toothease";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error)
{
    die("Connection failed: ". $conn->connect_error);
}

//echo"Connected successfully";
?>  