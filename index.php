<?php
require_once 'config.php';

// Fetch products from database
$query = "SELECT * FROM products ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<?php include 'header.php'; ?>

<main>
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Welcome to ShopSphere</h1>
            <p>Discover amazing products at unbeatable prices</p>
            <a href="#products" class="btn-primary">Shop Now</a>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="products-section">
        <div class="container">
            <h2 class="section-title">Our Products</h2>
            
            <?php if(isset($_SESSION['success'])): ?>
                <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            
            <div class="products-grid">
    <?php while($product = mysqli_fetch_assoc($result)): 
        // Fix image path
        $image_src = $product['image'];
        if(empty($image_src)) {
            $image_src = 'https://via.placeholder.com/300x200?text=No+Image';
        } elseif(strpos($image_src, 'http') !== 0 && strpos($image_src, '/') !== 0) {
            // If not starting with http or /, add the project path
            $image_src = '/shopsphere/' . $image_src;
        }
    ?>
        <div class="product-card">
            <div class="product-image">
                <img src="<?php echo $image_src; ?>" 
                     alt="<?php echo $product['name']; ?>"
                     onerror="this.src='https://via.placeholder.com/300x200?text=Image+Not+Found'">
                <div class="product-overlay">
                    <button class="quick-view" onclick="viewProduct(<?php echo $product['id']; ?>)">Quick View</button>
                </div>
            </div>
            <div class="product-info">
                <h3><?php echo $product['name']; ?></h3>
                <p class="product-category"><?php echo $product['category']; ?></p>
                <p class="product-price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="product-stock">Stock: <?php echo $product['stock']; ?></p>
                <div class="product-actions">
                    <button onclick="addToCart(<?php echo $product['id']; ?>, <?php echo $product['stock']; ?>)" 
                            class="btn-add-cart" 
                            <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button>
                    <button onclick="addToWishlist(<?php echo $product['id']; ?>)" class="btn-wishlist">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
</div>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>