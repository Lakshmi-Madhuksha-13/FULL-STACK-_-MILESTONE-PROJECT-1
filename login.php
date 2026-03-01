<?php
require_once 'config.php';

if(isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    
    // Validation
    if(empty($username) || empty($password)) {
        $error = "Please fill in all fields";
    } else {
        $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
        $result = mysqli_query($conn, $query);
        
        if(mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            if(password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['success'] = "Welcome back, " . $user['username'] . "!";
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "User not found";
        }
    }
}
?>

<?php include 'header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h2>Login to ShopSphere</h2>
        
        <?php if($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div id="clientErrors" class="alert error" style="display: none;"></div>
        
        <form method="POST" action="" class="auth-form" id="loginForm" onsubmit="return validateLoginForm()">
            <div class="form-group">
                <label for="username">Username or Email</label>
                <input type="text" id="username" name="username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                <small class="error-message" id="usernameError"></small>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password">
                <small class="error-message" id="passwordError"></small>
            </div>
            
            <button type="submit" class="btn-primary btn-full">Login</button>
            
            <p class="auth-link">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>