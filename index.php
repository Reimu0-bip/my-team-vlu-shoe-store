<<<<<<< HEAD
<?php include 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<section class="banner">
     <h1>4T Shoe Store</h1>
     <p>Khám phá bộ sưu tập giày thời thượng!</p>
</section>
<link rel="stylesheet" href="css/index.css">
<main class="banner1">

  <a href="products.php" class="banner-button">Xem sản phẩm</a>
  
</main>

<!-- Load các file JS -->
<script>
     const loginError = <?= isset($_SESSION['login_error']) ? json_encode($_SESSION['login_error']) : 'null' ?>;
     <?php unset($_SESSION['login_error']); ?>
</script>
<script src='js/dangky.js'></script>
<script src="js/taitrang.js"></script>

=======
<?php include 'includes/db.php'; ?>
<?php require_once 'includes/header.php'; ?>

<section class="banner">
     <h1>4T Shoe Store</h1>
     <p>Khám phá bộ sưu tập giày thời thượng!</p>
</section>
<link rel="stylesheet" href="css/index.css">
<main class="banner1">

  <a href="products.php" class="banner-button">Xem sản phẩm</a>
  
</main>

<!-- Load các file JS -->
<script>
     const loginError = <?= isset($_SESSION['login_error']) ? json_encode($_SESSION['login_error']) : 'null' ?>;
     <?php unset($_SESSION['login_error']); ?>
</script>
<script src='js/dangky.js'></script>
<script src="js/taitrang.js"></script>

>>>>>>> 9ee000d7c9b32ce9c2f594ee786e9bdedb0b21a7
<?php include 'includes/footer.php'; ?>