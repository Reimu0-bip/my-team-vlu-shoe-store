<?php
session_start();
include 'includes/db.php'; // Kết nối cơ sở dữ liệu
include 'includes/header.php';

// Kiểm tra giỏ hàng và chuyển hướng nếu trống
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Khởi tạo biến để lưu trạng thái và thông tin đơn hàng sau khi xử lý
$order_placed = false;
$order_info = [];
$total_amount = 0;
$error_message = '';

// Tính tổng tiền (nên tính lại trên server)
foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// ================== XỬ LÝ THANH TOÁN (KHI FORM ĐƯỢC SUBMIT) ==================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Lấy và làm sạch dữ liệu từ form
    $receiver_name = trim($_POST['receiver_name'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $payment_method = $_POST['payment_method'] ?? '';

    // 2. Xác thực cơ bản
    if (empty($receiver_name) || empty($phone_number) || empty($address) || empty($payment_method)) {
        $error_message = "Vui lòng điền đầy đủ thông tin người nhận và chọn phương thức thanh toán.";
    } elseif (!is_numeric($phone_number)) {
        $error_message = "Số điện thoại không hợp lệ.";
    } else {
        try {
            // Bắt đầu giao dịch (transaction) để đảm bảo tính toàn vẹn dữ liệu
            $conn->begin_transaction();
            $order_status = 'Pending'; // Trạng thái mặc định

            // 3. Xử lý Logic Thanh toán theo Phương thức
            switch ($payment_method) {
                case 'cod':
                    // COD: Thanh toán khi nhận hàng. Đơn hàng chờ xác nhận.
                    $order_status = 'Pending';
                    $transaction_id = 'COD-' . time();
                    break;
                case 'momo':
                case 'vnpay':
                case 'bank_transfer':
                    // Các phương thức thanh toán trực tuyến/chuyển khoản
                    // Trong thực tế: Chuyển hướng người dùng đến cổng thanh toán
                    // Sau khi cổng trả về (thường là một file callback), trạng thái mới được cập nhật.
                    
                    // Ở đây, ta chỉ mô phỏng thành công ngay lập tức để đơn giản hóa:
                    $transaction_successful = true; // Giả sử API thanh toán thành công
                    
                    if ($transaction_successful) {
                        $order_status = 'Processing'; // Đang xử lý
                        $transaction_id = strtoupper($payment_method) . '-' . time();
                    } else {
                        throw new Exception("Thanh toán qua {$payment_method} thất bại. Vui lòng thử lại.");
                    }
                    break;
                default:
                    throw new Exception("Phương thức thanh toán không hợp lệ.");
            }

            // 4. LƯU ĐƠN HÀNG VÀO BẢNG 'orders'
            $user_id = $_SESSION['user_id'] ?? 0; // Giả sử user_id là 0 nếu chưa đăng nhập
            $stmt = $conn->prepare("INSERT INTO orders (user_id, receiver_name, phone_number, address, total_amount, payment_method, status, transaction_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssdsss", $user_id, $receiver_name, $phone_number, $address, $total_amount, $payment_method, $order_status, $transaction_id);
            $stmt->execute();
            $order_id = $conn->insert_id;
            $stmt->close();

            // 5. LƯU CHI TIẾT ĐƠN HÀNG VÀO BẢNG 'order_details'
            $detail_stmt = $conn->prepare("INSERT INTO order_details (order_id, product_name, price, quantity) VALUES (?, ?, ?, ?)");
            
            foreach ($_SESSION['cart'] as $item) {
                $detail_stmt->bind_param("isdi", $order_id, $item['name'], $item['price'], $item['quantity']);
                $detail_stmt->execute();
            }
            $detail_stmt->close();
            
            // 6. Hoàn tất giao dịch và XÓA GIỎ HÀNG
            $conn->commit();
            unset($_SESSION['cart']); 
            
            // Chuẩn bị thông tin hiển thị trên trang 'Đặt hàng thành công'
            $order_placed = true;
            $order_info = [
                'order_id' => $order_id,
                'receiver_name' => $receiver_name,
                'phone_number' => $phone_number,
                'address' => $address,
                'total_amount' => $total_amount,
                'payment_method' => $payment_method
            ];

        } catch (Exception $e) {
            // Nếu có lỗi, rollback để hủy các thao tác INSERT
            $conn->rollback();
            $error_message = "Lỗi xử lý đơn hàng: " . $e->getMessage();
        }
    }
}
?>
<link rel="stylesheet" href="css/checkout.css">
<?php if ($order_placed): ?>
    <div class="success-container">
        <h2>🎉 ĐẶT HÀNG THÀNH CÔNG 🎉</h2>
        <div class="success-box">
            <p><strong>Mã Đơn hàng:</strong> #<?= $order_info['order_id'] ?></p>
            <h3>THÔNG TIN NGƯỜI NHẬN</h3>
            <p><strong>Tên:</strong> <?= htmlspecialchars($order_info['receiver_name']) ?></p>
            <p><strong>Số điện thoại:</strong> <?= htmlspecialchars($order_info['phone_number']) ?></p>
            <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($order_info['address']) ?></p>
            <p><strong>Tổng tiền:</strong> <?= number_format($order_info['total_amount'], 0, ',', '.') ?> VNĐ</p>
            <p><strong>Phương thức:</strong> 
                <?php 
                    $methods = ['cod' => 'Thanh toán khi nhận hàng', 'momo' => 'Ví điện tử MOMO', 'vnpay' => 'VNPAY', 'bank_transfer' => 'Thẻ ngân hàng'];
                    echo $methods[$order_info['payment_method']] ?? 'Không xác định';
                ?>
            </p>
        </div>
        <a href="order_detail.php?id=<?= $order_info['order_id'] ?>" class="btn-return">XEM LẠI ĐƠN HÀNG</a>
        <a href="index.php" class="btn-return" style="background-color: #2ecc71;">TIẾP TỤC MUA HÀNG</a>
    </div>

<?php else: ?>
    <div class="checkout-container">
        <h2>Thông tin mua bán</h2>
        
        <?php if ($error_message): ?>
            <p class="alert-error"><?= $error_message ?></p>
        <?php endif; ?>

        <form method="POST" action="checkout.php">
            
            <h3>THÔNG TIN NGƯỜI NHẬN</h3>
            <div class="form-group">
                <label for="receiver_name">Tên:</label>
                <input type="text" id="receiver_name" name="receiver_name" value="<?= htmlspecialchars($_POST['receiver_name'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_number">Số điện thoại:</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($_POST['phone_number'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea id="address" name="address" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
            </div>

            <h3>PHƯƠNG THỨC THANH TOÁN</h3>
            <div class="payment-options">
                <div class="form-group">
                    <input type="radio" id="cod" name="payment_method" value="cod" required <?= (($_POST['payment_method'] ?? '') === 'cod') ? 'checked' : '' ?>>
                    <label for="cod" style="display: inline;">Thanh toán khi nhận hàng</label>
                </div>
                <div class="form-group">
                    <input type="radio" id="momo" name="payment_method" value="momo" <?= (($_POST['payment_method'] ?? '') === 'momo') ? 'checked' : '' ?>>
                    <label for="momo" style="display: inline;">Thanh toán bằng ví điện tử MOMO</label>
                </div>
                <div class="form-group">
                    <input type="radio" id="vnpay" name="payment_method" value="vnpay" <?= (($_POST['payment_method'] ?? '') === 'vnpay') ? 'checked' : '' ?>>
                    <label for="vnpay" style="display: inline;">Thanh toán bằng VNPAY</label>
                </div>
                <div class="form-group">
                    <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" <?= (($_POST['payment_method'] ?? '') === 'bank_transfer') ? 'checked' : '' ?>>
                    <label for="bank_transfer" style="display: inline;">Thanh toán bằng thẻ ngân hàng</label>
                </div>
            </div>
            
            <button type="submit" class="btn-checkout">MUA HÀNG</button>
        </form>
        
        <p style="text-align: right; margin-top: 15px;">Tổng tiền cần thanh toán: <strong><?= number_format($total_amount, 0, ',', '.') ?> VNĐ</strong></p>
    </div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>