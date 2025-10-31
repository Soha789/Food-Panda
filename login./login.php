<?php
session_start();

// Redirect if already logged in
if(isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'index.php';</script>";
    exit();
}

// Handle login
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Simple validation - in real app, check against database
    if(!empty($email) && !empty($password)) {
        // For demo purposes, accept any login
        $_SESSION['user_id'] = rand(1000, 9999);
        $_SESSION['username'] = explode('@', $email)[0];
        $_SESSION['email'] = $email;
        
        echo "<script>
            alert('Login successful! Welcome back!');
            window.location.href = 'index.php';
        </script>";
        exit();
    } else {
        $error = "Please enter both email and password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FoodExpress</title>
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
            padding: 2rem;
        }

        .login-container {
            background: white;
            border-radius: 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 450px;
            width: 100%;
            padding: 3rem;
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        h1 {
            color: #ff1493;
            text-align: center;
            margin-bottom: 0.5rem;
            font-size: 2rem;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            color: #ff1493;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #ffe0e8;
            border-radius: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
            outline: none;
        }

        input:focus {
            border-color: #ff1493;
            box-shadow: 0 0 0 3px rgba(255, 20, 147, 0.1);
        }

        .btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #ff1493 0%, #ff69b4 100%);
            color: white;
            border: none;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn:hover {
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
            font-weight: 500;
        }

        .signup-link {
            text-align: center;
            margin-top: 1.5rem;
            color: #666;
        }

        .signup-link a {
            color: #ff1493;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .signup-link a:hover {
            color: #ff69b4;
        }

        .back-home {
            text-align: center;
            margin-top: 1rem;
        }

        .back-home a {
            color: #ff1493;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-home a:hover {
            color: #ff69b4;
        }

        .demo-note {
            background: #fff5f7;
            border: 2px dashed #ff69b4;
            padding: 1rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            text-align: center;
            color: #ff1493;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 2rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">🍔</div>
        <h1>Welcome Back!</h1>
        <p class="subtitle">Login to continue ordering</p>

        <div class="demo-note">
            📝 Demo Mode: Enter any email and password to login
        </div>

        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn">Login</button>
        </form>

        <div class="signup-link">
            Don't have an account? <a href="signup.php">Sign up here</a>
        </div>

        <div class="back-home">
            <a href="index.php">← Back to Home</a>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            if(!email || !password) {
                e.preventDefault();
                alert('Please fill in all fields!');
                return false;
            }
        });
    </script>
</body>
</html>
