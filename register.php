<?php
require_once 'config.php';

if(isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$errors = [];
$form_data = [];

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $form_data = ['username' => $username, 'email' => $email];
    
    // Validation
    if(empty($username) || empty($email) || empty($password)) {
        $errors[] = "All fields are required";
    }
    
    if(strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }
    
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if(strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }
    
    // Check if username/email exists
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) > 0) {
        $errors[] = "Username or email already exists";
    }
    
    if(empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        
        if(mysqli_query($conn, $insert_query)) {
            $_SESSION['success'] = "Registration successful! Please login.";
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Create Account</h2>
        
        <?php if(!empty($errors)): ?>
            <div class="alert error">
                <?php foreach($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div id="clientErrors" class="alert error" style="display: none;"></div>
        
        <form method="POST" action="" class="auth-form" id="registerForm" onsubmit="return validateRegisterForm()">
            <div class="form-group">
                <label for="regUsername">Username</label>
                <input type="text" id="regUsername" name="username" value="<?php echo isset($form_data['username']) ? htmlspecialchars($form_data['username']) : ''; ?>">
                <small class="error-message" id="usernameError"></small>
            </div>
            
            <div class="form-group">
                <label for="regEmail">Email</label>
                <input type="email" id="regEmail" name="email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>">
                <small class="error-message" id="emailError"></small>
            </div>
            
            <div class="form-group">
                <label for="regPassword">Password</label>
                <input type="password" id="regPassword" name="password">
                <small class="error-message" id="passwordError"></small>
            </div>
            
            <div class="form-group">
                <label for="regConfirmPassword">Confirm Password</label>
                <input type="password" id="regConfirmPassword" name="confirm_password">
                <small class="error-message" id="confirmPasswordError"></small>
            </div>
            
            <button type="submit" class="btn-primary btn-full">Register</button>
            
            <p class="auth-link">
                Already have an account? <a href="login.php">Login here</a>
            </p>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>