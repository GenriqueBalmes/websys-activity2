<?php
$host = "localhost";
$port = "5432"; 
$dbname = "mydb"; 
$user = "postgres"; 
$password = "database"; 

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>
