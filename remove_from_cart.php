<?php
session_start();
include("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);

    // ลบสินค้านั้นออกจากตะกร้า
    unset($_SESSION['cart'][$product_id]);
}

// กลับไปหน้าตะกร้าสินค้า
header("Location: cart.php");
exit();
?>
