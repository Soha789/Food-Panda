<?php
session_start();

// Initialize cart if not exists
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Handle add to cart
if(isset($_POST['add_to_cart'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_price = $_POST['item_price'];
    
    // Check if item already in cart
    $found = false;
    foreach($_SESSION['cart'] as &$item) {
        if($item['id'] == $item_id) {
            $item['quantity']++;
            $found = true;
            break;
        }
    }
    
    if(!$found) {
        $_SESSION['cart'][] = array(
            'id' => $item_id,
            'name' => $item_name,
            'price' => $item_price,
            'quantity' => 1
        );
    }
    
    echo "<script>alert('Item added to cart!');</script>";
}

// Calculate cart total
$cart_total = 0;
$cart_count = 0;
if(isset($_SESSION['cart'])) {
    foreach($_SESSION['cart'] as $item) {
        $cart_total += $item['price'] * $item['quantity'];
        $cart_count += $item['quantity'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - FoodExpress</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #fff5f7 0%, #ffe0e8 100%);
            min-height: 100vh;
        }

        .navbar {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            padding: 1.2rem 5%;
            box-shadow: 0 4px 20px rgba(255, 20, 147, 0.3);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-size: 2rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            cursor: pointer;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 25px;
        }

        .nav-links a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .cart-icon {
            position: relative;
            background: white;
            color: #ff1493;
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .cart-icon:hover {
            transform: scale(1.05);
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff1493;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 5%;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: #ff1493;
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .filters {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }

        .filter-btn {
            padding: 0.8rem 1.5rem;
            background: white;
            border: 2px solid #ff69b4;
            color: #ff1493;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            transform: translateY(-2px);
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .menu-item {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(255, 20, 147, 0.15);
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255, 20, 147, 0.25);
        }

        .item-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #ffe0e8 0%, #ffb3d9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }

        .item-info {
            padding: 1.5rem;
        }

        .item-info h3 {
            color: #ff1493;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .item-description {
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.5;
        }

        .item-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price {
            font-size: 1.5rem;
            color: #ff1493;
            font-weight: bold;
        }

        .add-btn {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 20, 147, 0.3);
        }

        .cart-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .cart-content {
            background: white;
            border-radius: 30px;
            padding: 2rem;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .cart-header h2 {
            color: #ff1493;
            font-size: 2rem;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            color: #ff1493;
            cursor: pointer;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: #fff5f7;
            border-radius: 15px;
            margin-bottom: 1rem;
        }

        .cart-item-info {
            flex: 1;
        }

        .cart-item-name {
            color: #ff1493;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .cart-total {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-top: 1rem;
            text-align: center;
        }

        .cart-total h3 {
            font-size: 1.5rem;
        }

        .checkout-btn {
            width: 100%;
            padding: 1rem;
            background: white;
            color: #ff1493;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            transform: scale(1.02);
        }

        .empty-cart {
            text-align: center;
            padding: 2rem;
            color: #666;
        }

        @media (max-width: 768px) {
            .menu-grid {
                grid-template-columns: 1fr;
            }

            .filters {
                gap: 0.5rem;
            }

            .filter-btn {
                padding: 0.6rem 1rem;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo" onclick="window.location.href='index.php'">🍔 FoodExpress</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="menu.php">Menu</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="track.php">Track Order</a>
                    <a href="logout.php">Logout</a>
                <?php endif; ?>
                <div class="cart-icon" onclick="toggleCart()">
                    🛒 Cart
                    <?php if($cart_count > 0): ?>
                        <span class="cart-count"><?php echo $cart_count; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>🍽️ Our Menu</h1>
            <p>Choose from our delicious selection</p>
        </div>

        <div class="filters">
            <button class="filter-btn active" onclick="filterMenu('all')">All</button>
            <button class="filter-btn" onclick="filterMenu('pizza')">🍕 Pizza</button>
            <button class="filter-btn" onclick="filterMenu('burger')">🍔 Burgers</button>
            <button class="filter-btn" onclick="filterMenu('asian')">🍜 Asian</button>
            <button class="filter-btn" onclick="filterMenu('indian')">🍛 Indian</button>
            <button class="filter-btn" onclick="filterMenu('dessert')">🍰 Desserts</button>
        </div>

        <div class="menu-grid" id="menuGrid">
            <!-- Pizza Items -->
            <div class="menu-item" data-category="pizza">
                <div class="item-image">🍕</div>
                <div class="item-info">
                    <h3>Margherita Pizza</h3>
                    <p class="item-description">Classic pizza with tomato sauce, mozzarella, and fresh basil</p>
                    <div class="item-footer">
                        <span class="price">$12.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="1">
                            <input type="hidden" name="item_name" value="Margherita Pizza">
                            <input type="hidden" name="item_price" value="12.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="menu-item" data-category="pizza">
                <div class="item-image">🍕</div>
                <div class="item-info">
                    <h3>Pepperoni Pizza</h3>
                    <p class="item-description">Loaded with pepperoni and extra cheese</p>
                    <div class="item-footer">
                        <span class="price">$14.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="2">
                            <input type="hidden" name="item_name" value="Pepperoni Pizza">
                            <input type="hidden" name="item_price" value="14.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Burger Items -->
            <div class="menu-item" data-category="burger">
                <div class="item-image">🍔</div>
                <div class="item-info">
                    <h3>Classic Burger</h3>
                    <p class="item-description">Juicy beef patty with lettuce, tomato, and special sauce</p>
                    <div class="item-footer">
                        <span class="price">$9.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="3">
                            <input type="hidden" name="item_name" value="Classic Burger">
                            <input type="hidden" name="item_price" value="9.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="menu-item" data-category="burger">
                <div class="item-image">🍔</div>
                <div class="item-info">
                    <h3>Cheese Burger</h3>
                    <p class="item-description">Double cheese with crispy bacon and onion rings</p>
                    <div class="item-footer">
                        <span class="price">$11.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="4">
                            <input type="hidden" name="item_name" value="Cheese Burger">
                            <input type="hidden" name="item_price" value="11.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Asian Items -->
            <div class="menu-item" data-category="asian">
                <div class="item-image">🍜</div>
                <div class="item-info">
                    <h3>Chicken Ramen</h3>
                    <p class="item-description">Rich broth with noodles, chicken, and vegetables</p>
                    <div class="item-footer">
                        <span class="price">$13.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="5">
                            <input type="hidden" name="item_name" value="Chicken Ramen">
                            <input type="hidden" name="item_price" value="13.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="menu-item" data-category="asian">
                <div class="item-image">🍱</div>
                <div class="item-info">
                    <h3>Sushi Platter</h3>
                    <p class="item-description">Assorted fresh sushi rolls with wasabi and ginger</p>
                    <div class="item-footer">
                        <span class="price">$18.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="6">
                            <input type="hidden" name="item_name" value="Sushi Platter">
                            <input type="hidden" name="item_price" value="18.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Indian Items -->
            <div class="menu-item" data-category="indian">
                <div class="item-image">🍛</div>
                <div class="item-info">
                    <h3>Chicken Biryani</h3>
                    <p class="item-description">Aromatic basmati rice with spiced chicken</p>
                    <div class="item-footer">
                        <span class="price">$15.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="7">
                            <input type="hidden" name="item_name" value="Chicken Biryani">
                            <input type="hidden" name="item_price" value="15.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="menu-item" data-category="indian">
                <div class="item-image">🍛</div>
                <div class="item-info">
                    <h3>Butter Chicken</h3>
                    <p class="item-description">Creamy tomato curry with tender chicken pieces</p>
                    <div class="item-footer">
                        <span class="price">$14.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="8">
                            <input type="hidden" name="item_name" value="Butter Chicken">
                            <input type="hidden" name="item_price" value="14.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dessert Items -->
            <div class="menu-item" data-category="dessert">
                <div class="item-image">🍰</div>
                <div class="item-info">
                    <h3>Chocolate Cake</h3>
                    <p class="item-description">Rich chocolate cake with creamy frosting</p>
                    <div class="item-footer">
                        <span class="price">$6.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="9">
                            <input type="hidden" name="item_name" value="Chocolate Cake">
                            <input type="hidden" name="item_price" value="6.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="menu-item" data-category="dessert">
                <div class="item-image">🍨</div>
                <div class="item-info">
                    <h3>Ice Cream Sundae</h3>
                    <p class="item-description">Vanilla ice cream with chocolate sauce and nuts</p>
                    <div class="item-footer">
                        <span class="price">$5.99</span>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="item_id" value="10">
                            <input type="hidden" name="item_name" value="Ice Cream Sundae">
                            <input type="hidden" name="item_price" value="5.99">
                            <button type="submit" name="add_to_cart" class="add-btn">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cart Modal -->
    <div class="cart-modal" id="cartModal">
        <div class="cart-content">
            <div class="cart-header">
                <h2>🛒 Your Cart</h2>
                <button class="close-btn" onclick="toggleCart()">&times;</button>
            </div>

            <?php if(empty($_SESSION['cart'])): ?>
                <div class="empty-cart">
                    <p>Your cart is empty</p>
                    <p>Add some delicious items!</p>
                </div>
            <?php else: ?>
                <?php foreach($_SESSION['cart'] as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                            <div>Quantity: <?php echo $item['quantity']; ?> × $<?php echo number_format($item['price'], 2); ?></div>
                        </div>
                        <div style="font-weight: bold; color: #ff1493;">
                            $<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="cart-total">
                    <h3>Total: $<?php echo number_format($cart_total, 2); ?></h3>
                    <button class="checkout-btn" onclick="checkout()">Proceed to Checkout</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function toggleCart() {
            const modal = document.getElementById('cartModal');
            modal.style.display = modal.style.display === 'flex' ? 'none' : 'flex';
        }

        function checkout() {
            <?php if(isset($_SESSION['user_id'])): ?>
                window.location.href = 'place_order.php';
            <?php else: ?>
                alert('Please login to place an order');
                window.location.href = 'login.php';
            <?php endif; ?>
        }

        function filterMenu(category) {
            const items = document.querySelectorAll('.menu-item');
            const buttons = document.querySelectorAll('.filter-btn');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            items.forEach(item => {
                if(category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Close modal when clicking outside
        document.getElementById('cartModal').addEventListener('click', function(e) {
            if(e.target === this) {
                toggleCart();
            }
        });
    </script>
</body>
</html>
