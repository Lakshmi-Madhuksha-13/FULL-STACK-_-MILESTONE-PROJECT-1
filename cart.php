<?php
require_once 'config.php';

// Initialize cart if not exists
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle cart actions
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $action = $_POST['action'];
    
    if($action == 'add') {
        if(isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id]++;
        } else {
            $_SESSION['cart'][$product_id] = 1;
        }
        $_SESSION['success'] = "Product added to cart";
    }
    header("Location: cart.php");
    exit();
}

// Update quantity
if(isset($_GET['update'])) {
    $product_id = $_GET['update'];
    $quantity = $_GET['qty'];
    
    if($quantity > 0) {
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        unset($_SESSION['cart'][$product_id]);
    }
    header("Location: cart.php");
    exit();
}

// Remove item
if(isset($_GET['remove'])) {
    $product_id = $_GET['remove'];
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$cart_items = [];
$total = 0;

if(!empty($_SESSION['cart'])) {
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    $query = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = mysqli_query($conn, $query);
    
    while($product = mysqli_fetch_assoc($result)) {
        $product['quantity'] = $_SESSION['cart'][$product['id']];
        $product['subtotal'] = $product['price'] * $product['quantity'];
        $total += $product['subtotal'];
        $cart_items[] = $product;
    }
}
?>

<?php include 'header.php'; ?>

<div class="cart-page">
    <div class="container">
        <h2 class="section-title">Shopping Cart</h2>
        
        <?php if(isset($_SESSION['success'])): ?>
            <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <?php if(!empty($cart_items)): ?>
            <div class="cart-container">
                <div class="cart-items">
                    <?php foreach($cart_items as $item): ?>
                        <div class="cart-item">
    <?php 
    // Fix image path for cart
    $cart_image = $item['image'];
    if(empty($cart_image)) {
        $cart_image = 'https://via.placeholder.com/100x100?text=No+Image';
    } elseif(strpos($cart_image, 'http') !== 0 && strpos($cart_image, '/') !== 0) {
        // If not starting with http or /, add the correct path
        $cart_image = '/shopsphere/' . $cart_image;
    }
    ?>
    <img src="<?php echo $cart_image; ?>" 
         alt="<?php echo $item['name']; ?>"
         onerror="this.src='https://via.placeholder.com/100x100?text=Error'">
    
    <div class="cart-item-details">
        <h3><?php echo $item['name']; ?></h3>
        <p class="item-price">$<?php echo number_format($item['price'], 2); ?></p>
        <span id="stock-<?php echo $item['id']; ?>" data-max="<?php echo $item['stock']; ?>" style="display: none;"></span>
    </div>
    <!-- rest of the cart item code -->
                            <div class="cart-item-quantity">
                                <label>Qty:</label>
                                <input type="number" 
                                       value="<?php echo $item['quantity']; ?>" 
                                       min="0" 
                                       max="<?php echo $item['stock']; ?>"
                                       onchange="updateCartQuantity(<?php echo $item['id']; ?>, this.value)"
                                       oninput="validateQuantity(this, <?php echo $item['stock']; ?>)">
                            </div>
                            <div class="cart-item-subtotal">
                                $<?php echo number_format($item['subtotal'], 2); ?>
                            </div>
                            <a href="javascript:void(0)" onclick="if(confirm('Remove item from cart?')) window.location.href='?remove=<?php echo $item['id']; ?>'" class="btn-remove-item">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="cart-summary">
                    <h3>Order Summary</h3>
                    <div class="summary-row">
                        <span>Subtotal:</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping:</span>
                        <span>Free</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total:</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <?php if(isLoggedIn()): ?>
                        <button class="btn-primary btn-full" onclick="checkout()">Proceed to Checkout</button>
                    <?php else: ?>
                        <p class="login-message">Please <a href="login.php">login</a> to checkout</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-shopping-cart"></i>
                <h3>Your cart is empty</h3>
                <p>Looks like you haven't added anything yet!</p>
                <a href="index.php#products" class="btn-primary">Start Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

