<?php
if (session_status() === PHP_SESSION_NONE) {
       session_start();
}
$username = $_SESSION['username'] ?? null;
$user_role = $_SESSION['user_role'] ?? 'guest'; // Lấy vai trò người dùng (Đúng: dùng 'user_role')

// Lấy lỗi từ session để hiển thị (từ logic đăng nhập/đăng ký)
$form_errors = $_SESSION['form_errors'] ?? [];
unset($_SESSION['form_errors']); // Xóa lỗi sau khi lấy

// Lấy dữ liệu cũ để điền lại form
$old_input = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);

// Kiểm tra xem form Đăng nhập/Đăng ký có nên mở ngay lập tức không (nếu có lỗi)
$is_login_form_open = isset($form_errors['username']) || isset($form_errors['password']) || isset($form_errors['general']);
$is_register_form_open = isset($form_errors['confirm_password']);

?>
<!doctype html>
<html lang="vi">
<head>
       <meta charset="utf-8" />
       <meta name="viewport" content="width=device-width,initial-scale=1" />
       <title>4T</title>
       <link rel="stylesheet" href="css/style.css">
       <link rel="stylesheet" href="css/dangky.css">
       <link rel="stylesheet" href="css/theloai.css">
       <link rel="stylesheet" href="css/giohang.css">
</head>
<body>
<header>

<div class="user-controls">
       <?php if (isset($form_errors['general'])): ?>
             <p class="error-message" style="color: red; margin-bottom: 10px;"><?= htmlspecialchars($form_errors['general']) ?></p>
       <?php endif; ?>
    
       <button id="loginBtn">
             <?= $username ? htmlspecialchars($username) : 'Đăng nhập'; ?>
       </button>

       <?php if ($username): ?>
             <a href="dangnhapky.php?action=logout" class="btn-logout">Đăng xuất</a>
       <?php endif; ?>
</div>

       <div id="loginForm" class="custom-modal <?= $is_login_form_open ? '' : 'hidden' ?>">
          <div class="modal-content-custom">
             <span id="closeLogin" class="closeLoginBtn">&times;</span>
             <h2>Đăng nhập</h2>
             <form action="dangnhapky.php" method="POST">
                   <?php if (isset($form_errors['username'])): ?>
                         <p class="error-field" style="color: red; font-size: 0.9em;"><?= htmlspecialchars($form_errors['username']) ?></p>
                   <?php endif; ?>
                 <input type="text" name="username" placeholder="Tên đăng nhập" 
                            value="<?= htmlspecialchars($old_input['username'] ?? '') ?>" required><br>
        
                   <?php if (isset($form_errors['password'])): ?>
                         <p class="error-field" style="color: red; font-size: 0.9em;"><?= htmlspecialchars($form_errors['password']) ?></p>
                   <?php endif; ?>
                 <input type="password" name="password" placeholder="Mật khẩu" required><br>
        
                 <button type="submit" name="action" value="login">Đăng nhập</button>
             </form>
             <p>Bạn chưa có tài khoản? 
                 <button id="goToRegister" type="button" class="link-btn">Đăng ký</button>
             </p>
          </div>
       </div>

       <div id="registerForm" class="custom-modal <?= $is_register_form_open ? '' : 'hidden' ?>">
          <div class="modal-content-custom">
             <span id="closeRegister" class="closeLoginBtn">&times;</span>
             <h2>Đăng ký</h2>
             <form id="registerFormContent" action="dangnhapky.php" method="POST">
                   <?php if (isset($form_errors['username']) && $is_register_form_open): ?>
                         <p class="error-field" style="color: red; font-size: 0.9em;"><?= htmlspecialchars($form_errors['username']) ?></p>
                   <?php endif; ?>
                 <input type="text" id="newUsername" name="username" placeholder="Tên đăng nhập" 
                            value="<?= htmlspecialchars($old_input['username'] ?? '') ?>" required><br>

                   <?php if (isset($form_errors['password']) && $is_register_form_open): ?>
                         <p class="error-field" style="color: red; font-size: 0.9em;"><?= htmlspecialchars($form_errors['password']) ?></p>
                   <?php endif; ?>
                 <input type="password" id="newPassword" name="password" placeholder="Mật khẩu" required><br>
        
                   <?php if (isset($form_errors['confirm_password'])): ?>
                         <p class="error-field" style="color: red; font-size: 0.9em;"><?= htmlspecialchars($form_errors['confirm_password']) ?></p>
                   <?php endif; ?>
                 <input type="password" id="confirmPassword" name="confirm_password" placeholder="Xác nhận mật khẩu" required><br>
        
                 <button type="submit" name="action" value="register">Đăng ký</button>
             </form>
             <p>Đã có tài khoản? 
                 <button id="backToLogin" type="button" class="link-btn">Quay lại đăng nhập</button>
             </p>
          </div>
       </div>

<nav>
       <ul>
             <?php if ($user_role === 'admin'): ?>
                   <li><a href="admin/dashboard.php" style="color: #f39c12; font-weight: bold;">Thống kê</a></li>
             <?php endif; ?>
             <li><a href="index.php">Trang chủ</a></li>             
             <li><a href="products.php">Sản phẩm</a></li>
             <li><a href="hang.php">Hãng</a>
                   <ul class="dropdown">
                         <?php
                         $shoe_brands = ['Nike', 'Adidas', 'Puma', '4T'];
                         foreach ($shoe_brands as $brand) {
                                echo "<li><a href='hang.php?name=" . urlencode($brand) . "'>$brand</a></li>";
                         }
                         ?>
                   </ul>
             </li>
             <li><a href="cart.php">Giỏ hàng 
          </a></li>
       </ul>
</nav>

</header>
<main class="container">