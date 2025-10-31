<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodExpress - Order Food Online</title>
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
            transform: translateY(-2px);
        }

        .user-info {
            color: white;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 25px;
        }

        .hero {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            padding: 4rem 5%;
            text-align: center;
            color: white;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
        }

        .hero p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .search-bar {
            max-width: 600px;
            margin: 0 auto;
            display: flex;
            gap: 1rem;
            background: white;
            padding: 0.5rem;
            border-radius: 50px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .search-bar input {
            flex: 1;
            border: none;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            border-radius: 50px;
            outline: none;
        }

        .search-bar button {
            background: #ff1493;
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-bar button:hover {
            background: #ff69b4;
            transform: scale(1.05);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 3rem 5%;
        }

        .section-title {
            font-size: 2.5rem;
            color: #ff1493;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: bold;
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .offer-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(255, 20, 147, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .offer-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255, 20, 147, 0.25);
        }

        .offer-image {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, #ff69b4 0%, #ff1493 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 4rem;
        }

        .offer-content {
            padding: 1.5rem;
        }

        .offer-content h3 {
            color: #ff1493;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .offer-content p {
            color: #666;
            line-height: 1.6;
        }

        .discount-badge {
            background: #ff1493;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            display: inline-block;
            margin-top: 1rem;
            font-weight: bold;
        }

        .restaurants-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
        }

        .restaurant-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(255, 20, 147, 0.15);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .restaurant-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(255, 20, 147, 0.25);
        }

        .restaurant-image {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, #ffe0e8 0%, #ffb3d9 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .restaurant-info {
            padding: 1.5rem;
        }

        .restaurant-info h3 {
            color: #ff1493;
            font-size: 1.3rem;
            margin-bottom: 0.5rem;
        }

        .restaurant-meta {
            display: flex;
            justify-content: space-between;
            color: #666;
            font-size: 0.9rem;
            margin-top: 1rem;
        }

        .rating {
            background: #ff1493;
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-weight: bold;
        }

        .footer {
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            text-align: center;
            padding: 2rem;
            margin-top: 4rem;
        }

        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2rem;
            }

            .nav-links {
                gap: 1rem;
            }

            .search-bar {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">🍔 FoodExpress</div>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="menu.php">Menu</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="user-info">👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                    <a href="track.php">Track Order</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h1>Delicious Food Delivered to Your Door</h1>
        <p>Order from the best restaurants in your area</p>
        <div class="search-bar">
            <input type="text" placeholder="Search for restaurants or dishes..." id="searchInput">
            <button onclick="searchFood()">Search</button>
        </div>
    </section>

    <div class="container">
        <h2 class="section-title">🎉 Special Offers</h2>
        <div class="offers-grid">
            <div class="offer-card" onclick="goToMenu()">
                <div class="offer-image">🍕</div>
                <div class="offer-content">
                    <h3>Pizza Mania</h3>
                    <p>Get 2 large pizzas with unlimited toppings</p>
                    <span class="discount-badge">50% OFF</span>
                </div>
            </div>
            <div class="offer-card" onclick="goToMenu()">
                <div class="offer-image">🍔</div>
                <div class="offer-content">
                    <h3>Burger Bonanza</h3>
                    <p>Buy 1 Get 1 Free on all burgers</p>
                    <span class="discount-badge">BOGO</span>
                </div>
            </div>
            <div class="offer-card" onclick="goToMenu()">
                <div class="offer-image">🍜</div>
                <div class="offer-content">
                    <h3>Noodle Night</h3>
                    <p>Flat 40% off on all Asian cuisine</p>
                    <span class="discount-badge">40% OFF</span>
                </div>
            </div>
        </div>

        <h2 class="section-title">🍽️ Featured Restaurants</h2>
        <div class="restaurants-grid">
            <div class="restaurant-card" onclick="goToMenu()">
                <div class="restaurant-image">🍕</div>
                <div class="restaurant-info">
                    <h3>Pizza Palace</h3>
                    <p>Italian, Pizza, Fast Food</p>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ 4.5</span>
                        <span>30-40 min</span>
                    </div>
                </div>
            </div>
            <div class="restaurant-card" onclick="goToMenu()">
                <div class="restaurant-image">🍔</div>
                <div class="restaurant-info">
                    <h3>Burger House</h3>
                    <p>Burgers, American, Fast Food</p>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ 4.7</span>
                        <span>25-35 min</span>
                    </div>
                </div>
            </div>
            <div class="restaurant-card" onclick="goToMenu()">
                <div class="restaurant-image">🍜</div>
                <div class="restaurant-info">
                    <h3>Asian Wok</h3>
                    <p>Chinese, Thai, Asian</p>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ 4.6</span>
                        <span>35-45 min</span>
                    </div>
                </div>
            </div>
            <div class="restaurant-card" onclick="goToMenu()">
                <div class="restaurant-image">🍛</div>
                <div class="restaurant-info">
                    <h3>Spice Garden</h3>
                    <p>Indian, Curry, Biryani</p>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ 4.8</span>
                        <span>40-50 min</span>
                    </div>
                </div>
            </div>
            <div class="restaurant-card" onclick="goToMenu()">
                <div class="restaurant-image">🍣</div>
                <div class="restaurant-info">
                    <h3>Sushi Station</h3>
                    <p>Japanese, Sushi, Seafood</p>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ 4.9</span>
                        <span>45-55 min</span>
                    </div>
                </div>
            </div>
            <div class="restaurant-card" onclick="goToMenu()">
                <div class="restaurant-image">🌮</div>
                <div class="restaurant-info">
                    <h3>Taco Fiesta</h3>
                    <p>Mexican, Tacos, Burritos</p>
                    <div class="restaurant-meta">
                        <span class="rating">⭐ 4.4</span>
                        <span>30-40 min</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2025 FoodExpress. All rights reserved. | Delivering happiness to your doorstep! 🚀</p>
    </footer>

    <script>
        function goToMenu() {
            window.location.href = 'menu.php';
        }

        function searchFood() {
            const searchTerm = document.getElementById('searchInput').value;
            if(searchTerm.trim()) {
                window.location.href = 'menu.php?search=' + encodeURIComponent(searchTerm);
            } else {
                alert('Please enter a search term');
            }
        }

        // Allow Enter key to search
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if(e.key === 'Enter') {
                searchFood();
            }
        });
    </script>
</body>
</html>
