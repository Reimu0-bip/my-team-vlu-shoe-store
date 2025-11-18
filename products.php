<?php 
// 1. BẮT BUỘC: Bắt đầu Session
if (session_status() == PHP_SESSION_NONE) {
       session_start();
}
include 'includes/db.php'; 
include 'includes/header.php'; 

// Xác định Admin Role (Dùng biến 'user_role' theo file admin_product.php)
$is_admin = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin');

// -------------------------------------------------------------
// XỬ LÝ TÌM KIẾM VÀ TRUY VẤN
// -------------------------------------------------------------
$search_query = "";
$sql = "SELECT * FROM products";
$params = [];
$types = "";
$is_searching = false;

if (isset($_GET['search']) && !empty($_GET['search'])) {
     $search_term = '%' . $_GET['search'] . '%';
     $search_query = htmlspecialchars($_GET['search']); // Giữ lại từ khóa để điền vào input
     $is_searching = true;

     // Sửa truy vấn: Tìm kiếm theo tên sản phẩm hoặc hãng
     $sql .= " WHERE name LIKE ? OR brand LIKE ?";
     $params = [$search_term, $search_term];
     $types = "ss";
}

// THAY ĐỔI: Nếu KHÔNG tìm kiếm, sắp xếp ngẫu nhiên
if (!$is_searching) {
     $sql .= " ORDER BY RAND()";
}

// Chuẩn bị truy vấn
$stmt = $conn->prepare($sql);

// KHẮC PHỤC LỖI BIND_PARAM Ở ĐÂY
if ($types) {
    // 1. Khởi tạo mảng đầu tiên chứa chuỗi định dạng ("ss")
    $bind_names[] = $types;

    // 2. Tạo biến động và mảng tham chiếu cho các tham số
    for ($i = 0; $i < count($params); $i++) {
        $bind_name = 'bind' . $i;
        $$bind_name = $params[$i]; // Tạo biến có tên động ($bind0, $bind1) và gán giá trị
        $bind_names[] = &$$bind_name; // Lấy tham chiếu của biến đó và thêm vào mảng
    }
    
    // 3. Thực hiện bind_param an toàn bằng cách truyền mảng tham chiếu
    call_user_func_array([$stmt, 'bind_param'], $bind_names);
}
// KẾT THÚC KHẮC PHỤC

$stmt->execute();
$result = $stmt->get_result();

?>

<h2>Danh sách sản phẩm</h2>

<?php if ($is_admin): ?>
     <div class="admin-controls" style="margin-bottom: 20px; text-align: left;">
             <a href="admin/admin_product.php" style="padding: 10px 15px; background-color: #2ecc71; color: white; text-decoration: none; border-radius: 5px; display: inline-block;">
                   + Thêm Sản Phẩm Mới
             </a>
       </div>
<?php endif; ?>

<div class="search-container" style="text-align: center; margin-bottom: 30px;">
       <form action="products.php" method="GET" class="search-form">
             <input type="text" 
                        name="search" 
                        placeholder="Tìm kiếm theo tên hoặc hãng..." 
                        value="<?= $search_query ?>"
                        style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 5px 0 0 5px;">
             <button type="submit" 
                         style="padding: 10px 15px; background-color: #3498db; color: white; border: none; border-radius: 0 5px 5px 0; cursor: pointer;">
                   Tìm kiếm
             </button>
             <?php if ($search_query): ?>
                   <a href="products.php" style="margin-left: 10px; text-decoration: none; color: #e74c3c;">Xóa tìm kiếm</a>
             <?php endif; ?>
       </form>
</div>


<div class="product-list">
<?php
if ($result->num_rows > 0) {
         while($row = $result->fetch_assoc()) {
               echo "
               <div class='product'>
                      <img src='anh/{$row['image']}' alt='{$row['name']}'> 
                      <h3>{$row['name']}</h3>
                      <p>" . number_format($row['price'], 0, ',', '.') . " VNĐ</p>";
                  // PHÂN QUYỀN NÚT TRONG DANH SÁCH
                  if ($is_admin) {
                            echo "<a href='admin/admin_product.php?action=edit&id={$row['id']}' 
                                   class='admin-btn' 
                                   style='margin-right: 5px; color: #3498db;'>Sửa</a> | "; 

                       echo "<a href='admin/admin_product.php?id={$row['id']}' 
                                   class='admin-btn delete-btn' 
                                   onclick='return confirm(\"Bạn có chắc chắn muốn xóa sản phẩm {$row['name']}?\")'
                                   style='color: red;'>Xóa</a>";
                  } else {
                            // Người dùng thường thấy nút Thêm vào giỏ
                            echo "<button onclick='addToCart({$row['id']})'>Thêm vào giỏ</button>";
                  }

               echo "
               </div>";
       }
} else {
       echo "<p style='text-align: center; width: 100%; color: #e74c3c;'>Không tìm thấy sản phẩm nào phù hợp với từ khóa \"{$search_query}\".</p>";
}

// Đóng statement
$stmt->close();
?>
</div>
<a href="#" id="scrollToTopBtn" title="Trở lên đầu">&#9650</a>

<script src="js/dangky.js"></script>
<script src="js/giohang.js"></script>
<script src="js/taitrang.js"></script>
<script src="js/sot.js"></script>

<?php include 'includes/footer.php'; ?>