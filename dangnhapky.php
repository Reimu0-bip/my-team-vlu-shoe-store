<?php
session_start();
require_once 'includes/db.php'; // file config kết nối DB

// --- LOGOUT ---
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $_SESSION = [];        // Xóa tất cả dữ liệu session
    session_destroy();      // Hủy session
    header('Location: index.php'); // Quay về trang chủ
    exit;
}

// Biến lỗi
$errors = [];

// Xử lý POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    if ($action === 'register') {
        // --- Đăng ký ---
        if ($username === '') $errors['username'] = 'Vui lòng nhập tên đăng nhập.';
        if ($password === '') $errors['password'] = 'Vui lòng nhập mật khẩu.';
        if (strlen($password) < 6) $errors['password'] = 'Mật khẩu tối thiểu 6 ký tự.';
        if ($password !== $confirm_password) $errors['confirm_password'] = 'Mật khẩu xác nhận không khớp.';

        // Kiểm tra username đã tồn tại
        if (empty($errors)) {
            $stmt = $conn->prepare('SELECT id FROM users WHERE username=?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $errors['username'] = 'Tài khoản đã tồn tại.';
            }
            $stmt->close();
        }

        // Lưu vào DB nếu không có lỗi
        if (empty($errors)) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $default_role = 'user'; // <<< Gán vai trò mặc định
            
            //Thêm cột role vào INSERT
            $stmt = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)'); 
            
            //Thêm $default_role vào bind_param
            $stmt->bind_param('sss', $username, $hashed, $default_role); 
            
            if ($stmt->execute()) {
                // Tự động đăng nhập sau khi đăng ký
                $_SESSION['loggedin'] = true;
                $_SESSION['id'] = $stmt->insert_id;
                $_SESSION['username'] = $username;
                
                //Lưu vai trò mặc định vào Session
                $_SESSION['user_role'] = $default_role; 
    
                // Chuyển về trang chủ
                header('Location: index.php');
                exit;
            } else {
                $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại.';
            }
            $stmt->close();
        }

    } elseif ($action === 'login') {
        // --- Đăng nhập ---
        if ($username === '') $errors['username'] = 'Vui lòng nhập tên đăng nhập.';
        if ($password === '') $errors['password'] = 'Vui lòng nhập mật khẩu.';

        if (empty($errors)) {
            //Thêm 'role' vào câu SELECT
            $stmt = $conn->prepare('SELECT id, password, role FROM users WHERE username=?');
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows === 1) {
                //Thêm $role vào bind_result
                $stmt->bind_result($id, $hashed_password, $role);
                $stmt->fetch();
                
                if (password_verify($password, $hashed_password)) {
                    $_SESSION['loggedin'] = true;
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $username;
                    
                    //Lưu vai trò vào SESSION
                    $_SESSION['user_role'] = $role;

                    // Chuyển về trang chủ
                    header('Location: index.php');
                    exit;
                } else {
                    $errors['password'] = 'Mật khẩu không đúng.';
                }
            } else {
                $errors['username'] = 'Tài khoản không tồn tại.';
            }
            $stmt->close();
        }
    }
}

// Nếu có lỗi (từ Đăng nhập hoặc Đăng ký), lưu vào session để hiển thị ở header
if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    $_SESSION['old_input'] = $_POST;
    header('Location: index.php');
    exit;
}

$conn->close();
?>