<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// 0. KIỂM TRA ĐĂNG NHẬP
$logged_in_user_id = $_SESSION['user_id'] ?? 0;

if ($logged_in_user_id === 0) {
    // Nếu chưa đăng nhập, chuyển hướng về trang chủ
    header('Location: index.php'); 
    exit;
}

// 1. Kiểm tra tham số ID đơn hàng
$order_id = (int)($_GET['id'] ?? 0);
echo '<link rel="stylesheet" href="css/checkout.css">'; 

// ----------------------------------------------------
// --- LOGIC HIỂN THỊ DANH SÁCH ĐƠN HÀNG (MY ORDERS) ---
// ----------------------------------------------------
if ($order_id === 0) {
    
    $orders = [];
    
    // Truy vấn TẤT CẢ đơn hàng của người dùng hiện tại
    $stmt = $conn->prepare("SELECT id, total_amount, payment_method, status, order_date FROM orders WHERE user_id = ? ORDER BY order_date DESC");

    if (!$stmt) {
        die("Lỗi chuẩn bị truy vấn: " . $conn->error);
    }

    $stmt->bind_param("i", $logged_in_user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
    
    ?>

    <div class="order-summary">
        <h2>🛍️ Đơn hàng của tôi</h2>
        
        <?php if (empty($orders)): ?>
            <p style="text-align: center; padding: 20px; color: #777;">Bạn chưa có đơn hàng nào.</p>
            <a href="products.php" class="btn-return" style="background-color: #2ecc71;">BẮT ĐẦU MUA SẮM</a>
        <?php else: ?>
            <table class="details-table">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= date('d/m/Y', strtotime($order['order_date'])) ?></td>
                        <td><?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</td>
                        <td><span style="color: 
                            <?php 
                                // Logic màu trạng thái (giống như trong chi tiết)
                                if ($order['status'] == 'Processing') echo 'blue';
                                else if ($order['status'] == 'Delivered') echo 'green';
                                else if ($order['status'] == 'Failed') echo 'red';
                                else echo 'orange';
                            ?>; font-weight: bold;">
                            <?= htmlspecialchars($order['status']) ?>
                        </span></td>
                        <td>
                            <a href="order_detail.php?id=<?= $order['id'] ?>" class="btn-return" style="padding: 5px 10px; font-size: 14px; background-color: #3498db; display: inline;">Xem chi tiết</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <?php
    
} 

// ----------------------------------------------------
// --- LOGIC HIỂN THỊ CHI TIẾT ĐƠN HÀNG (ORDER DETAIL) ---
// ----------------------------------------------------
else {
    // Khởi tạo các biến cho chi tiết
    $order = null;
    $order_details = [];

    // 2. TRUY VẤN THÔNG TIN CHUNG TỪ BẢNG orders
    $stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();
    $stmt->close();

    if (!$order) {
        echo "<p class='alert-error' style='text-align:center;'>❌ Đơn hàng #{$order_id} không tồn tại.</p>";
        include 'includes/footer.php';
        exit;
    }

    // 2.5. KIỂM TRA QUYỀN TRUY CẬP (AUTHORIZATION)
    if ((int)$order['user_id'] !== $logged_in_user_id) {
        echo "<p class='alert-error' style='text-align:center;'>🚫 Bạn không có quyền xem đơn hàng này.</p>";
        include 'includes/footer.php';
        exit;
    }

    // 3. TRUY VẤN DANH SÁCH SẢN PHẨM TỪ BẢNG order_details
    $detail_stmt = $conn->prepare("SELECT product_name, price, quantity FROM order_details WHERE order_id = ?");
    $detail_stmt->bind_param("i", $order_id);
    $detail_stmt->execute();
    $details_result = $detail_stmt->get_result();
    while ($row = $details_result->fetch_assoc()) {
        $order_details[] = $row;
    }
    $detail_stmt->close();
    
    ?>

    <div class="order-summary">
        <h2>📜 Chi tiết Đơn hàng #<?= $order['id'] ?></h2>
        
        <div class="info-box">
            <h3>Thông tin Người nhận</h3>
            <p><strong>Tên:</strong> <?= htmlspecialchars($order['receiver_name']) ?></p>
            <p><strong>Điện thoại:</strong> <?= htmlspecialchars($order['phone_number']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order['address']) ?></p>
            <p><strong>Ngày đặt:</strong> <?= date('d/m/Y H:i:s', strtotime($order['order_date'])) ?></p>
            <?php
                // Hiển thị tên phương thức thanh toán thân thiện
                $methods = ['cod' => 'Thanh toán khi nhận hàng', 'momo' => 'Ví điện tử MOMO', 'vnpay' => 'VNPAY', 'bank_transfer' => 'Thẻ ngân hàng'];
                $payment_display = $methods[$order['payment_method']] ?? 'Không xác định';
            ?>
            <p><strong>Phương thức TT:</strong> <?= htmlspecialchars($payment_display) ?></p>
            <p><strong>Trạng thái:</strong> 
                <span style="color: 
                <?php 
                    if ($order['status'] == 'Processing') echo 'blue';
                    else if ($order['status'] == 'Delivered') echo 'green';
                    else if ($order['status'] == 'Failed') echo 'red';
                    else echo 'orange';
                ?>; font-weight: bold;">
                <?= htmlspecialchars($order['status']) ?>
                </span>
            </p>
        </div>
        
        <h3>Sản phẩm đã đặt</h3>
        <table class="details-table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Giá (VNĐ)</th>
                    <th>Số lượng</th>
                    <th>Thành tiền (VNĐ)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_details as $item): 
                    $subtotal = $item['price'] * $item['quantity'];
                ?>
                <tr>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= number_format($item['price'], 0, ',', '.') ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= number_format($subtotal, 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3 style="text-align: right; margin-top: 20px;">Tổng tiền: <span style="color: #e74c3c;"><?= number_format($order['total_amount'], 0, ',', '.') ?> VNĐ</span></h3>
        
        <a href="order_detail.php" class="btn-return" style="background-color: #2ecc71; display: block; width: fit-content; margin: 30px auto 10px auto;">← Quay lại Danh sách Đơn hàng</a>
        <a href="index.php" class="btn-return" style="background-color: #3498db; display: block; width: fit-content; margin: 0 auto;">← Quay lại Trang chủ</a>
    </div>

    <?php
}

include 'includes/footer.php'; 
?>