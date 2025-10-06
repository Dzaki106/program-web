<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Store Man</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .container {
            text-align: center;
            padding: 2rem;
        }
        
        .logo {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }
        
        .btn {
            display: inline-block;
            padding: 1rem 2rem;
            background-color: #e74c3c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: background-color 0.3s;
            margin: 0.5rem;
        }
        
        .btn:hover {
            background-color: #c0392b;
        }
        
        .btn-secondary {
            background-color: #3498db;
        }
        
        .btn-secondary:hover {
            background-color: #2980b9;
        }
        
        .demo-info {
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Game Store Man</div>
        <p class="subtitle">Toko Game Terpercaya</p>
        
        <div style="margin-bottom: 2rem;">
            <a href="login.php" class="btn">Login ke Store</a>
            <a href="store.php" class="btn btn-secondary">Lihat Store (Guest)</a>
        </div>
        
        <div class="demo-info">
            <h3>Demo Accounts:</h3>
            <p><strong>admin</strong> / password123</p>
            <p><strong>user</strong> / user123</p>
            <p><strong>gamer</strong> / game123</p>
        </div>
    </div>
</body>
</html>
