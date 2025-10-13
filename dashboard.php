<?php
session_start();
require_once 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php?message=Silakan login terlebih dahulu!&status=error');
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$login_time = isset($_SESSION['login_time']) ? $_SESSION['login_time'] : time();

$message = '';
$status = '';

if (isset($_POST['add_game']) && $role === 'admin') {
    $name = $conn->real_escape_string($_POST['name']);
    $price = $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $description = $conn->real_escape_string($_POST['description']);
    $stock = $_POST['stock'];
    
    $stmt = $conn->prepare("INSERT INTO games (name, price, category, image_url, description, stock) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsssi", $name, $price, $category, $image_url, $description, $stock);
    
    if ($stmt->execute()) {
        $message = "Game berhasil ditambahkan!";
        $status = "success";
    } else {
        $message = "Error: " . $stmt->error;
        $status = "error";
    }
    $stmt->close();
}

if (isset($_POST['edit_game']) && $role === 'admin') {
    $id = $_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $price = $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $description = $conn->real_escape_string($_POST['description']);
    $stock = $_POST['stock'];
    
    $stmt = $conn->prepare("UPDATE games SET name=?, price=?, category=?, image_url=?, description=?, stock=? WHERE id=?");
    $stmt->bind_param("sdsssii", $name, $price, $category, $image_url, $description, $stock, $id);
    
    if ($stmt->execute()) {
        $message = "Game berhasil diupdate!";
        $status = "success";
    } else {
        $message = "Error: " . $stmt->error;
        $status = "error";
    }
    $stmt->close();
}

// DELETE - Delete game
if (isset($_GET['delete']) && $role === 'admin') {
    $id = $_GET['delete'];
    
    $stmt = $conn->prepare("DELETE FROM games WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $message = "Game berhasil dihapus!";
        $status = "success";
    } else {
        $message = "Error: " . $stmt->error;
        $status = "error";
    }
    $stmt->close();
}

$games_result = $conn->query("SELECT * FROM games ORDER BY created_at DESC");

if (isset($_GET['message']) && empty($message)) {
    $message = $_GET['message'];
    $status = $_GET['status'] ?? 'success';
}
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
        
        .session-info {
            background: #fff3cd;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            border-left: 4px solid #ffc107;
        }
        
        .crud-section {
            margin: 3rem 0;
        }
        
        .crud-header {
            display: flex;
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }
        
        .table th, .table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: var(--primary);
        }
        
        .table img {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .form-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .form-modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            position: relative;
        }
        
        .close-modal {
            position: absolute;
            right: 1rem;
            top: 1rem;
            font-size: 2rem;
            cursor: pointer;
            color: var(--gray);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
            color: var(--dark);
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #e1e5e9;
            border-radius: 6px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--secondary);
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
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
                    <p class="welcome-message">Selamat datang, <strong><?php echo htmlspecialchars($username); ?></strong>! (<?php echo $role; ?>)</p>
                </div>
                
                <div class="user-info">
                    <h3>Informasi User</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p><strong>Role:</strong> <?php echo ucfirst($role); ?></p>
                    <p><strong>Login Time:</strong> <?php echo date('d-m-Y H:i:s', $login_time); ?></p>
                </div>
                
                <div class="stats-grid">
                    <?php
                    // Get stats
                    $total_games = $conn->query("SELECT COUNT(*) as total FROM games")->fetch_assoc()['total'];
                    $total_categories = $conn->query("SELECT COUNT(DISTINCT category) as total FROM games")->fetch_assoc()['total'];
                    $total_stock = $conn->query("SELECT SUM(stock) as total FROM games")->fetch_assoc()['total'];
                    ?>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_games; ?></div>
                        <div class="stat-label">Total Games</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_categories; ?></div>
                        <div class="stat-label">Kategori</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $total_stock; ?></div>
                        <div class="stat-label">Total Stock</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number"><?php echo $role === 'admin' ? 'Full' : 'Limited'; ?></div>
                        <div class="stat-label">Akses</div>
                    </div>
                </div>

                <!-- CRUD Section for Games (Admin Only) -->
                <?php if ($role === 'admin'): ?>
                <div class="crud-section">
                    <div class="crud-header">
                        <h2>Manajemen Game</h2>
                        <button class="btn" onclick="openAddModal()">Tambah Game</button>
                    </div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Kategori</th>
                                <th>Stock</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($game = $games_result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?php echo htmlspecialchars($game['image_url']); ?>" alt="<?php echo htmlspecialchars($game['name']); ?>"></td>
                                <td><?php echo htmlspecialchars($game['name']); ?></td>
                                <td>Rp <?php echo number_format($game['price'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($game['category']); ?></td>
                                <td><?php echo $game['stock']; ?></td>
                                <td>
                                    <button class="btn btn-secondary" onclick="openEditModal(<?php echo $game['id']; ?>, '<?php echo addslashes($game['name']); ?>', <?php echo $game['price']; ?>, '<?php echo addslashes($game['category']); ?>', '<?php echo addslashes($game['image_url']); ?>', '<?php echo addslashes($game['description']); ?>', <?php echo $game['stock']; ?>)">Edit</button>
                                    <a href="?delete=<?php echo $game['id']; ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus game ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <div class="dashboard-actions">
                    <a href="index.php" class="btn">Kembali ke Home</a>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <div id="addModal" class="form-modal">
        <div class="form-modal-content">
            <span class="close-modal" onclick="closeAddModal()">&times;</span>
            <h2>Tambah Game Baru</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Nama Game</label>
                    <input type="text" name="name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" class="form-input" required step="0.01">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" class="form-input" required>
                        <option value="action">Action</option>
                        <option value="adventure">Adventure</option>
                        <option value="shooter">Shooter</option>
                        <option value="rpg">RPG</option>
                        <option value="sports">Sports</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">URL Gambar</label>
                    <input type="url" name="image_url" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-input" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-input" required>
                </div>
                <button type="submit" name="add_game" class="btn">Tambah Game</button>
            </form>
        </div>
    </div>

    <div id="editModal" class="form-modal">
        <div class="form-modal-content">
            <span class="close-modal" onclick="closeEditModal()">&times;</span>
            <h2>Edit Game</h2>
            <form method="POST" action="">
                <input type="hidden" name="id" id="edit_id">
                <div class="form-group">
                    <label class="form-label">Nama Game</label>
                    <input type="text" name="name" id="edit_name" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Harga</label>
                    <input type="number" name="price" id="edit_price" class="form-input" required step="0.01">
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="category" id="edit_category" class="form-input" required>
                        <option value="action">Action</option>
                        <option value="adventure">Adventure</option>
                        <option value="shooter">Shooter</option>
                        <option value="rpg">RPG</option>
                        <option value="sports">Sports</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">URL Gambar</label>
                    <input type="url" name="image_url" id="edit_image_url" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="edit_description" class="form-input" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" id="edit_stock" class="form-input" required>
                </div>
                <button type="submit" name="edit_game" class="btn">Update Game</button>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }
        
        function openEditModal(id, name, price, category, image_url, description, stock) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_category').value = category;
            document.getElementById('edit_image_url').value = image_url;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_stock').value = stock;
            document.getElementById('editModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('form-modal')) {
                closeAddModal();
                closeEditModal();
            }
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>

