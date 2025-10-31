<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Please login to place an order');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Redirect if cart is empty
if(empty($_SESSION['cart'])) {
    echo "<script>
        alert('Your cart is empty!');
        window.location.href = 'menu.php';
    </script>";
    exit();
}

// Calculate totals
$subtotal = 0;
foreach($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = 2.99;
$total = $subtotal + $delivery_fee;

// Handle order placement
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['place_order'])) {
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $payment_method = $_POST['payment_method'];
    
    if(!empty($address) && !empty($phone)) {
        // Generate order ID
        $order_id = 'ORD' . rand(10000, 99999);
        
        // Store order in session
        $_SESSION['current_order'] = array(
            'order_id' => $order_id,
            'items' => $_SESSION['cart'],
            'total' => $total,
            'address' => $address,
            'phone' => $phone,
            'payment_method' => $payment_method,
            'status' => 'Processing',
            'time' => date('Y-m-d H:i:s')
        );
        
        // Clear cart
        $_SESSION['cart'] = array();
        
        echo "<script>
            alert('Order placed successfully! Order ID: $order_id');
            window.location.href = 'track.php';
        </script>";
        exit();
    } else {
        $error = "Please fill in all required fields!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - FoodExpress</title>
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
            cursor: pointer;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 2rem;
            font-weight: 500;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 5%;
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 2rem;
        }

        .checkout-form {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(255, 20, 147, 0.15);
        }

        h2 {
            color: #ff1493;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: #ff1493;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        input, textarea, select {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ffe0e8;
            border-radius: 15px;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s ease;
        }

        input:focus, textarea:focus, select:focus {
            border-color: #ff1493;
            box-shadow: 0 0 0 3px rgba(255, 20, 147, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .payment-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .payment-option {
            border: 2px solid #ffe0e8;
            border-radius: 15px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .payment-option:hover {
            border-color: #ff69b4;
        }

        .payment-option input[type="radio"] {
            display: none;
        }

        .payment-option input[type="radio"]:checked + label {
            color: #ff1493;
            font-weight: bold;
        }

        .payment-option.selected {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            border-color: #ff1493;
        }

        .order-summary {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(255, 20, 147, 0.15);
            height: fit-content;
            position: sticky;
            top: 2rem;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #ffe0e8;
        }

        .summary-item:last-child {
            border-bottom: none;
        }

        .item-name {
            color: #333;
            font-weight: 500;
        }

        .item-price {
            color: #ff1493;
            font-weight: bold;
        }

        .total-section {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-top: 1rem;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .total-row.final {
            font-size: 1.5rem;
            font-weight: bold;
            padding-top: 1rem;
            border-top: 2px solid rgba(255, 255, 255, 0.3);
        }

        .place-order-btn {
            width: 100%;
            padding: 1.2rem;
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
        }

        .error {
            background: #ffe0e8;
            color: #ff1493;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        @media (max-width: 968px) {
            .container {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo" onclick="window.location.href='index.php'">🍔 FoodExpress</div>
            <div class="nav-links">
                <a href="menu.php">← Back to Menu</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="checkout-form">
            <h2>🚚 Delivery Details</h2>

            <?php if(isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="" id="orderForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number" required>
                </div>

                <div class="form-group">
                    <label for="address">Delivery Address</label>
                    <textarea id="address" name="address" placeholder="Enter your complete delivery address" required></textarea>
                </div>

                <div class="form-group">
                    <label>Payment Method</label>
                    <div class="payment-options">
                        <div class="payment-option" onclick="selectPayment('cod', this)">
                            <input type="radio" name="payment_method" value="cod" id="cod" checked>
                            <label for="cod">
                                <div style="font-size: 2rem;">💵</div>
                                <div>Cash on Delivery</div>
                            </label>
                        </div>
                        <div class="payment-option" onclick="selectPayment('online', this)">
                            <input type="radio" name="payment_method" value="online" id="online">
                            <label for="online">
                                <div style="font-size: 2rem;">💳</div>
                                <div>Online Payment</div>
                            </label>
                        </div>
                    </div>
                </div>

                <button type="submit" name="place_order" class="place-order-btn">Place Order 🎉</button>
            </form>
        </div>

        <div class="order-summary">
            <h2>📋 Order Summary</h2>

            <?php foreach($_SESSION['cart'] as $item): ?>
                <div class="summary-item">
                    <div>
                        <div class="item-name"><?php echo htmlspecialchars($item['name']); ?></div>
                        <div style="color: #666; font-size: 0.9rem;">Qty: <?php echo $item['quantity']; ?></div>
                    </div>
                    <div class="item-price">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                </div>
            <?php endforeach; ?>

            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>$<?php echo number_format($subtotal, 2); ?></span>
                </div>
                <div class="total-row">
                    <span>Delivery Fee:</span>
                    <span>$<?php echo number_format($delivery_fee, 2); ?></span>
                </div>
                <div class="total-row final">
                    <span>Total:</span>
                    <span>$<?php echo number_format($total, 2); ?></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectPayment(method, element) {
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('selected');
            });
            element.classList.add('selected');
            document.getElementById(method).checked = true;
        }

        // Set initial selection
        document.querySelector('.payment-option').classList.add('selected');

        document.getElementById('orderForm').addEventListener('submit', function(e) {
            const address = document.getElementById('address').value.trim();
            const phone = document.getElementById('phone').value.trim();
            
            if(!address || !phone) {
                e.preventDefault();
                alert('Please fill in all required fields!');
                return false;
            }
        });
    </script>
</body>
</html>
