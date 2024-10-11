<?php
session_start();
include("connectdb.php");

// รับคำค้นหา
$kw = isset($_POST['search']) ? $_POST['search'] : '';
// รับ category_id จาก URL
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : '';
// รับตัวเลือกการจัดเรียง (ถ้ามี)
$order_by = isset($_POST['order_by']) ? $_POST['order_by'] : '';

// สร้างคำสั่ง SQL สำหรับค้นหาสินค้า
$sql = "SELECT * FROM product WHERE (p_name LIKE '%{$kw}%' OR p_detail LIKE '%{$kw}%')";

// เพิ่มเงื่อนไขสำหรับ category_id ถ้ามีการกำหนด
if (!empty($category_id)) {
    $sql .= " AND c_id = '{$category_id}'";
}

// จัดเรียงตามราคาถ้าผู้ใช้เลือก
if ($order_by == 'price_low_high') {
    $sql .= " ORDER BY p_price ASC"; // ราคาต่ำไปสูง
} elseif ($order_by == 'price_high_low') {
    $sql .= " ORDER BY p_price DESC"; // ราคาสูงไปต่ำ
}

$rs = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- เพิ่ม JavaScript เพื่อส่งฟอร์มอัตโนมัติ -->
    <script>
        function submitSortForm() {
            document.getElementById('sortForm').submit();
        }
    </script>
</head>

<body>

<?php include('header.php'); ?>

<div class="search-bar">
    <!-- ฟอร์มค้นหาสินค้า -->
    <form method="POST" action="">
        <input type="text" name="search" placeholder="Search for products..." value="<?= htmlspecialchars($kw); ?>">
        <button type="submit"><i class="fas fa-search"></i> Search</button>
    </form>
</div>

<main>
    <!-- ส่วนจัดเรียงสินค้า อยู่ด้านบนซ้ายของสินค้าชิ้นแรก -->
    <section class="sort-and-products">
        <div class="sort-bar">
            <!-- ฟอร์มจัดเรียงสินค้า -->
            <form id="sortForm" method="POST" action="">
                <input type="hidden" name="search" value="<?= htmlspecialchars($kw); ?>">
                <select name="order_by" onchange="submitSortForm()">
                    <option value="">Sort by</option>
                    <option value="price_low_high" <?= $order_by == 'price_low_high' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high_low" <?= $order_by == 'price_high_low' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </form>
        </div><br>

        <section class="products">
        <?php
        // ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
        if (mysqli_num_rows($rs) > 0) {
            while ($data = mysqli_fetch_array($rs)) {
        ?>
            <div class="product-card">
                <img src="images/<?= $data['p_id']; ?>.<?= htmlspecialchars($data['p_img']); ?>" width="150" height="150" alt="<?= htmlspecialchars($data['p_name']); ?>">

                <!-- ลิงก์ชื่อสินค้าไปยังหน้า product_detail.php -->
                <h3>
                    <a href="product_detail.php?p_id=<?= $data['p_id']; ?>">
                        <?= htmlspecialchars(substr($data['p_name'], 0, 20)) . (strlen($data['p_name']) > 20 ? '...' : ''); ?>
                    </a>
                </h3>

                <p><?= htmlspecialchars(substr($data['p_detail'], 0, 20)) . (strlen($data['p_detail']) > 20 ? '...' : ''); ?></p>
                <p><strong><?= number_format($data['p_price'], 2); ?> THB</strong></p>
                
                <!-- ปุ่มหยิบสินค้า -->
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?= $data['p_id']; ?>">
                    <button type="submit" class="add-to-cart-btn"><i class="fas fa-cart-plus"></i> Add to Cart</button>
                </form>
            </div>
        <?php
            }
        } else {
            echo "<p>No products found matching your search.</p>";
        }
        ?>
        </section>
    </section>
</main>

<footer>
    <p>Follow us: 
        <a href="https://www.facebook.com/jewellista.official">Facebook</a> | 
        <a href="https://www.instagram.com/jewellistath/">Instagram</a> | 
        <a href="https://shop.line.me/@jewellista">Line</a>
    </p>
    <p>© 2024 Jewelry Store. All rights reserved.</p>
</footer>

</body>
</html>
