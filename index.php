<?php
session_start();

// Handle query string parameters
$message = isset($_GET['message']) ? $_GET['message'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['username']);
$username = $isLoggedIn ? $_SESSION['username'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Store Man</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container header-container">
            <div class="logo">Game Store Man</div>
            <nav>
                <ul class="nav-menu">
                    <li class="nav-item"><a href="index.php" class="nav-link">Home</a></li>
                    <li class="nav-item"><a href="#games" class="nav-link">Games</a></li>
                    <li class="nav-item"><a href="#cart" class="nav-link" id="cart-link">
                        Keranjang (<span id="cart-count">0</span>)
                    </a></li>
                    <li class="nav-item">
                        <?php if ($isLoggedIn): ?>
                            <a href="dashboard.php" class="nav-link">Dashboard (<?php echo htmlspecialchars($username); ?>)</a>
                        <?php else: ?>
                            <a href="login.php" class="nav-link">Login</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Notification from query string -->
    <?php if ($message): ?>
    <div class="notification <?php echo $status; ?>" style="position: fixed; top: 20px; right: 20px; padding: 1rem 2rem; border-radius: 4px; z-index: 1000; background: <?php echo $status === 'success' ? '#27ae60' : '#e74c3c'; ?>; color: white;">
        <?php echo htmlspecialchars($message); ?>
    </div>
    <script>
        setTimeout(() => {
            document.querySelector('.notification').remove();
        }, 3000);
    </script>
    <?php endif; ?>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <h1 class="hero-title">Game Store Man</h1>
            <p class="hero-subtitle">Temukan game terbaik dengan harga terbaik. Jelajahi koleksi kami dan temukan petualangan gaming berikutnya!</p>
            <a href="#games" class="btn">Lihat Game</a>
            
            <!-- Search Bar -->
            <div class="search-container">
                <input type="text" id="search-input" placeholder="Cari game..." class="search-input">
                <button id="search-btn" class="btn btn-secondary">Cari</button>
            </div>
        </div>
    </section>

    <!-- Games Section -->
    <section class="section" id="games">
        <div class="container">
            <h2 class="section-title">Game Yang Tersedia</h2>
            
            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <button class="btn filter-btn active" data-filter="all">Semua</button>
                <button class="btn filter-btn" data-filter="action">Action</button>
                <button class="btn filter-btn" data-filter="adventure">Adventure</button>
                <button class="btn filter-btn" data-filter="shooter">Shooter</button>
            </div>
            
            <div class="games-grid" id="games-container">
                <!-- Game cards will be loaded dynamically by JavaScript -->
            </div>
        </div>
    </section>

    <!-- Cart Section -->
    <section class="section" id="cart">
        <div class="container">
            <h2 class="section-title">Keranjang Belanja</h2>
            <div class="cart-container">
                <div id="cart-items" class="cart-items">
                    <!-- Cart items will be loaded dynamically by JavaScript -->
                </div>
                <div class="cart-summary">
                    <h3>Total: Rp <span id="cart-total">0</span></h3>
                    <button id="checkout-btn" class="btn">Checkout</button>
                    <button id="clear-cart-btn" class="btn btn-secondary">Kosongkan Keranjang</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <p>Game Store Man - Toko Game Terpercaya</p>
            <div class="footer-links">
                <a href="https://dzaki106.github.io/program-web" class="footer-link">Program Web</a>
                <a href="https://store.steampowered.com/" class="footer-link">Steam Store</a>
                <a href="#privacy" class="footer-link">Privacy Policy</a>
            </div>
            <p class="copyright">&copy; 2025 Game Store Man. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modal untuk Detail Game -->
    <div id="game-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div id="modal-body">
                <!-- Modal content will be loaded dynamically by JavaScript -->
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>