<?php
include('db_connect.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $1";
$result = pg_query_params($conn, $query, array($user_id));
$user_data = pg_fetch_assoc($result);

$success_message = $error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $profile_image = $user_data['profile_image']; 
    
    if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_photo']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $upload_dir = 'uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $new_filename = 'profile_' . $user_id . '_' . time() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;
            
            if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $upload_path)) {
                if (!empty($user_data['profile_image']) && file_exists($user_data['profile_image'])) {
                    unlink($user_data['profile_image']);
                }
                $profile_image = $upload_path;
            } else {
                $error_message = "Failed to upload photo";
            }
        } else {
            $error_message = "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
        }
    }
    
    if (empty($error_message)) {
        $full_name = trim($_POST['full_name'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $contact_phone = trim($_POST['contact_phone'] ?? '');
        $contact_email = trim($_POST['contact_email'] ?? '');
        $contact_address = trim($_POST['contact_address'] ?? '');
        $skills = trim($_POST['skills'] ?? '');
        $organizations = trim($_POST['organizations'] ?? '');
        $profile_summary = trim($_POST['profile_summary'] ?? '');
        $education = trim($_POST['education'] ?? '');
        $projects = trim($_POST['projects'] ?? '');
        $interests = trim($_POST['interests'] ?? '');
        
        if (empty($full_name)) {
            $error_message = "Full name is required";
        } elseif (empty($contact_email) || !filter_var($contact_email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Valid email is required";
        } else {
            $update_query = "UPDATE users SET 
                full_name = $1, title = $2, contact_phone = $3, contact_email = $4, 
                contact_address = $5, skills = $6, organizations = $7, profile_summary = $8, 
                education = $9, projects = $10, interests = $11, profile_image = $12 
                WHERE id = $13";

            $update_result = pg_query_params($conn, $update_query, array(
                $full_name, $title, $contact_phone, $contact_email, $contact_address,
                $skills, $organizations, $profile_summary, $education, $projects, $interests, 
                $profile_image, $user_id
            ));

            if ($update_result) {
                $success_message = "Resume updated successfully!";
                $result = pg_query_params($conn, $query, array($user_id));
                $user_data = pg_fetch_assoc($result);
            } else {
                $error_message = "Error updating resume: " . pg_last_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit My Resume</title>
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
            padding: 20px;
        }
        
        .edit-container {
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }
        
        .header-actions {
            background: linear-gradient(45deg, #3B82F6, #60A5FA);
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .header-actions h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary {
            background: rgba(255, 255, 255, 0.9);
            color: #3B82F6;
        }
        
        .btn-primary:hover {
            background: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }
        
        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .form-content {
            padding: 40px;
        }
        
        .message {
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid;
        }
        
        .success {
            background: rgba(40, 167, 69, 0.1);
            color: #51cf66;
            border-color: rgba(40, 167, 69, 0.3);
        }
        
        .error {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border-color: rgba(220, 53, 69, 0.3);
        }
        
        .form-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            margin-bottom: 25px;
            border-radius: 15px;
            border-left: 4px solid #3B82F6;
            transition: all 0.3s ease;
        }
        
        .form-section:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(5px);
        }
        
        .form-section h3 {
            color: #3B82F6;
            margin-bottom: 25px;
            font-size: 1.4em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .photo-upload-section {
            display: flex;
            align-items: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .current-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid #3B82F6;
            overflow: hidden;
            background: linear-gradient(135deg, #3B82F6, #60A5FA);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 700;
        }
        
        .current-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-upload-controls {
            flex: 1;
            min-width: 250px;
        }
        
        .file-input-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }
        
        input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        
        .file-input-label {
            display: block;
            padding: 15px;
            background: rgba(59, 130, 246, 0.2);
            border: 2px dashed #3B82F6;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input-label:hover {
            background: rgba(59, 130, 246, 0.3);
            border-color: #60A5FA;
        }
        
        .file-name {
            margin-top: 10px;
            font-size: 14px;
            color: #b0b0b0;
            font-style: italic;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: #b0b0b0;
            font-size: 14px;
        }
        
        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        
        input[type="text"]:focus, input[type="email"]:focus, textarea:focus {
            outline: none;
            border-color: #3B82F6;
            background: rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }
        
        textarea {
            resize: vertical;
            min-height: 120px;
        }
        
        .form-help {
            font-size: 12px;
            color: #888;
            margin-top: 8px;
            font-style: italic;
        }
        
        .submit-section {
            background: rgba(255, 255, 255, 0.05);
            padding: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        
        .btn-submit {
            background: linear-gradient(45deg, #3B82F6, #60A5FA);
            color: white;
            padding: 18px 50px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
        }
        
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .header-actions {
                flex-direction: column;
                text-align: center;
            }
            
            .action-buttons {
                justify-content: center;
            }
            
            .form-content {
                padding: 20px;
            }
            
            .photo-upload-section {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
    <script>
        function updateFileName() {
            const input = document.getElementById('profile_photo');
            const fileNameDisplay = document.getElementById('file-name');
            if (input.files.length > 0) {
                fileNameDisplay.textContent = '📁 ' + input.files[0].name;
            } else {
                fileNameDisplay.textContent = 'No file chosen';
            }
        }
        
        function previewImage(input) {
            const preview = document.getElementById('photo-preview');
            const file = input.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Profile Preview">';
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</head>
<body>
    <div class="edit-container">
        <div class="header-actions">
            <h1>✏️ Edit My Resume</h1>
            <div class="action-buttons">
                <a href="public_resume.php?id=<?php echo $user_id; ?>" class="btn btn-primary" target="_blank">
                    👁️ View Public Resume
                </a>
                <a href="logout.php" class="btn btn-secondary">
                    🚪 Logout
                </a>
            </div>
        </div>

        <div class="form-content">
            <?php if ($success_message): ?>
                <div class="message success">✅ <?php echo $success_message; ?></div>
            <?php endif; ?>
            
            <?php if ($error_message): ?>
                <div class="message error">❌ <?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="edit_resume.php" enctype="multipart/form-data">
                <div class="form-section">
                    <h3>📸 Profile Photo</h3>
                    <div class="photo-upload-section">
                        <div class="current-photo" id="photo-preview">
                            <?php if (!empty($user_data['profile_image']) && file_exists($user_data['profile_image'])): ?>
                                <img src="<?php echo htmlspecialchars($user_data['profile_image']); ?>" alt="Profile Photo">
                            <?php else: ?>
                                <?php
                                $name = $user_data['full_name'] ?? 'USER';
                                $words = explode(' ', $name);
                                if (count($words) >= 2) {
                                    echo strtoupper(substr($words[0], 0, 1) . substr($words[count($words)-1], 0, 1));
                                } else {
                                    echo strtoupper(substr($name, 0, 2));
                                }
                                ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="photo-upload-controls">
                            <div class="file-input-wrapper">
                                <label class="file-input-label" for="profile_photo">
                                    📷 Click to Upload Photo<br>
                                    <small style="color: #b0b0b0;">JPG, PNG, or GIF (Max 5MB)</small>
                                </label>
                                <input type="file" 
                                       id="profile_photo" 
                                       name="profile_photo" 
                                       accept="image/*"
                                       onchange="updateFileName(); previewImage(this);">
                            </div>
                            <div class="file-name" id="file-name">No file chosen</div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>👤 Personal Information</h3>
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="full_name">Full Name *</label>
                            <input type="text" id="full_name" name="full_name" 
                                   value="<?php echo htmlspecialchars($user_data['full_name'] ?? ''); ?>" 
                                   required>
                        </div>
                        <div class="form-group full-width">
                            <label for="title">Professional Title</label>
                            <input type="text" id="title" name="title" 
                                   value="<?php echo htmlspecialchars($user_data['title'] ?? ''); ?>"
                                   placeholder="e.g., Computer Science Student">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>📞 Contact Information</h3>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="contact_phone">Phone Number</label>
                            <input type="text" id="contact_phone" name="contact_phone" 
                                   value="<?php echo htmlspecialchars($user_data['contact_phone'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact_email">Email Address *</label>
                            <input type="email" id="contact_email" name="contact_email" 
                                   value="<?php echo htmlspecialchars($user_data['contact_email'] ?? ''); ?>"
                                   required>
                        </div>
                        <div class="form-group full-width">
                            <label for="contact_address">Address</label>
                            <textarea id="contact_address" name="contact_address" 
                                      placeholder="Enter your full address"><?php echo htmlspecialchars($user_data['contact_address'] ?? ''); ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>💼 Skills</h3>
                    <div class="form-group full-width">
                        <label for="skills">Skills (comma-separated)</label>
                        <textarea id="skills" name="skills" 
                                  placeholder="e.g., C++, Java, Python, HTML, CSS, JavaScript, MySQL"><?php echo htmlspecialchars($user_data['skills'] ?? ''); ?></textarea>
                        <div class="form-help">Separate each skill with a comma</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🏢 Organizations</h3>
                    <div class="form-group full-width">
                        <label for="organizations">Organizations (one per line)</label>
                        <textarea id="organizations" name="organizations" 
                                  placeholder="e.g., Junior Philippine Computer Society (JPCS) – Member"><?php echo htmlspecialchars($user_data['organizations'] ?? ''); ?></textarea>
                        <div class="form-help">Enter each organization on a new line</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>📝 Profile Summary</h3>
                    <div class="form-group full-width">
                        <label for="profile_summary">Professional Summary</label>
                        <textarea id="profile_summary" name="profile_summary" 
                                  placeholder="Describe your professional background and goals..."><?php echo htmlspecialchars($user_data['profile_summary'] ?? ''); ?></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🎓 Education</h3>
                    <div class="form-group full-width">
                        <label for="education">Education Details (one per line)</label>
                        <textarea id="education" name="education" 
                                  placeholder="e.g., 2023 – 2027&#10;Bachelor of Science in Computer Science&#10;Batangas State University"><?php echo htmlspecialchars($user_data['education'] ?? ''); ?></textarea>
                        <div class="form-help">Enter year, degree, and institution on separate lines</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🚀 Academic Projects</h3>
                    <div class="form-group full-width">
                        <label for="projects">Project Details (one per line)</label>
                        <textarea id="projects" name="projects" 
                                  placeholder="e.g., 2024&#10;Web Development Projects&#10;Student Developer&#10;• Developed responsive web applications..."><?php echo htmlspecialchars($user_data['projects'] ?? ''); ?></textarea>
                        <div class="form-help">Include year, project name, role, and bullet points</div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>🎯 Areas of Interest</h3>
                    <div class="form-group full-width">
                        <label for="interests">Interests (comma-separated)</label>
                        <textarea id="interests" name="interests" 
                                  placeholder="e.g., Web Development, Database Management, Software Engineering"><?php echo htmlspecialchars($user_data['interests'] ?? ''); ?></textarea>
                        <div class="form-help">Separate each interest with a comma</div>
                    </div>
                </div>

                <div class="submit-section">
                    <button type="submit" class="btn-submit">
                        💾 Save All Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
<?php pg_close($conn); ?>