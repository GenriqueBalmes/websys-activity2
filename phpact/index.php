<?php
echo '<!DOCTYPE html>
<html>
<head>
    <title>Resume - Genrique Sean Arkin D. Balmes</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Garamond, "Times New Roman", Times, serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
            line-height: 1.6;
        }
        .resume-container {
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            display: flex;
        }
        .left-column {
            flex: 1;
            background: #6f2eb9;
            color: white;
            padding: 40px 30px;
        }
        .left-column * {
            color: white;
        }
        .right-column {
            flex: 2;
            padding: 40px 30px;
            color: black;
        }
        .right-column * {
            color: black;
        }
        .name-title {
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .name-title h1 {
            margin: 0;
            font-size: 32px; /* Increased from 28px */
            font-weight: 600;
            font-family: Garamond, "Times New Roman", Times, serif;
            color: white;
        }
        .name-title h2 {
            margin: 5px 0 0 0;
            font-size: 20px; /* Increased from 18px */
            font-weight: 400;
            color: rgba(255,255,255,0.9);
            font-family: Garamond, "Times New Roman", Times, serif;
        }
        .profile-pic {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            margin: 0 auto 30px auto;
            display: block;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h3 {
            font-size: 20px; /* Increased from 18px */
            font-weight: 600;
            margin-bottom: 15px;
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-bottom: 8px;
            font-family: Garamond, "Times New Roman", Times, serif;
        }
        .right-column .section h3 {
            color: #6f2eb9;
            border-bottom: 1px solid #e0e0e0;
            font-family: Garamond, "Times New Roman", Times, serif;
        }
        .contact-info {
            font-size: 16px; /* Increased from 14px */
            line-height: 1.8;
            font-family: Garamond, "Times New Roman", Times, serif;
            color: white;
        }
        .contact-info p {
            margin: 8px 0;
            font-family: Garamond, "Times New Roman", Times, serif;
            color: white;
        }
        .experience-item, .education-item {
            margin-bottom: 25px;
            font-family: Garamond, "Times New Roman", Times, serif;
            color: black;
        }
        .year {
            color: #6f2eb9;
            font-weight: 600;
            font-size: 16px; /* Increased from 14px */
            margin-bottom: 5px;
            font-family: Garamond, "Times New Roman", Times, serif;
        }
        .company, .degree {
            font-weight: 600;
            margin-bottom: 5px;
            font-size: 17px; /* Added larger size */
            font-family: Garamond, "Times New Roman", Times, serif;
            color: black;
        }
        .position {
            color: #666;
            font-style: italic;
            margin-bottom: 8px;
            font-size: 16px; /* Added larger size */
            font-family: Garamond, "Times New Roman", Times, serif;
        }
        ul {
            padding-left: 20px;
            margin: 8px 0;
            font-family: Garamond, "Times New Roman", Times, serif;
            color: black;
        }
        li {
            margin-bottom: 4px;
            font-size: 16px; /* Increased from 14px */
            font-family: Garamond, "Times New Roman", Times, serif;
            color: black;
        }
        .skills-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .skill-item {
            background: rgba(255,255,255,0.1);
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 16px; /* Increased from 14px */
            font-family: Garamond, "Times New Roman", Times, serif;
            color: white;
        }
        .right-column .skill-item {
            background: #f0f0f0;
            color: black;
            font-family: Garamond, "Times New Roman", Times, serif;
        }
        .divider {
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 25px 0;
        }
        .right-column .divider {
            background: #e0e0e0;
        }
        p {
            font-family: Garamond, "Times New Roman", Times, serif;
            font-size: 16px; /* Added larger size */
        }
        .left-column p {
            color: white;
            font-size: 16px; /* Added larger size */
        }
        .right-column p {
            color: black;
            font-size: 16px; /* Added larger size */
        }
    </style>
</head>
<body>
<div class="resume-container">';

echo '<div class="left-column">';
    
    echo '<img src="2x2.jpeg" alt="Profile Picture" class="profile-pic">';
    
    echo '<div class="name-title">';
    echo '<h1>GENRIQUE SEAN ARKIN D. BALMES</h1>';
    echo '<h2>Computer Science Student</h2>';
    echo '</div>';
    
    echo '<div class="section">';
    echo '<h3>Contact</h3>';
    echo '<div class="contact-info">';
    echo '<p>📞 09081231414</p>';
    echo '<p>✉️ balmesgenrique27@gmail.com</p>';
    echo '<p>📍 Sampaguita Street, Calero,<br>Batangas City, Batangas</p>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="divider"></div>';
    
    echo '<div class="section">';
    echo '<h3>Skillsets</h3>';
    echo '<div class="skills-grid">';
    $skills = ["C++", "Java", "Python", "C", "HTML", "CSS", "JavaScript", "MySQL"];
    foreach ($skills as $s) {
        echo '<div class="skill-item">' . $s . '</div>';
    }
    echo '</div>';
    echo '</div>';
    
    echo '<div class="divider"></div>';
    
    echo '<div class="section">';
    echo '<h3>Organizations</h3>';
    $orgs = [
        "Junior Philippine Computer Society (JPCS) – Member",
        "Association of Committed Computer Science Students (ACCESS) – Member"
    ];
    foreach ($orgs as $o) {
        echo '<p style="font-size: 16px; margin-bottom: 10px; color: white;">• ' . $o . '</p>';
    }
    echo '</div>';

echo '</div>'; 

echo '<div class="right-column">';
    
    echo '<div class="section">';
    echo '<h3>Profile</h3>';
    echo '<p>Aspiring Computer Science professional with an interest in web technologies, data processing, and automation. Eager to apply technical skills and creativity to solve real-world problems and contribute to technological advancement.</p>';
    echo '</div>';
    
    echo '<div class="divider"></div>';
    
    echo '<div class="section">';
    echo '<h3>Education</h3>';
    echo '<div class="education-item">';
    echo '<div class="year">2023 – 2027</div>';
    echo '<div class="degree">Bachelor of Science in Computer Science</div>';
    echo '<div class="company">Batangas State University</div>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="divider"></div>';
    
    echo '<div class="section">';
    echo '<h3>Academic Projects</h3>';
    echo '<div class="experience-item">';
    echo '<div class="year">2024</div>';
    echo '<div class="company">Web Development Projects</div>';
    echo '<div class="position">Student Developer</div>';
    echo '<ul>';
    echo '<li>Developed responsive web applications using HTML, CSS, and JavaScript</li>';
    echo '<li>Created database-driven applications with PHP and MySQL</li>';
    echo '<li>Implemented user authentication and session management systems</li>';
    echo '</ul>';
    echo '</div>';
    echo '</div>';
    
    echo '<div class="divider"></div>';
    
   
    echo '<div class="section">';
    echo '<h3>Areas of Interest</h3>';
    echo '<div class="skills-grid">';
    $interests = ["Web Development", "Database Management", "Software Engineering", "Data Structures", "Algorithms", "UI/UX Design"];
    foreach ($interests as $interest) {
        echo '<div class="skill-item">' . $interest . '</div>';
    }
    echo '</div>';
    echo '</div>';

echo '</div>'; 

echo '</div></body></html>';
?>