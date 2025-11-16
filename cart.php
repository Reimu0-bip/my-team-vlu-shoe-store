<<<<<<< HEAD
<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// ================== THÊM SẢN PHẨM VÀO GIỎ ==================
if (isset($_GET['add_id'])) {
    $id = (int)$_GET['add_id'];

    $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

// ================== XÓA SẢN PHẨM ==================
if (isset($_GET['remove_id'])) {
    $id = (int)$_GET['remove_id'];
    if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// ================== CẬP NHẬT SỐ LƯỢNG ==================
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if (isset($_SESSION['cart'][$id])) {
        if ($_GET['action'] === 'increase') {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($_GET['action'] === 'decrease') {
            $_SESSION['cart'][$id]['quantity']--;
            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    header("Location: cart.php");
    exit;
}

// ================== THANH TOÁN ==================
if (isset($_GET['checkout'])) {
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']); // xóa giỏ hàng
    }
    echo "<script>alert('Thanh toán thành công!'); window.location='cart.php';</script>";
    exit;
}

?>

<h2 class="cart-heading">🛒 Giỏ hàng của bạn</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p class="cart-empty">Giỏ hàng đang trống 😢</p>
<?php else: ?>
<div class="cart-table-wrapper">
    <table class="cart-table">
        <tr>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Xóa</th>
        </tr>

        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            // KHẮC PHỤC LỖI ĐƯỜNG DẪN ẢNH CÓ THƯ MỤC CON
            $imgPath = "anh/" . htmlspecialchars($item['image']); 
        ?>
        <tr>
            <td><img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
            <td>
                <a href="cart.php?action=decrease&id=<?= $id ?>">&#x2796</a>
                <?= $item['quantity'] ?>
                <a href="cart.php?action=increase&id=<?= $id ?>">&#x2795</a>
            </td>
            <td><?= number_format($subtotal, 0, ',', '.') ?> VNĐ</td>
            <td>
                <a href="cart.php?remove_id=<?= $id ?>" onclick="return confirm('Xóa sản phẩm này?')">&#x1F5D1</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<h3 class="cart-total">Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VNĐ</h3>

<div class="cart-button-wrapper">
    <a href="cart.php?checkout=1">
        <button>Thanh toán</button>
    </a>
</div>
<?php endif; ?>

<script src="js/dangky.js"></script>
<script src="js/giohang.js"></script>
<script src="js/taitrang.js"></script>

=======
<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// ================== THÊM SẢN PHẨM VÀO GIỎ ==================
if (isset($_GET['add_id'])) {
    $id = (int)$_GET['add_id'];

    $stmt = $conn->prepare("SELECT name, price, image FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if ($product) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

// ================== XÓA SẢN PHẨM ==================
if (isset($_GET['remove_id'])) {
    $id = (int)$_GET['remove_id'];
    if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit;
}

// ================== CẬP NHẬT SỐ LƯỢNG ==================
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    if (isset($_SESSION['cart'][$id])) {
        if ($_GET['action'] === 'increase') {
            $_SESSION['cart'][$id]['quantity']++;
        } elseif ($_GET['action'] === 'decrease') {
            $_SESSION['cart'][$id]['quantity']--;
            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    header("Location: cart.php");
    exit;
}

// ================== THANH TOÁN ==================
if (isset($_GET['checkout'])) {
    if (isset($_SESSION['cart'])) {
        unset($_SESSION['cart']); // xóa giỏ hàng
    }
    echo "<script>alert('Thanh toán thành công!'); window.location='cart.php';</script>";
    exit;
}

?>

<h2 class="cart-heading">🛒 Giỏ hàng của bạn</h2>

<?php if (empty($_SESSION['cart'])): ?>
    <p class="cart-empty">Giỏ hàng đang trống 😢</p>
<?php else: ?>
<div class="cart-table-wrapper">
    <table class="cart-table">
        <tr>
            <th>Ảnh</th>
            <th>Tên sản phẩm</th>
            <th>Giá</th>
            <th>Số lượng</th>
            <th>Thành tiền</th>
            <th>Xóa</th>
        </tr>

        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $item):
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
            
            // KHẮC PHỤC LỖI ĐƯỜNG DẪN ẢNH CÓ THƯ MỤC CON
            $imgPath = "anh/" . htmlspecialchars($item['image']); 
        ?>
        <tr>
            <td><img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($item['name']) ?>"></td>
            <td><?= htmlspecialchars($item['name']) ?></td>
            <td><?= number_format($item['price'], 0, ',', '.') ?> VNĐ</td>
            <td>
                <a href="cart.php?action=decrease&id=<?= $id ?>">&#x2796</a>
                <?= $item['quantity'] ?>
                <a href="cart.php?action=increase&id=<?= $id ?>">&#x2795</a>
            </td>
            <td><?= number_format($subtotal, 0, ',', '.') ?> VNĐ</td>
            <td>
                <a href="cart.php?remove_id=<?= $id ?>" onclick="return confirm('Xóa sản phẩm này?')">&#x1F5D1</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<h3 class="cart-total">Tổng cộng: <?= number_format($total, 0, ',', '.') ?> VNĐ</h3>

<div class="cart-button-wrapper">
    <a href="cart.php?checkout=1">
        <button>Thanh toán</button>
    </a>
</div>
<?php endif; ?>

<script src="js/dangky.js"></script>
<script src="js/giohang.js"></script>
<script src="js/taitrang.js"></script>

>>>>>>> 9ee000d7c9b32ce9c2f594ee786e9bdedb0b21a7
<?php include 'includes/footer.php'; ?>