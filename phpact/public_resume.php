<?php
include('db_connect.php');

$user_id = $_GET['id'] ?? 1;

// Fetch user data
$query = "SELECT * FROM users WHERE id = $1";
$result = pg_query_params($conn, $query, array($user_id));

if ($result && pg_num_rows($result) > 0) {
    $user_data = pg_fetch_assoc($result);
} else {
    die("Resume not found");
}

$full_name = $user_data['full_name'] ?? 'GENRIQUE SEAN ARKIN D. BALMES';
$title = $user_data['title'] ?? 'Computer Science Student';
$contact_phone = $user_data['contact_phone'] ?? '09081231414';
$contact_email = $user_data['contact_email'] ?? 'balmesgenrique27@gmail.com';
$contact_address = $user_data['contact_address'] ?? 'Sampaguita Street, Calero, Batangas City, Batangas';
$skills = explode(',', $user_data['skills'] ?? 'C++, Java, Python, C, HTML, CSS, JavaScript, MySQL');
$organizations = explode("\n", $user_data['organizations'] ?? "Junior Philippine Computer Society (JPCS) – Member\nAssociation of Committed Computer Science Students (ACCESS) – Member");
$profile_summary = $user_data['profile_summary'] ?? 'Aspiring Computer Science professional with an interest in web technologies, data processing, and automation. Eager to apply technical skills and creativity to solve real-world problems and contribute to technological advancement.';
$education_lines = explode("\n", $user_data['education'] ?? "2023 – 2027\nBachelor of Science in Computer Science\nBatangas State University");
$project_lines = explode("\n", $user_data['projects'] ?? "2024\nWeb Development Projects\nStudent Developer\n• Developed responsive web applications using HTML, CSS, and JavaScript\n• Created database-driven applications with PHP and MySQL\n• Implemented user authentication and session management systems");
$interests = explode(',', $user_data['interests'] ?? 'Web Development, Database Management, Software Engineering, Data Structures, Algorithms, UI/UX Design');
$profile_image = $user_data['profile_image'] ?? '';
$username = $user_data['username'] ?? '';

function getInitials($name) {
    $words = explode(' ', $name);
    if (count($words) >= 2) {
        return strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1));
    }
    return strtoupper(substr($name, 0, 2));
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Resume - <?php echo htmlspecialchars($full_name); ?></title>
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
            line-height: 1.6;
        }
        
        .public-header {
            background: linear-gradient(45deg, #3B82F6, #60A5FA);
            color: white;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }
        
        .public-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        
        .public-actions {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 25px;
            border: 2px solid;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .btn-edit {
            background: #3B82F6;
            border-color: #3B82F6;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .resume-container {
            max-width: 1200px;
            margin: 30px auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            display: flex;
            overflow: hidden;
        }
        
        .left-column {
            flex: 1;
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            color: white;
            padding: 40px 30px;
        }
        
        .right-column {
            flex: 2;
            padding: 40px 30px;
            background: rgba(255, 255, 255, 0.02);
        }
        
        .profile-pic {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            margin: 0 auto 30px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            background: rgba(255, 255, 255, 0.1);
            font-size: 64px;
            font-weight: 700;
            color: white;
        }
        
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .name-title {
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        
        .name-title h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: white;
        }
        
        .name-title h2 {
            margin: 10px 0 0 0;
            font-size: 18px;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.9);
            font-style: italic;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .right-column .section h3 {
            color: #3B82F6;
            border-bottom: 2px solid rgba(59, 130, 246, 0.3);
        }
        
        .contact-info {
            font-size: 14px;
            line-height: 1.8;
        }
        
        .contact-info p {
            margin: 12px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .experience-item, .education-item {
            margin-bottom: 25px;
        }
        
        .year {
            color: #ffd700;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 8px;
        }
        
        .right-column .year {
            color: #3B82F6;
        }
        
        .company, .degree {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 17px;
            color: white;
        }
        
        .right-column .company, .right-column .degree {
            color: #e0e0e0;
        }
        
        .position {
            color: rgba(255, 255, 255, 0.8);
            font-style: italic;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .right-column .position {
            color: #b0b0b0;
        }
        
        ul {
            padding-left: 20px;
            margin: 12px 0;
        }
        
        li {
            margin-bottom: 8px;
            font-size: 15px;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .right-column li {
            color: #e0e0e0;
        }
        
        .skills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        .skill-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 12px;
            border-radius: 8px;
            font-size: 14px;
            text-align: center;
            color: white;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .skill-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .right-column .skill-item {
            background: rgba(59, 130, 246, 0.1);
            color: #e0e0e0;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }
        
        .right-column .skill-item:hover {
            background: rgba(59, 130, 246, 0.2);
        }
        
        .divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.2);
            margin: 25px 0;
        }
        
        .right-column .divider {
            background: rgba(59, 130, 246, 0.3);
        }
        
        p {
            font-size: 15px;
            line-height: 1.7;
        }
        
        .left-column p {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .right-column p {
            color: #e0e0e0;
        }
        
        .org-item {
            margin-bottom: 12px;
            padding-left: 15px;
            position: relative;
            font-size: 14px;
        }
        
        .org-item:before {
            content: "•";
            color: #ffd700;
            position: absolute;
            left: 0;
            font-size: 18px;
        }
        
        @media (max-width: 768px) {
            .resume-container {
                flex-direction: column;
                margin: 15px;
            }
            
            .skills-grid {
                grid-template-columns: 1fr;
            }
            
            .public-header h1 {
                font-size: 22px;
            }
            
            .public-actions {
                flex-direction: column;
                align-items: center;
            }
            
            .action-btn {
                width: 200px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="public-header">
        <h1>📄 Digital Resume - <?php echo htmlspecialchars($full_name); ?></h1>
    </div>
    
    <div class="public-actions">
        <a href="login.php" class="action-btn btn-edit">
            ✏️ Edit Your Resume
        </a>
        <a href="browse_resumes.php" class="action-btn btn-edit">
            👁️ Browse All Resumes
        </a>
    </div>
    
    <div class="resume-container">
        <div class="left-column">
            <div class="profile-pic">
                <?php 
                if (!empty($profile_image) && file_exists($profile_image)): 
                ?>
                    <img src="<?php echo htmlspecialchars($profile_image); ?>" alt="Profile Picture">
                <?php 
                elseif ($username === 'admin' && file_exists('2x2.jpeg')): 
                ?>
                    <img src="2x2.jpeg" alt="Profile Picture">
                <?php 
                else: 
                ?>
                    <?php echo getInitials($full_name); ?>
                <?php endif; ?>
            </div>
            
            <div class="name-title">
                <h1><?php echo htmlspecialchars($full_name); ?></h1>
                <h2><?php echo htmlspecialchars($title); ?></h2>
            </div>
            
            <div class="section">
                <h3>📞 Contact</h3>
                <div class="contact-info">
                    <p>📞 <?php echo htmlspecialchars($contact_phone); ?></p>
                    <p>✉️ <?php echo htmlspecialchars($contact_email); ?></p>
                    <p>📍 <?php echo nl2br(htmlspecialchars($contact_address)); ?></p>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <div class="section">
                <h3>💼 Skillsets</h3>
                <div class="skills-grid">
                    <?php foreach ($skills as $skill): ?>
                        <?php if (trim($skill)): ?>
                            <div class="skill-item"><?php echo htmlspecialchars(trim($skill)); ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <div class="section">
                <h3>🏢 Organizations</h3>
                <?php foreach ($organizations as $org): ?>
                    <?php if (trim($org)): ?>
                        <div class="org-item"><?php echo htmlspecialchars(trim($org)); ?></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="right-column">
            <div class="section">
                <h3>👤 Profile</h3>
                <p><?php echo nl2br(htmlspecialchars($profile_summary)); ?></p>
            </div>
            
            <div class="divider"></div>
            
            <div class="section">
                <h3>🎓 Education</h3>
                <div class="education-item">
                    <?php if (count($education_lines) >= 3): ?>
                        <div class="year"><?php echo htmlspecialchars(trim($education_lines[0])); ?></div>
                        <div class="degree"><?php echo htmlspecialchars(trim($education_lines[1])); ?></div>
                        <div class="company"><?php echo htmlspecialchars(trim($education_lines[2])); ?></div>
                    <?php else: ?>
                        <?php foreach ($education_lines as $index => $line): ?>
                            <?php if (trim($line)): ?>
                                <?php if ($index === 0): ?>
                                    <div class="year"><?php echo htmlspecialchars(trim($line)); ?></div>
                                <?php elseif ($index === 1): ?>
                                    <div class="degree"><?php echo htmlspecialchars(trim($line)); ?></div>
                                <?php else: ?>
                                    <div class="company"><?php echo htmlspecialchars(trim($line)); ?></div>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <div class="section">
                <h3>🚀 Academic Projects</h3>
                <div class="experience-item">
                    <?php if (count($project_lines) >= 3): ?>
                        <div class="year"><?php echo htmlspecialchars(trim($project_lines[0])); ?></div>
                        <div class="company"><?php echo htmlspecialchars(trim($project_lines[1])); ?></div>
                        <div class="position"><?php echo htmlspecialchars(trim($project_lines[2])); ?></div>
                        <ul>
                            <?php for ($i = 3; $i < count($project_lines); $i++): ?>
                                <?php if (trim($project_lines[$i])): ?>
                                    <li><?php echo htmlspecialchars(trim($project_lines[$i])); ?></li>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </ul>
                    <?php else: ?>
                        <?php foreach ($project_lines as $index => $line): ?>
                            <?php if (trim($line)): ?>
                                <?php if ($index === 0): ?>
                                    <div class="year"><?php echo htmlspecialchars(trim($line)); ?></div>
                                <?php elseif ($index === 1): ?>
                                    <div class="company"><?php echo htmlspecialchars(trim($line)); ?></div>
                                <?php elseif ($index === 2): ?>
                                    <div class="position"><?php echo htmlspecialchars(trim($line)); ?></div>
                                    <ul>
                                <?php else: ?>
                                    <li><?php echo htmlspecialchars(trim($line)); ?></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="divider"></div>
            
            <div class="section">
                <h3>🎯 Areas of Interest</h3>
                <div class="skills-grid">
                    <?php foreach ($interests as $interest): ?>
                        <?php if (trim($interest)): ?>
                            <div class="skill-item"><?php echo htmlspecialchars(trim($interest)); ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php pg_close($conn); ?>