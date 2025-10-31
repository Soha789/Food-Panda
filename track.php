<?php
session_start();

// Redirect if not logged in
if(!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Please login to track your order');
        window.location.href = 'login.php';
    </script>";
    exit();
}

// Check if there's an order to track
$has_order = isset($_SESSION['current_order']);
$order = $has_order ? $_SESSION['current_order'] : null;

// Simulate order status progression
if($has_order && isset($_GET['update_status'])) {
    $statuses = ['Processing', 'Preparing', 'On the Way', 'Delivered'];
    $current_index = array_search($order['status'], $statuses);
    if($current_index < count($statuses) - 1) {
        $_SESSION['current_order']['status'] = $statuses[$current_index + 1];
        $order = $_SESSION['current_order'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Order - FoodExpress</title>
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

        .nav-links {
            display: flex;
            gap: 2rem;
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

        .container {
            max-width: 900px;
            margin: 3rem auto;
            padding: 0 5%;
        }

        .tracking-card {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            box-shadow: 0 15px 50px rgba(255, 20, 147, 0.2);
        }

        h1 {
            color: #ff1493;
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 2rem;
        }

        .order-info {
            background: linear-gradient(135deg, #ffe0e8 0%, #ffb3d9 100%);
            padding: 1.5rem;
            border-radius: 20px;
            margin-bottom: 3rem;
        }

        .order-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.8rem;
            color: #ff1493;
            font-weight: 600;
        }

        .order-info-row:last-child {
            margin-bottom: 0;
        }

        .status-timeline {
            position: relative;
            padding: 2rem 0;
        }

        .status-step {
            display: flex;
            align-items: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .status-step:last-child {
            margin-bottom: 0;
        }

        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            background: #ffe0e8;
            border: 4px solid #ffe0e8;
            position: relative;
            z-index: 2;
            transition: all 0.5s ease;
        }

        .status-step.active .status-icon {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            border-color: #ff1493;
            animation: pulse 2s infinite;
        }

        .status-step.completed .status-icon {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            border-color: #ff1493;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 20, 147, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(255, 20, 147, 0);
            }
        }

        .status-content {
            margin-left: 2rem;
            flex: 1;
        }

        .status-title {
            color: #ff1493;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 0.3rem;
        }

        .status-description {
            color: #666;
            font-size: 1rem;
        }

        .status-line {
            position: absolute;
            left: 39px;
            top: 80px;
            width: 4px;
            height: calc(100% - 80px);
            background: #ffe0e8;
        }

        .status-line.active {
            background: linear-gradient(180deg, #ff1493 0%, #ffe0e8 100%);
        }

        .order-items {
            background: #fff5f7;
            padding: 1.5rem;
            border-radius: 20px;
            margin-top: 2rem;
        }

        .order-items h3 {
            color: #ff1493;
            margin-bottom: 1rem;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid #ffe0e8;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .no-order {
            text-align: center;
            padding: 3rem;
        }

        .no-order-icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        .no-order h2 {
            color: #ff1493;
            margin-bottom: 1rem;
        }

        .no-order p {
            color: #666;
            margin-bottom: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
        }

        .simulate-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 2rem;
            transition: all 0.3s ease;
        }

        .simulate-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
        }

        @media (max-width: 768px) {
            .tracking-card {
                padding: 2rem 1.5rem;
            }

            .status-icon {
                width: 60px;
                height: 60px;
                font-size: 2rem;
            }

            .status-line {
                left: 29px;
                top: 60px;
                height: calc(100% - 60px);
            }

            .status-title {
                font-size: 1.2rem;
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
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="tracking-card">
            <?php if($has_order): ?>
                <h1>🚚 Track Your Order</h1>

                <div class="order-info">
                    <div class="order-info-row">
                        <span>Order ID:</span>
                        <span><?php echo $order['order_id']; ?></span>
                    </div>
                    <div class="order-info-row">
                        <span>Order Time:</span>
                        <span><?php echo date('M d, Y h:i A', strtotime($order['time'])); ?></span>
                    </div>
                    <div class="order-info-row">
                        <span>Delivery Address:</span>
                        <span><?php echo htmlspecialchars($order['address']); ?></span>
                    </div>
                    <div class="order-info-row">
                        <span>Total Amount:</span>
                        <span>$<?php echo number_format($order['total'], 2); ?></span>
                    </div>
                </div>

                <div class="status-timeline">
                    <?php
                    $statuses = [
                        'Processing' => ['icon' => '📝', 'title' => 'Order Received', 'desc' => 'We have received your order'],
                        'Preparing' => ['icon' => '👨‍🍳', 'title' => 'Preparing Food', 'desc' => 'Your delicious meal is being prepared'],
                        'On the Way' => ['icon' => '🏍️', 'title' => 'Out for Delivery', 'desc' => 'Your order is on its way to you'],
                        'Delivered' => ['icon' => '✅', 'title' => 'Delivered', 'desc' => 'Enjoy your meal!']
                    ];
                    
                    $current_status = $order['status'];
                    $status_keys = array_keys($statuses);
                    $current_index = array_search($current_status, $status_keys);
                    
                    foreach($statuses as $key => $status):
                        $index = array_search($key, $status_keys);
                        $is_active = $key === $current_status;
                        $is_completed = $index < $current_index;
                        $class = $is_active ? 'active' : ($is_completed ? 'completed' : '');
                    ?>
                        <div class="status-step <?php echo $class; ?>">
                            <?php if($index < count($statuses) - 1): ?>
                                <div class="status-line <?php echo ($is_completed || $is_active) ? 'active' : ''; ?>"></div>
                            <?php endif; ?>
                            <div class="status-icon"><?php echo $status['icon']; ?></div>
                            <div class="status-content">
                                <div class="status-title"><?php echo $status['title']; ?></div>
                                <div class="status-description"><?php echo $status['desc']; ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-items">
                    <h3>📦 Order Items</h3>
                    <?php foreach($order['items'] as $item): ?>
                        <div class="order-item">
                            <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['quantity']; ?></span>
                            <span style="color: #ff1493; font-weight: bold;">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if($current_status !== 'Delivered'): ?>
                    <button class="simulate-btn" onclick="updateStatus()">
                        🔄 Simulate Next Status (Demo)
                    </button>
                <?php endif; ?>

            <?php else: ?>
                <div class="no-order">
                    <div class="no-order-icon">📦</div>
                    <h2>No Active Orders</h2>
                    <p>You don't have any orders to track at the moment.</p>
                    <a href="menu.php" class="btn">Browse Menu</a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function updateStatus() {
            window.location.href = 'track.php?update_status=1';
        }

        // Auto-refresh every 30 seconds in real app
        // setInterval(() => location.reload(), 30000);
    </script>
</body>
</html>
