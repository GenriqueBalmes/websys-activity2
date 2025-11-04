<?php
session_start();

$host = "localhost";
$port = "5432";
$dbname = "mydb";
$user = "postgres";
$password = "database";

$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Connection failed: " . pg_last_error());
}

if (isset($_SESSION['user_id'])) {
    $check_user = pg_query_params($conn, "SELECT id FROM users WHERE id = $1", array($_SESSION['user_id']));
    if (!$check_user || pg_num_rows($check_user) === 0) {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
?>