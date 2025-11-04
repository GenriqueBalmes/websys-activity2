<?php
include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password";
    } else {
        $password_md5 = md5($password);
        $query = "SELECT * FROM users WHERE username = $1 AND password_hash = $2";
        $result = pg_query_params($conn, $query, array($username, $password_md5));

        if ($result && pg_num_rows($result) > 0) {
            $row = pg_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['login_time'] = time();
            
            header("Location: edit_resume.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Resume Builder</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 50%, #2d2d2d 100%);
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
            background: linear-gradient(45deg, #0A4CFF, #3B82F6);
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
            border-color: #0A4CFF;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 2px rgba(10, 76, 255, 0.2);
        }
        
        button {
            width: 100%;
            padding: 15px;
            margin-top: 30px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #0A4CFF, #3B82F6);
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
            box-shadow: 0 10px 25px rgba(10, 76, 255, 0.4);
        }
        
        .error-message {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            color: #ff6b6b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        p {
            margin-top: 25px;
            font-size: 14px;
            color: #b0b0b0;
        }
        
        p a {
            color: #0A4CFF;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        
        p a:hover {
            color: #3B82F6;
            text-decoration: underline;
        }
        
        .public-link {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>🔐 Login to Resume Builder</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                ⚠️ <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <label>👤 Username</label>
            <input type="text" name="username" required value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" placeholder="Enter your username">
            
            <label>🔒 Password</label>
            <input type="password" name="password" required placeholder="Enter your password">
            
            <button type="submit">🚀 Login</button>
        </form>
        
        <p>
            Don't have an account? <a href="register.php">Sign up here</a>
        </p>
        
        <div class="public-link">
            <p>
                <a href="browse_resumes.php">👁️ Browse Public Resumes</a>
            </p>
        </div>
    </div>
</body>
</html>
<?php pg_close($conn); ?>