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
</head>
<body>

    <?php include('header.php'); ?>

    <main>
        <footer style="text-align: center; padding: 225px 0;">
            <h1 style="margin-bottom: 20px;">Follow us: 
                <a href="https://www.facebook.com/jewellista.official">
                    <i class="fab fa-facebook"></i> Facebook
                </a> |  
                <a href="https://www.instagram.com/jewellistath/">
                    <i class="fab fa-instagram"></i> Instagram
                </a> | 
                <a href="https://shop.line.me/@jewellista">
                    <i class="fab fa-line"></i> Line
                </a>
            </h1>
            <p>© 2024 Jewelry Store. All rights reserved.</p>
        </footer>
    </main>

</body>
</html>
