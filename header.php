<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopSphere - Modern E-Commerce</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="script.js" defer></script>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-container">
                <div class="nav-brand">
                    <a href="index.php">ShopSphere</a>
                </div>
                
                <div class="nav-menu" id="navMenu">
                    <ul class="nav-links">
                        <li><a href="index.php">Home</a></li>
                        <li><a href="index.php#products">Products</a></li>
                        <li><a href="wishlist.php">Wishlist</a></li>
                        <li><a href="cart.php">Cart</a></li>
                    </ul>
                    
                    <div class="nav-icons">
                        <a href="wishlist.php" class="icon-link">
                            <i class="fas fa-heart"></i>
                        </a>
                        <a href="cart.php" class="icon-link cart-icon">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-count"><?php echo getCartCount(); ?></span>
                        </a>
                        
                        <?php if(isLoggedIn()): ?>
                            <span class="user-name">Hi, <?php echo $_SESSION['username']; ?></span>
                            <a href="logout.php" class="btn-logout">Logout</a>
                        <?php else: ?>
                            <a href="login.php" class="btn-login">Login</a>
                            <a href="register.php" class="btn-register">Register</a>
                        <?php endif; ?>
                        
                        <button class="mobile-menu-btn" onclick="toggleMenu()">
                            <i class="fas fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </header>