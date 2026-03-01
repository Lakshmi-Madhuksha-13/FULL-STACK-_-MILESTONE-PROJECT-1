<?php
require_once 'config.php';

if(!isLoggedIn()) {
    $_SESSION['error'] = "Please login to view wishlist";
    header("Location: login.php");
    exit();
}

// Add to wishlist
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $user_id = $_SESSION['user_id'];
    
    $check_query = "SELECT * FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
    $check_result = mysqli_query($conn, $check_query);
    
    if(mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";
        if(mysqli_query($conn, $insert_query)) {
            $_SESSION['success'] = "Product added to wishlist";
        }
    }
    header("Location: wishlist.php");
    exit();
}

// Remove from wishlist
if(isset($_GET['remove'])) {
    $wishlist_id = mysqli_real_escape_string($conn, $_GET['remove']);
    $user_id = $_SESSION['user_id'];
    
    $delete_query = "DELETE FROM wishlist WHERE id = $wishlist_id AND user_id = $user_id";
    mysqli_query($conn, $delete_query);
    $_SESSION['success'] = "Product removed from wishlist";
    header("Location: wishlist.php");
    exit();
}

// Fetch wishlist items
$user_id = $_SESSION['user_id'];
$query = "SELECT w.id as wishlist_id, p.* FROM wishlist w 
          JOIN products p ON w.product_id = p.id 
          WHERE w.user_id = $user_id 
          ORDER BY w.added_date DESC";
$result = mysqli_query($conn, $query);
?>

<?php include 'header.php'; ?>

<div class="wishlist-page">
    <div class="container">
        <h2 class="section-title">My Wishlist</h2>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <div class="wishlist-grid">
                <?php while($item = mysqli_fetch_assoc($result)): ?>
                    <div class="wishlist-item">
                        <?php 
                        // Fix image path for wishlist
                        $wishlist_image = $item['image'];
                        if(empty($wishlist_image)) {
                            $wishlist_image = 'https://via.placeholder.com/150x150?text=No+Image';
                        } elseif(strpos($wishlist_image, 'http') !== 0 && strpos($wishlist_image, '/') !== 0) {
                            // If not starting with http or /, add the correct path
                            $wishlist_image = '/shopsphere/' . $wishlist_image;
                        }
                        ?>
                        <img src="<?php echo $wishlist_image; ?>" 
                             alt="<?php echo $item['name']; ?>"
                             onerror="this.src='https://via.placeholder.com/150x150?text=Error'">
                        
                        <div class="wishlist-item-info">
                            <h3><?php echo $item['name']; ?></h3>
                            <p class="price">$<?php echo number_format($item['price'], 2); ?></p>
                            <p class="stock <?php echo $item['stock'] > 0 ? 'in-stock' : 'out-of-stock'; ?>">
                                <?php echo $item['stock'] > 0 ? 'In Stock' : 'Out of Stock'; ?>
                            </p>
                            <div class="wishlist-item-actions">
                                <button onclick="addToCart(<?php echo $item['id']; ?>, <?php echo $item['stock']; ?>)" 
                                        class="btn-add-cart" 
                                        <?php echo $item['stock'] <= 0 ? 'disabled' : ''; ?>>
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                                <a href="javascript:void(0)" onclick="removeFromWishlist(<?php echo $item['wishlist_id']; ?>)" class="btn-remove">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-heart"></i>
                <h3>Your wishlist is empty</h3>
                <p>Browse our products and add items you love!</p>
                <a href="index.php#products" class="btn-primary">Browse Products</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>