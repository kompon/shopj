<?php
session_start();
include("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // ตรวจสอบว่ามีตะกร้าสินค้าอยู่แล้วหรือไม่ ถ้าไม่มีให้สร้างใหม่
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // ตรวจสอบว่าสินค้าอยู่ในตะกร้าหรือไม่
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // ถ้ายังไม่มีสินค้าในตะกร้า ให้เพิ่มสินค้าจำนวน 1 ชิ้น
    } else {
        $_SESSION['cart'][$product_id]++; // ถ้ามีแล้ว ให้เพิ่มจำนวนสินค้า
    }

    // ส่งผู้ใช้กลับไปยังหน้าที่มาก่อน
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
}
?>
