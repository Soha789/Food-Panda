<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - FoodExpress</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logout-container {
            background: white;
            border-radius: 30px;
            padding: 3rem;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .icon {
            font-size: 5rem;
            margin-bottom: 1rem;
        }

        h1 {
            color: #ff1493;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        p {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }

        .btn {
            display: inline-block;
            padding: 1rem 2.5rem;
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
            margin: 0.5rem;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 20, 147, 0.3);
        }

        .btn-secondary {
            background: white;
            color: #ff1493;
            border: 2px solid #ff1493;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="icon">👋</div>
        <h1>Logged Out Successfully!</h1>
        <p>Thank you for using FoodExpress. We hope to see you again soon!</p>
        <a href="index.php" class="btn">Go to Home</a>
        <a href="login.php" class="btn btn-secondary">Login Again</a>
    </div>

    <script>
        // Auto redirect after 3 seconds
        setTimeout(function() {
            window.location.href = 'index.php';
        }, 3000);
    </script>
</body>
</html>
