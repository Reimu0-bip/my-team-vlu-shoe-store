<<<<<<< HEAD
<?php
session_start();
include '../includes/db.php'; 

// Bảo vệ Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// --- LẤY DỮ LIỆU THỐNG KÊ ---

// 1. Tổng số sản phẩm
$count_products = $conn->query("SELECT COUNT(id) AS total_products FROM products")->fetch_assoc()['total_products'];

// 2. Tổng số người dùng
$count_users = $conn->query("SELECT COUNT(id) AS total_users FROM users")->fetch_assoc()['total_users'];

// 3. Sản phẩm có giá cao nhất
$most_expensive = $conn->query("SELECT name, price FROM products ORDER BY price DESC LIMIT 1")->fetch_assoc();

// Đóng kết nối DB (Nếu không dùng nữa)
$conn->close();
?>
<div class="container" style="padding: 40px;">
    <h2 style="text-align: center;">Bảng Điều Khiển Quản Trị</h2>
    <p style="text-align: center;">Chào mừng Admin, bạn đang ở khu vực quản trị.</p>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        
        <div class="stat-card" style="padding: 20px; border: 1px solid #ddd; border-left: 5px solid #3498db;">
            <h3>Tổng Sản Phẩm</h3>
            <h1><?= number_format($count_products) ?></h1>
            <p>Sản phẩm đang được bán.</p>
        </div>

        <div class="stat-card" style="padding: 20px; border: 1px solid #ddd; border-left: 5px solid #2ecc71;">
            <h3>Tổng Người Dùng</h3>
            <h1><?= number_format($count_users) ?></h1>
            <p>Tài khoản người dùng đã đăng ký.</p>
        </div>

        <div class="stat-card" style="padding: 20px; border: 1px solid #ddd; border-left: 5px solid #f1c40f;">
            <h3>Giá Cao Nhất</h3>
            <h4><?= htmlspecialchars($most_expensive['name']) ?></h4>
            <p><?= number_format($most_expensive['price'], 0, ',', '.') ?> VNĐ</p>
        </div>
    </div>
</div>

=======
<?php
session_start();
include '../includes/db.php'; 

// Bảo vệ Admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// --- LẤY DỮ LIỆU THỐNG KÊ ---

// 1. Tổng số sản phẩm
$count_products = $conn->query("SELECT COUNT(id) AS total_products FROM products")->fetch_assoc()['total_products'];

// 2. Tổng số người dùng
$count_users = $conn->query("SELECT COUNT(id) AS total_users FROM users")->fetch_assoc()['total_users'];

// 3. Sản phẩm có giá cao nhất
$most_expensive = $conn->query("SELECT name, price FROM products ORDER BY price DESC LIMIT 1")->fetch_assoc();

// Đóng kết nối DB (Nếu không dùng nữa)
$conn->close();
?>
<div class="container" style="padding: 40px;">
    <h2 style="text-align: center;">Bảng Điều Khiển Quản Trị</h2>
    <p style="text-align: center;">Chào mừng Admin, bạn đang ở khu vực quản trị.</p>

    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-top: 30px;">
        
        <div class="stat-card" style="padding: 20px; border: 1px solid #ddd; border-left: 5px solid #3498db;">
            <h3>Tổng Sản Phẩm</h3>
            <h1><?= number_format($count_products) ?></h1>
            <p>Sản phẩm đang được bán.</p>
        </div>

        <div class="stat-card" style="padding: 20px; border: 1px solid #ddd; border-left: 5px solid #2ecc71;">
            <h3>Tổng Người Dùng</h3>
            <h1><?= number_format($count_users) ?></h1>
            <p>Tài khoản người dùng đã đăng ký.</p>
        </div>

        <div class="stat-card" style="padding: 20px; border: 1px solid #ddd; border-left: 5px solid #f1c40f;">
            <h3>Giá Cao Nhất</h3>
            <h4><?= htmlspecialchars($most_expensive['name']) ?></h4>
            <p><?= number_format($most_expensive['price'], 0, ',', '.') ?> VNĐ</p>
        </div>
    </div>
</div>

>>>>>>> 9ee000d7c9b32ce9c2f594ee786e9bdedb0b21a7
