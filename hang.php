<?php include 'includes/db.php'; ?>
<?php include 'includes/header.php'; ?>

<section class="products">
    <h2>Sản phẩm của thương hiệu <?php echo htmlspecialchars($_GET['name']); ?></h2>
    <div class="product-list">
        <?php
        $brand = mysqli_real_escape_string($conn, $_GET['name']);
        
        $sql = "SELECT * FROM products WHERE brand = '$brand'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "
                <div class='product'>
                  <img src='anh/{$row['image']}' alt='{$row['name']}'>
                  <h3>{$row['name']}</h3>
                  <p>" . number_format($row['price'], 0, ',', '.') . " VNĐ</p>
                  <button onclick='addToCart({$row['id']})'>Thêm vào giỏ</button>
                  
                </div>";
              }
        } 
        else {
            echo "<p>Không có sản phẩm nào từ thương hiệu này.</p>";
        }

        $conn->close();
        ?>
    </div>
</section>
<script src="js/dangky.js"></script>
<script src="js/giohang.js"></script>
<script src="js/taitrang.js"></script>

<?php include 'includes/footer.php'; ?>
