// Navigation Menu Toggle
function toggleMenu() {
    const navMenu = document.getElementById('navMenu');
    if (navMenu) {
        navMenu.classList.toggle('active');
    }
}

// Close mobile menu when clicking outside
document.addEventListener('click', function(event) {
    const navMenu = document.getElementById('navMenu');
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    
    if (navMenu && mobileBtn && !navMenu.contains(event.target) && !mobileBtn.contains(event.target)) {
        navMenu.classList.remove('active');
    }
});

// ==================== LOGIN FORM VALIDATION ====================
function validateLoginForm() {
    let isValid = true;
    const username = document.getElementById('username')?.value.trim();
    const password = document.getElementById('password')?.value.trim();
    
    // Clear previous errors
    clearErrors();
    
    // Username validation
    if (!username) {
        showError('usernameError', 'Username or email is required');
        isValid = false;
    } else if (username.length < 3) {
        showError('usernameError', 'Username must be at least 3 characters');
        isValid = false;
    }
    
    // Password validation
    if (!password) {
        showError('passwordError', 'Password is required');
        isValid = false;
    } else if (password.length < 6) {
        showError('passwordError', 'Password must be at least 6 characters');
        isValid = false;
    }
    
    if (!isValid) {
        showClientError('Please fix the errors below');
    }
    
    return isValid;
}

// ==================== REGISTER FORM VALIDATION ====================
function validateRegisterForm() {
    let isValid = true;
    const username = document.getElementById('regUsername')?.value.trim();
    const email = document.getElementById('regEmail')?.value.trim();
    const password = document.getElementById('regPassword')?.value.trim();
    const confirmPassword = document.getElementById('regConfirmPassword')?.value.trim();
    
    // Clear previous errors
    clearErrors();
    
    // Username validation
    if (!username) {
        showError('usernameError', 'Username is required');
        isValid = false;
    } else if (username.length < 3) {
        showError('usernameError', 'Username must be at least 3 characters');
        isValid = false;
    } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
        showError('usernameError', 'Username can only contain letters, numbers, and underscores');
        isValid = false;
    }
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email) {
        showError('emailError', 'Email is required');
        isValid = false;
    } else if (!emailRegex.test(email)) {
        showError('emailError', 'Please enter a valid email address');
        isValid = false;
    }
    
    // Password validation
    const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/;
    if (!password) {
        showError('passwordError', 'Password is required');
        isValid = false;
    } else if (password.length < 6) {
        showError('passwordError', 'Password must be at least 6 characters');
        isValid = false;
    } else if (!passwordRegex.test(password)) {
        showError('passwordError', 'Password must contain at least one letter and one number');
        isValid = false;
    }
    
    // Confirm password validation
    if (!confirmPassword) {
        showError('confirmPasswordError', 'Please confirm your password');
        isValid = false;
    } else if (password !== confirmPassword) {
        showError('confirmPasswordError', 'Passwords do not match');
        isValid = false;
    }
    
    if (!isValid) {
        showClientError('Please fix the errors below');
    }
    
    return isValid;
}

// ==================== CHECKOUT VALIDATION ====================
function validateCheckout() {
    // Check if user is logged in
    const isLoggedIn = document.body.hasAttribute('data-logged-in');
    
    if (!isLoggedIn) {
        alert('Please login to proceed with checkout');
        window.location.href = 'login.php';
        return false;
    }
    
    // Check if cart is empty
    const cartCount = document.querySelector('.cart-count')?.textContent || '0';
    if (cartCount === '0') {
        alert('Your cart is empty');
        return false;
    }
    
    return true;
}

// ==================== WISHLIST ACTIONS ====================
function addToWishlist(productId) {
    if (!confirm('Add this item to wishlist?')) {
        return false;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'wishlist.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    
    const productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'product_id';
    productInput.value = productId;
    
    form.appendChild(actionInput);
    form.appendChild(productInput);
    document.body.appendChild(form);
    form.submit();
    
    return true;
}

function removeFromWishlist(wishlistId) {
    if (confirm('Remove this item from wishlist?')) {
        window.location.href = 'wishlist.php?remove=' + wishlistId;
    }
    return false;
}

// ==================== CART ACTIONS ====================
function updateCartQuantity(productId, quantity) {
    quantity = parseInt(quantity);
    
    if (quantity < 0) {
        alert('Quantity cannot be negative');
        return false;
    }
    
    if (quantity === 0) {
        if (confirm('Remove this item from cart?')) {
            window.location.href = 'cart.php?remove=' + productId;
        }
        return false;
    }
    
    // Get max stock from data attribute
    const stockElement = document.getElementById(`stock-${productId}`);
    const maxStock = stockElement ? parseInt(stockElement.getAttribute('data-max')) : 99;
    
    if (quantity > maxStock) {
        alert('Quantity exceeds available stock');
        return false;
    }
    
    window.location.href = `cart.php?update=${productId}&qty=${quantity}`;
    return true;
}

function addToCart(productId, stock) {
    if (stock <= 0) {
        alert('This product is out of stock');
        return false;
    }
    
    if (!confirm('Add this item to cart?')) {
        return false;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'cart.php';
    
    const actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'add';
    
    const productInput = document.createElement('input');
    productInput.type = 'hidden';
    productInput.name = 'product_id';
    productInput.value = productId;
    
    form.appendChild(actionInput);
    form.appendChild(productInput);
    document.body.appendChild(form);
    form.submit();
    
    return true;
}

// ==================== HELPER FUNCTIONS ====================
function showError(elementId, message) {
    const errorElement = document.getElementById(elementId);
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        
        // Add error class to input
        const inputId = elementId.replace('Error', '');
        const input = document.getElementById(inputId) || document.getElementById('reg' + inputId.charAt(0).toUpperCase() + inputId.slice(1));
        if (input) {
            input.classList.add('error');
            input.classList.remove('valid');
        }
    }
}

function showClientError(message) {
    const clientErrors = document.getElementById('clientErrors');
    if (clientErrors) {
        clientErrors.textContent = message;
        clientErrors.style.display = 'block';
    }
}

function clearErrors() {
    const errorElements = document.querySelectorAll('.error-message');
    errorElements.forEach(el => {
        el.textContent = '';
        el.style.display = 'none';
    });
    
    // Remove error classes from inputs
    document.querySelectorAll('.form-group input').forEach(input => {
        input.classList.remove('error', 'valid');
    });
    
    const clientErrors = document.getElementById('clientErrors');
    if (clientErrors) {
        clientErrors.style.display = 'none';
    }
}

// ==================== PRODUCT QUICK VIEW ====================
function viewProduct(productId) {
    // In a real application, this would open a modal with product details
    alert('Product ID: ' + productId + '\n\nProduct Details:\n• Name: Sample Product\n• Price: $XX.XX\n• Description: This is a sample product description.\n• Stock: Available');
}

// ==================== CHECKOUT FUNCTION ====================
function checkout() {
    if (validateCheckout()) {
        alert('Proceeding to checkout...\n\nThis is a demo feature.\nIn a real application, you would be redirected to payment gateway.');
    }
}

// ==================== QUANTITY INPUT VALIDATION ====================
function validateQuantity(input, maxStock) {
    let value = parseInt(input.value);
    
    if (isNaN(value) || value < 0) {
        input.value = 0;
    } else if (value > maxStock) {
        input.value = maxStock;
        alert('Maximum available stock: ' + maxStock);
    }
}

// ==================== REAL-TIME VALIDATION ====================
document.addEventListener('DOMContentLoaded', function() {
    // Set logged-in status as data attribute on body
    const isLoggedIn = document.querySelector('.user-name') !== null;
    if (isLoggedIn) {
        document.body.setAttribute('data-logged-in', 'true');
    }
    
    // Real-time validation for login form
    const loginUsername = document.getElementById('username');
    if (loginUsername) {
        loginUsername.addEventListener('input', function() {
            const username = this.value.trim();
            if (username && username.length < 3) {
                showError('usernameError', 'Username must be at least 3 characters');
                this.classList.add('error');
                this.classList.remove('valid');
            } else if (username) {
                document.getElementById('usernameError').style.display = 'none';
                this.classList.remove('error');
                this.classList.add('valid');
            } else {
                document.getElementById('usernameError').style.display = 'none';
                this.classList.remove('error', 'valid');
            }
        });
    }
    
    const loginPassword = document.getElementById('password');
    if (loginPassword) {
        loginPassword.addEventListener('input', function() {
            const password = this.value.trim();
            if (password && password.length < 6) {
                showError('passwordError', 'Password must be at least 6 characters');
                this.classList.add('error');
                this.classList.remove('valid');
            } else if (password) {
                document.getElementById('passwordError').style.display = 'none';
                this.classList.remove('error');
                this.classList.add('valid');
            } else {
                document.getElementById('passwordError').style.display = 'none';
                this.classList.remove('error', 'valid');
            }
        });
    }
    
    // Real-time validation for register form
    const regUsername = document.getElementById('regUsername');
    if (regUsername) {
        regUsername.addEventListener('input', function() {
            const username = this.value.trim();
            const errorElement = document.getElementById('usernameError');
            
            if (!username) {
                errorElement.style.display = 'none';
                this.classList.remove('error', 'valid');
            } else if (username.length < 3) {
                showError('usernameError', 'Username must be at least 3 characters');
                this.classList.add('error');
                this.classList.remove('valid');
            } else if (!/^[a-zA-Z0-9_]+$/.test(username)) {
                showError('usernameError', 'Username can only contain letters, numbers, and underscores');
                this.classList.add('error');
                this.classList.remove('valid');
            } else {
                errorElement.style.display = 'none';
                this.classList.remove('error');
                this.classList.add('valid');
            }
        });
    }
    
    const regEmail = document.getElementById('regEmail');
    if (regEmail) {
        regEmail.addEventListener('input', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const errorElement = document.getElementById('emailError');
            
            if (!email) {
                errorElement.style.display = 'none';
                this.classList.remove('error', 'valid');
            } else if (!emailRegex.test(email)) {
                showError('emailError', 'Please enter a valid email address');
                this.classList.add('error');
                this.classList.remove('valid');
            } else {
                errorElement.style.display = 'none';
                this.classList.remove('error');
                this.classList.add('valid');
            }
        });
    }
    
    const regPassword = document.getElementById('regPassword');
    if (regPassword) {
        regPassword.addEventListener('input', function() {
            const password = this.value.trim();
            const errorElement = document.getElementById('passwordError');
            const confirmPassword = document.getElementById('regConfirmPassword')?.value.trim();
            
            if (!password) {
                errorElement.style.display = 'none';
                this.classList.remove('error', 'valid');
            } else if (password.length < 6) {
                showError('passwordError', 'Password must be at least 6 characters');
                this.classList.add('error');
                this.classList.remove('valid');
            } else if (!/^(?=.*[A-Za-z])(?=.*\d)/.test(password)) {
                showError('passwordError', 'Password must contain at least one letter and one number');
                this.classList.add('error');
                this.classList.remove('valid');
            } else {
                errorElement.style.display = 'none';
                this.classList.remove('error');
                this.classList.add('valid');
            }
            
            // Check confirm password if it has value
            if (confirmPassword) {
                const confirmError = document.getElementById('confirmPasswordError');
                if (password !== confirmPassword) {
                    showError('confirmPasswordError', 'Passwords do not match');
                    document.getElementById('regConfirmPassword').classList.add('error');
                    document.getElementById('regConfirmPassword').classList.remove('valid');
                } else {
                    confirmError.style.display = 'none';
                    document.getElementById('regConfirmPassword').classList.remove('error');
                    document.getElementById('regConfirmPassword').classList.add('valid');
                }
            }
        });
    }
    
    const regConfirmPassword = document.getElementById('regConfirmPassword');
    if (regConfirmPassword) {
        regConfirmPassword.addEventListener('input', function() {
            const confirmPassword = this.value.trim();
            const password = document.getElementById('regPassword')?.value.trim();
            const errorElement = document.getElementById('confirmPasswordError');
            
            if (!confirmPassword) {
                errorElement.style.display = 'none';
                this.classList.remove('error', 'valid');
            } else if (password !== confirmPassword) {
                showError('confirmPasswordError', 'Passwords do not match');
                this.classList.add('error');
                this.classList.remove('valid');
            } else {
                errorElement.style.display = 'none';
                this.classList.remove('error');
                this.classList.add('valid');
            }
        });
    }
});