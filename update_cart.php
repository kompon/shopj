<?php
session_start();
include("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity > 0) {
        // อัปเดตจำนวนสินค้าในตะกร้า
        $_SESSION['cart'][$product_id] = $quantity;
    } else {
        // ถ้าจำนวนเท่ากับ 0 ให้ลบออกจากตะกร้า
        unset($_SESSION['cart'][$product_id]);
    }
}

// กลับไปหน้าตะกร้าสินค้า
header("Location: cart.php");
exit();
?>
