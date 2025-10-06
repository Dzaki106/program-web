<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php?message=Silakan login terlebih dahulu!&status=error');
    exit();
}

$username = $_SESSION['username'];
$message = isset($_GET['message']) ? $_GET['message'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Game Store Man</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .dashboard-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
        }
        
        .dashboard-content {
            background: white;
            border-radius: 12px;
            padding: 3rem;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin-bottom: 2rem;
        }
        
        .dashboard-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .dashboard-title {
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .welcome-message {
            color: var(--gray);
            font-size: 1.2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        
        .stat-card {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid var(--secondary);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--gray);
            font-size: 1rem;
        }
        
        .dashboard-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .user-info {
            background: #e8f4fd;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid var(--secondary);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="container">
            <div class="dashboard-content">
                <!-- Notification -->
                <?php if ($message): ?>
                <div class="notification <?php echo $status; ?>" style="padding: 1rem 2rem; border-radius: 4px; margin-bottom: 2rem; background: <?php echo $status === 'success' ? '#27ae60' : '#e74c3c'; ?>; color: white;">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <?php endif; ?>
                
                <div class="dashboard-header">
                    <h1 class="dashboard-title">Dashboard</h1>
                    <p class="welcome-message">Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>!</p>
                </div>
                
                <div class="user-info">
                    <h3>Informasi User</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p><strong>Role:</strong> <?php echo $username === 'admin' ? 'Administrator' : 'User'; ?></p>
                    <p><strong>Login Time:</strong> <?php echo date('d-m-Y H:i:s'); ?></p>
                </div>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">8</div>
                        <div class="stat-label">Total Games</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">3</div>
                        <div class="stat-label">Kategori</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $username === 'admin' ? 'Unlimited' : 'Standard'; ?></div>
                        <div class="stat-label">Akses Fitur</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support</div>
                    </div>
                </div>
                
                <?php if ($username === 'admin'): ?>
                <div class="user-info">
                    <h3>Admin Panel</h3>
                    <p>Anda memiliki akses administrator. Fitur yang tersedia:</p>
                    <ul>
                        <li>Manajemen User</li>
                        <li>Manajemen Game</li>
                        <li>Laporan Penjualan</li>
                        <li>Pengaturan Sistem</li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <div class="dashboard-actions">
                    <a href="index.php" class="btn">Kembali ke Home</a>
                    <a href="index.php?message=Berhasil logout!&status=success" class="btn btn-secondary">Logout</a>
                    <?php if ($username === 'admin'): ?>
                    <a href="#" class="btn" style="background: #27ae60;">Admin Panel</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>