<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = $1 AND password = $2";
    $result = pg_query_params($conn, $query, array($username, $password));

    if ($row = pg_fetch_assoc($result)) {
        if ($row['username'] === 'admin') {
            header("Location: index.php");
            exit();
        } else {
            
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Login Successful</title>
                <link rel="stylesheet" href="login.css">
            </head>
            <body>
                <div class="form-container">
                    <h2 style="color: #11998e;">✅ Login Successful</h2>
                    <p>Welcome, ' . htmlspecialchars($row['username']) . '!</p>
                    <p>No resume found for your account.</p>
                    <p><a href="login.html">Back to Login</a></p>
                </div>
            </body>
            </html>';
        }
    } else {
        
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Account Not Found</title>
            <link rel="stylesheet" href="login.css">
        </head>
        <body>
            <div class="form-container">
                <h2 style="color: #e74c3c;">❌ Account Not Found</h2>
                <p>The username or password you entered is incorrect.</p>
                <p><a href="register.html">Sign up here</a> or <a href="login.html">Try again</a></p>
            </div>
        </body>
        </html>';
    }
}

pg_close($conn);
?>