<?php
include("db_connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $check = pg_query_params($conn, "SELECT * FROM users WHERE username = $1", array($username));

    if (pg_num_rows($check) > 0) {
        $error = "The username '$username' is already taken. Please choose a different username.";
    } else {
        $password_hash = md5($password);
        
        $result = pg_query_params($conn, 
            "INSERT INTO users (username, password_hash, full_name, title) VALUES ($1, $2, $3, $4)", 
            array($username, $password_hash, $username, 'Computer Science Student')
        );

        if ($result) {
            $success = true;
        } else {
            $error = "Error creating account: " . pg_last_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Resume Builder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f1b33 0%, #1a3658 50%, #2d4a76 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
            color: white;
        }
        
        h2 {
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 600;
            background: linear-gradient(45deg, #00c6ff, #0072ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        label {
            display: block;
            text-align: left;
            margin-top: 20px;
            font-weight: 500;
            color: #e0e0e0;
            font-size: 14px;
        }
        
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin-top: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transition: all 0.3s ease;
        }
        
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #0072ff;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 2px rgba(0, 114, 255, 0.2);
        }
        
        button {
            width: 100%;
            padding: 15px;
            margin-top: 30px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #00c6ff, #0072ff);
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 114, 255, 0.4);
        }
        
        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .success {
            background: rgba(40, 167, 69, 0.2);
            border: 1px solid rgba(40, 167, 69, 0.5);
            color: #51cf66;
        }
        
        .error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ff6b6b;
        }
        
        p {
            margin-top: 25px;
            font-size: 14px;
            color: #b0b0b0;
        }
        
        p a {
            color: #00c6ff;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        p a:hover {
            color: #4dcfff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php if (isset($success)): ?>
            <div class="message success">
                🎉 Registration Successful!
            </div>
            <p>Your account has been created successfully.</p>
            <p style="margin-top: 20px;">
                <a href="login.php">➡️ Click here to login</a>
            </p>
        <?php else: ?>
            <h2>🚀 Create Account</h2>
            
            <?php if (isset($error)): ?>
                <div class="message error">
                    ⚠️ <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="register.php" method="POST">
                <label>👤 Username</label>
                <input type="text" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="Choose a username">
                
                <label>🔒 Password</label>
                <input type="password" name="password" required placeholder="Create a password">
                
                <button type="submit">📝 Register</button>
            </form>
            
            <p>
                Already have an account? <a href="login.php">Login here</a>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php pg_close($conn); ?>