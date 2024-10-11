<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- JavaScript for auto-submitting the sort form -->
    <script>
        function submitSortForm() {
            document.getElementById('sortForm').submit();
        }
    </script>
</head>

<body>

    <?php include('header.php'); ?>

    <div class="search-bar">
        <form method="POST" action="">
            <input type="text" name="search" placeholder="Search for products..." value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>">
            <button type="submit"><i class="fas fa-search"></i> Search</button>
        </form>
    </div>

    <main>
        <!-- Sort Bar -->
        <div class="sort-bar">
            <form id="sortForm" method="POST" action="">
                <input type="hidden" name="search" value="<?= htmlspecialchars(isset($_POST['search']) ? $_POST['search'] : '') ?>">
                <select name="order_by" onchange="submitSortForm()">
                    <option value="">Sort by</option>
                    <option value="price_low_high" <?= isset($_POST['order_by']) && $_POST['order_by'] == 'price_low_high' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high_low" <?= isset($_POST['order_by']) && $_POST['order_by'] == 'price_high_low' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </form>
        </div><br>

        <section class="products">
        <?php
        
        include("connectdb.php");

        // คำค้นหา
        $kw = isset($_POST['search']) ? $_POST['search'] : '';
        $order_by = isset($_POST['order_by']) ? $_POST['order_by'] : '';

        // กำหนดจำนวนสินค้าต่อหน้า
        $limit = 20; 
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // นับจำนวนสินค้าทั้งหมดที่ตรงกับการค้นหา
        $count_sql = "SELECT COUNT(*) FROM product WHERE (p_name LIKE '%{$kw}%' OR p_detail LIKE '%{$kw}%')";
        $count_result = mysqli_query($conn, $count_sql);
        $total_rows = mysqli_fetch_array($count_result)[0];
        $total_pages = ceil($total_rows / $limit);

        // สร้างคำสั่ง SQL สำหรับดึงข้อมูลสินค้าตามการค้นหาและการจัดเรียง
        $sql = "SELECT * FROM product WHERE (p_name LIKE '%{$kw}%' OR p_detail LIKE '%{$kw}%')";

        // เพิ่มเงื่อนไขการจัดเรียง
        if ($order_by == 'price_low_high') {
            $sql .= " ORDER BY p_price ASC";
        } elseif ($order_by == 'price_high_low') {
            $sql .= " ORDER BY p_price DESC";
        }

        // เพิ่มการแบ่งหน้า
        $sql .= " LIMIT $limit OFFSET $offset";
        $rs = mysqli_query($conn, $sql);

        // แสดงผลสินค้า
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

        mysqli_close($conn);
        ?>
        </section>
    </main>

    <!-- แสดงปุ่มการแบ่งหน้า (Pagination) -->
    <div class="pagination">
        <?php if ($page > 1) { ?>
            <a href="?page=<?= $page - 1 ?>"><button>«</button></a>
        <?php } ?>

        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
            <a href="?page=<?= $i ?>"><button class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></button></a>
        <?php } ?>

        <?php if ($page < $total_pages) { ?>
            <a href="?page=<?= $page + 1 ?>"><button>»</button></a>
        <?php } ?>
    </div>

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
