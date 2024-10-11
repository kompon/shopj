<?php
// เริ่ม session เพื่อใช้จัดการ login/logout
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
    <style>
        /* เพิ่มสไตล์เพื่อจัดการการเลื่อนซ้าย-ขวา */
        .category-section {
            margin-bottom: 30px;
            position: relative; /* เพื่อให้ปุ่มสามารถวางทับได้ */
        }

        .category-title {
            font-size: 2em; /* ขนาดฟอนต์เพิ่มขึ้น */
            margin-bottom: 10px;
            color: #4A4A4A; /* สีของชื่อประเภทสินค้า */
            position: relative; /* เพื่อให้สามารถใช้เอฟเฟกต์ */
            display: inline-block; /* ทำให้สามารถใช้ padding ได้ */
            padding-bottom: 5px; /* เพิ่มพื้นที่ด้านล่าง */
        }

        .category-title::after {
            content: ""; /* เพิ่มเส้นใต้ */
            display: block;
            width: 50%; /* ความกว้างของเส้น */
            height: 4px; /* ความสูงของเส้น */
            background-color: #FF6F61; /* สีของเส้น */
            position: absolute; /* วางเส้นไว้ที่ตำแหน่งสุดท้าย */
            left: 0;
            bottom: 0; /* วางเส้นที่ด้านล่างของชื่อ */
            transition: width 0.4s ease; /* เพิ่มการเคลื่อนไหวให้กับเส้น */
        }

        .category-title:hover::after {
            width: 100%; /* เปลี่ยนความกว้างของเส้นเมื่อ hover */
        }

        .products-slider {
            display: flex;
            overflow-x: hidden; /* ซ่อนการเลื่อน */
            gap: 15px;
            padding-bottom: 15px;
        }

        .product-card {
            min-width: 200px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }

        .products-slider::-webkit-scrollbar {
            display: none;
        }

        /* ปุ่มเลื่อน */
        .scroll-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #ddd;
            border-radius: 50%;
            padding: 10px;
            cursor: pointer;
            z-index: 1; /* ให้ปุ่มอยู่เหนือสินค้า */
        }

        .scroll-left {
            left: 10px; /* ปุ่มเลื่อนซ้าย */
        }

        .scroll-right {
            right: 10px; /* ปุ่มเลื่อนขวา */
        }
    </style>
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <?php
        // เชื่อมต่อฐานข้อมูล
        include("connectdb.php");

        // ตรวจสอบการเชื่อมต่อฐานข้อมูล
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // ดึงข้อมูลหมวดหมู่สินค้า 5 หมวดหมู่
        $category_sql = "SELECT * FROM category LIMIT 5";
        $category_result = mysqli_query($conn, $category_sql);

        if (mysqli_num_rows($category_result) > 0) {
            while ($category = mysqli_fetch_assoc($category_result)) {
                $c_id = $category['c_id'];
                $c_name = htmlspecialchars($category['c_name']);
                
                // แสดงชื่อหมวดหมู่สินค้า
                echo "<section class='category-section'>";
                echo "<h2 class='category-title'>$c_name</h2>";
                echo "<div class='products-slider' id='slider-$c_id'>"; // ใช้ id เฉพาะ
              
                // ดึงข้อมูลสินค้าในหมวดหมู่นั้น
                $product_sql = "SELECT * FROM product WHERE c_id = ?";
                $stmt = mysqli_prepare($conn, $product_sql);
                mysqli_stmt_bind_param($stmt, "i", $c_id);
                mysqli_stmt_execute($stmt);
                $rs = mysqli_stmt_get_result($stmt);

                // แสดงข้อมูลสินค้า
                if (mysqli_num_rows($rs) > 0) {
                    while ($data = mysqli_fetch_array($rs)) {
                        ?>
                        <div class="product-card">
                            <img src="images/<?= htmlspecialchars($data['p_id']); ?>.<?= htmlspecialchars($data['p_img']); ?>" alt="<?= htmlspecialchars($data['p_name']); ?>">
                            <h3>
                                <a href="product_detail.php?p_id=<?= $data['p_id']; ?>">
                                    <?= htmlspecialchars(substr($data['p_name'], 0, 20)) . (strlen($data['p_name']) > 20 ? '...' : ''); ?>
                                </a>
                            </h3>
                            <p><?= htmlspecialchars(substr($data['p_detail'], 0, 20)) . (strlen($data['p_detail']) > 20 ? '...' : ''); ?></p>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No products available in this category.</p>";
                }

                echo "</div>"; // ปิด products-slider
                
                // เพิ่มปุ่มเลื่อน
                echo "<button class='scroll-button scroll-left' onclick='scrollLeft(\"slider-$c_id\")'><i class='fas fa-chevron-left'></i></button>";
                echo "<button class='scroll-button scroll-right' onclick='scrollRight(\"slider-$c_id\")'><i class='fas fa-chevron-right'></i></button>";
                
                echo "</section>"; // ปิด category-section
            }
        } else {
            echo "<p>No categories found.</p>";
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);
        ?>
    </main>

    <footer>
        <p>Follow us: 
            <a href="https://www.facebook.com/jewellista.official">Facebook</a> | 
            <a href="https://www.instagram.com/jewellistath/">Instagram</a> | 
            <a href="https://shop.line.me/@jewellista">Line</a>
        </p>
        <p>© 2024 Jewelry Store. All rights reserved.</p>
    </footer>

    <script>
        function scrollLeft(sliderId) {
            const slider = document.getElementById(sliderId);
            slider.scrollBy({ left: -200, behavior: 'smooth' }); // เลื่อนซ้าย 200px
        }

        function scrollRight(sliderId) {
            const slider = document.getElementById(sliderId);
            slider.scrollBy({ left: 200, behavior: 'smooth' }); // เลื่อนขวา 200px
        }
    </script>

</body>
</html>
