<?php
session_start();
include("connectdb.php");

// รับ p_id จาก URL
$p_id = isset($_GET['p_id']) ? $_GET['p_id'] : '';

// ตรวจสอบว่ามี p_id หรือไม่
if (empty($p_id)) {
    echo "Product not found.";
    exit;
}

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$sql = "SELECT * FROM product WHERE p_id = '{$p_id}'";
$result = mysqli_query($conn, $sql);

// ตรวจสอบว่ามีสินค้าหรือไม่
if (mysqli_num_rows($result) > 0) {
    $product = mysqli_fetch_assoc($result);
} else {
    echo "Product not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['p_name']); ?> - Jewelry Store</title>
    <link rel="stylesheet" href="product_detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>

<body>

    <?php include('header.php'); ?>

    <main>
        <section class="product-detail">
            <div class="product-image">
                <img src="images/<?= $product['p_id']; ?>.<?= htmlspecialchars($product['p_img']); ?>" alt="<?= htmlspecialchars($product['p_name']); ?>">
            </div>
            <div class="product-info"><br><br>
                <h1><?= htmlspecialchars($product['p_name']); ?></h1>
                <p class="product-description"><?= htmlspecialchars($product['p_detail']); ?></p>
                <p class="product-price"><?= number_format($product['p_price'], 2); ?> THB</p>

                <form method="POST" action="add_to_cart.php" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= $product['p_id']; ?>">
                    <button type="submit" class="add-to-cart-btn">
                        <i class="fas fa-cart-plus"></i> Add to Cart
                    </button>
                </form>
            </div>
        </section>
    </main>

</body>

</html>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
