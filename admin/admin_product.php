<?php
session_start();
// Đảm bảo đường dẫn này trỏ đúng về file db.php (đi ra ngoài 1 cấp)
include '../includes/db.php'; 

// --- BƯỚC 1: BẢO VỆ ADMIN (BẮT BUỘC) ---
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
  header("Location: ../index.php"); 
  exit();
}
// ----------------------------------------

// Khởi tạo các biến
$message = '';
$product_to_edit = null;
$form_title = "Thêm Sản Phẩm Mới";

// ------------------------------------------------------------------
// --- BƯỚC 2: XỬ LÝ LẤY DỮ LIỆU ĐỂ SỬA (GET action=edit) ---
// ------------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
  $product_id = intval($_GET['id']);
  $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $product_to_edit = $result->fetch_assoc();
  $stmt->close();

  if ($product_to_edit) {
    $form_title = "Chỉnh Sửa Sản Phẩm: " . htmlspecialchars($product_to_edit['name']);
  } else {
    $message = "Không tìm thấy sản phẩm cần sửa.";
  }
}


// ------------------------------------------------------------------
// --- BƯỚC 3: XỬ LÝ HÀNH ĐỘNG POST (THÊM HOẶC SỬA) ---
// ------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
  $action_type = $_POST['action'];
  $name = trim($_POST['name'] ?? '');
  $price = $_POST['price'] ?? 0;
  $brand = trim($_POST['brand'] ?? '');
  $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
  $current_image = $_POST['current_image'] ?? ''; 

    // CHUYỂN TÊN HÃNG THÀNH TÊN THƯ MỤC HỢP LỆ (Không dấu, không khoảng trắng)
    // Ví dụ: "Giày Adidas 4T" -> "Giay_Adidas_4T"
    $folder_name = preg_replace('/[^A-Za-z0-9_]/', '_', str_replace(' ', '_', $brand));
    
  // Xử lý Upload Ảnh
  $image_name_to_save = $current_image; // Mặc định giữ ảnh cũ khi sửa
    $success = false;

  if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        // Tên file gốc
    $original_filename = basename($_FILES['image']['name']);
        
        // 1. TẠO ĐƯỜNG DẪN THƯ MỤC ĐÍCH ĐẾN (từ admin_product.php, đi ra ngoài 1 cấp, vào 'anh')
        $upload_dir = "../anh/" . $folder_name;
        
        // 2. TẠO THƯ MỤC NẾU CHƯA TỒN TẠI
        if (!is_dir($upload_dir)) {
            // Quyền 0777 là quyền tối đa, nên dùng 0755 nếu máy chủ cho phép
            mkdir($upload_dir, 0777, true); 
        }
        
        // 3. ĐƯỜNG DẪN ĐÍCH ĐỂ LƯU FILE VẬT LÝ
        $target_path = $upload_dir . "/" . $original_filename;
        
        // 4. THỰC HIỆN DI CHUYỂN VÀ CẬP NHẬT TÊN ẢNH LƯU VÀO CSDL
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
            // Đường dẫn lưu vào CSDL (Ví dụ: "Adidas/shoe_name.jpg")
            $image_name_to_save = $folder_name . "/" . $original_filename;
        } else {
            $message = "Lỗi khi di chuyển file ảnh.";
            // Không thực hiện truy vấn SQL nếu lỗi file
            $success = false; 
            goto end_of_post; 
        }
  }
  
  // --- THỰC HIỆN TRUY VẤN SQL ---
  if ($action_type === 'add') {
    // THÊM SẢN PHẨM MỚI (INSERT)
    $stmt = $conn->prepare("INSERT INTO products (name, price, brand, image) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $name, $price, $brand, $image_name_to_save);
    
  } elseif ($action_type === 'edit' && $product_id > 0) {
    // SỬA SẢN PHẨM (UPDATE)
    $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, brand = ?, image = ? WHERE id = ?");
    $stmt->bind_param("sdssi", $name, $price, $brand, $image_name_to_save, $product_id);
  }
  
  // Thực thi lệnh SQL
  if (isset($stmt)) {
    $success = $stmt->execute();
    $message = $success ? ($action_type === 'add' ? "Thêm sản phẩm thành công!" : "Cập nhật sản phẩm thành công!") 
                           : "Lỗi SQL: " . $stmt->error;
    $stmt->close();
  }
  
  end_of_post: // Điểm nhảy cho lệnh goto khi lỗi file
  
  // Chuyển hướng sau khi POST/REDIRECT/GET
  if ($success) {
    header("Location: ../products.php?status=" . ($action_type === 'add' ? 'added' : 'updated'));
  } else {
    header("Location: admin_product.php?status=error&msg=" . urlencode($message));
  }
  exit();
}


// ------------------------------------------------------------------
// --- BƯỚC 4: XỬ LÝ XÓA SẢN PHẨM (GET id) ---
// ------------------------------------------------------------------
if (isset($_GET['id']) && !isset($_GET['action'])) { // Xử lý DELETE khi chỉ có ID
    $product_id = intval($_GET['id']); 
    
    // --- Bỏ qua logic lấy tên ảnh và hàm unlink() ở đây ---

    // Xóa khỏi CSDL (Chỉ giữ lại phần này)
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        header("Location: ../products.php?status=deleted");
    } else {
        header("Location: ../products.php?status=delete_error");
    }
    $stmt->close();
    exit();
    
    // Xóa khỏi CSDL
  $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
  $stmt->bind_param("i", $product_id);
 
  if ($stmt->execute()) {
    header("Location: ../products.php?status=deleted");
  } else {
    header("Location: ../products.php?status=delete_error");
  }
  $stmt->close();
  exit();
}

// ------------------------------------------------------------------
// --- BƯỚC 5: HIỂN THỊ THÔNG BÁO TỪ URL (nếu có lỗi) ---
// ------------------------------------------------------------------
if (isset($_GET['status']) && $_GET['status'] == 'error' && isset($_GET['msg'])) {
  $message = htmlspecialchars($_GET['msg']);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quản lý Sản Phẩm</title>
  <link rel="stylesheet" href="admin_style.css"> 
</head>
<body>
  <main class="container">
    <?php if (!empty($message)): ?>
           <p class="error-msg"><?php echo $message; ?></p>
    <?php endif; ?>

    <h2><?php echo $form_title; ?></h2>
     
    <form action="admin_product.php" method="POST" enctype="multipart/form-data" class="form-add-product"> 
     
           <input type="hidden" name="action" value="<?php echo $product_to_edit ? 'edit' : 'add'; ?>">
           <?php if ($product_to_edit): ?>
                <input type="hidden" name="product_id" value="<?php echo $product_to_edit['id']; ?>">
                <input type="hidden" name="current_image" value="<?php echo $product_to_edit['image']; ?>">
           <?php endif; ?>
     
                    <div>
                <label for="name">Tên Sản Phẩm:</label><br>
                <input type="text" id="name" name="name" 
                   value="<?php echo $product_to_edit ? htmlspecialchars($product_to_edit['name']) : ''; ?>" required>
           </div>
     
           <div>
                <label for="brand">Hãng Sản Xuất:</label><br>
                <input type="text" id="brand" name="brand" 
                   value="<?php echo $product_to_edit ? htmlspecialchars($product_to_edit['brand']) : ''; ?>" required>
           </div>
     
           <div>
                <label for="price">Giá:</label><br>
                <input type="number" id="price" name="price" 
                   value="<?php echo $product_to_edit ? htmlspecialchars($product_to_edit['price']) : ''; ?>" required>
           </div>
     
                    <div>
                <label for="image">Hình ảnh:</label><br>
                <input type="file" id="image" name="image" accept="image/*">
                
                <?php if ($product_to_edit && $product_to_edit['image']): 
                    $current_image_path = htmlspecialchars($product_to_edit['image']);
                ?>
                  <p style="margin-top: 5px;">Ảnh hiện tại: **<?php echo $current_image_path; ?>**</p>
                                      <img src="../anh/<?php echo $current_image_path; ?>" 
                     alt="Ảnh sản phẩm hiện tại" 
                     style="max-width: 100px; height: auto; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;">
                <?php endif; ?>
           </div>
         
           <button type="submit" style="background-color: #3498db; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
                <?php echo $product_to_edit ? 'Lưu Thay Đổi' : 'Thêm Sản Phẩm'; ?>
           </button>
           <div class="admin-controls">
                <a href="../products.php" style="margin-left: 10px;">Quay lại danh sách sản phẩm</a>
           </div>
    </form>
  </main>
</body>
</html>

<?php
$conn->close();
?>