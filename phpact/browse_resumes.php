<?php
include('db_connect.php');

$query = "SELECT id, username, full_name, title, contact_address, profile_image 
          FROM users 
          WHERE full_name IS NOT NULL 
          ORDER BY created_at DESC";
$result = pg_query($conn, $query);

function getInitials($name) {
    $words = explode(' ', $name);
    if (count($words) >= 2) {
        return strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1));
    }
    return strtoupper(substr($name, 0, 2));
}

function getLocation($address) {
    if (empty($address)) return "Location not set";
    $parts = array_map('trim', explode(',', $address));
    if (count($parts) >= 2) {
        return $parts[count($parts) - 2] . ', ' . $parts[count($parts) - 1];
    }
    return $parts[0];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Resumes - Portfolio Hub</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            color: #e0e0e0;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #3B82F6;
            text-decoration: none;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
        }
        
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-size: 14px;
            display: inline-block;
        }
        
        .btn-primary {
            background: #3B82F6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563EB;
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: transparent;
            color: #e0e0e0;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-secondary:hover {
            border-color: #3B82F6;
            color: #3B82F6;
        }
        
        .hero {
            text-align: center;
            padding: 80px 20px 60px 20px;
            max-width: 900px;
            margin: 0 auto;
        }
        
        .hero h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #3B82F6, #60A5FA);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero p {
            font-size: 18px;
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 40px;
        }
        
        .hero-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn-large {
            padding: 15px 40px;
            font-size: 16px;
        }
        
        .resumes-section {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px 80px 20px;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 50px;
        }
        
        .section-header h2 {
            font-size: 36px;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }
        
        .section-underline {
            width: 80px;
            height: 4px;
            background: linear-gradient(45deg, #3B82F6, #60A5FA);
            margin: 0 auto;
            border-radius: 2px;
        }
        
        .resume-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .resume-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .resume-card:hover {
            transform: translateY(-10px);
            border-color: #3B82F6;
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.3);
            background: rgba(255, 255, 255, 0.08);
        }
        
        .profile-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 25px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            border: 4px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        
        .profile-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .card-name {
            font-size: 24px;
            font-weight: 700;
            color: white;
            margin-bottom: 8px;
        }
        
        .card-location {
            font-size: 14px;
            color: #b0b0b0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .card-bio {
            font-size: 15px;
            color: #e0e0e0;
            line-height: 1.5;
            margin-bottom: 20px;
            min-height: 45px;
        }
        
        .card-username {
            display: inline-block;
            background: rgba(59, 130, 246, 0.2);
            color: #60A5FA;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
        
        .no-resumes {
            text-align: center;
            padding: 60px 20px;
            color: #b0b0b0;
            font-size: 18px;
        }
        
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 36px;
            }
            
            .hero p {
                font-size: 16px;
            }
            
            .header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }
            
            .resume-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php" class="logo">📄 Resume Builder</a>
        <div class="header-actions">
            <a href="login.php" class="btn btn-primary">Login</a>
            <a href="register.php" class="btn btn-secondary">Register</a>
        </div>
    </div>
    
    <div class="hero">
        <h1>Build your Resume</h1>
        <p>Build. Showcase. Get Hired. Your career starts with a standout resume.</p>
        <div class="hero-actions">
            <a href="register.php" class="btn btn-primary btn-large">Create Your Resume</a>
            <a href="#resumes" class="btn btn-secondary btn-large">Browse Resumes</a>
        </div>
    </div>
    
    <div class="resumes-section" id="resumes">
        <div class="section-header">
            <h2>Available Resumes</h2>
            <div class="section-underline"></div>
        </div>
        
        <div class="resume-grid">
            <?php if ($result && pg_num_rows($result) > 0): ?>
                <?php while ($user = pg_fetch_assoc($result)): ?>
                    <a href="public_resume.php?id=<?php echo $user['id']; ?>" class="resume-card">
                        <div class="profile-circle">
                            <?php if (!empty($user['profile_image'])): ?>
                                <img src="<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile">
                            <?php else: ?>
                                <?php echo getInitials($user['full_name']); ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-name"><?php echo htmlspecialchars($user['full_name']); ?></div>
                        
                        <div class="card-location">
                            📍 <?php echo htmlspecialchars(getLocation($user['contact_address'])); ?>
                        </div>
                        
                        <div class="card-bio">
                            <?php 
                            $bio = !empty($user['title']) ? $user['title'] : 'No bio available';
                            echo htmlspecialchars($bio); 
                            ?>
                        </div>
                        
                        <div class="card-username"><?php echo htmlspecialchars($user['username']); ?></div>
                    </a>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-resumes">
                    <p>No resumes available yet. Be the first to create one!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php pg_close($conn); ?>