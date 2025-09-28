<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $check = pg_query_params($conn, "SELECT * FROM users WHERE username = $1", array($username));

    if (pg_num_rows($check) > 0) {
        
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Username Taken</title>
            <link rel="stylesheet" href="register.css">
        </head>
        <body>
            <div class="form-container">
                <h2 style="color: #e74c3c;">⚠️ Username Taken</h2>
                <p>The username "' . htmlspecialchars($username) . '" is already taken.</p>
                <p>Please choose a different username.</p>
                <p><a href="register.html">Try again</a> or <a href="login.html">Go back to Login</a></p>
            </div>
        </body>
        </html>';
    } else {
        $result = pg_query_params($conn, 
            "INSERT INTO users (username, password) VALUES ($1, $2)", 
            array($username, $password)
        );

        if ($result) {
            
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Registration Successful</title>
                <link rel="stylesheet" href="register.css">
            </head>
            <body>
                <div class="form-container">
                    <h2 style="color: #11998e;">🎉 Registration Successful!</h2>
                    <p>Your account has been created successfully.</p>
                    <p><a href="login.html">Click here to login</a></p>
                </div>
            </body>
            </html>';
        } else {
            echo "Error: " . pg_last_error($conn);
        }
    }
}
?>