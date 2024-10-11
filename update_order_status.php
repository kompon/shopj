<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่งค่ามาหรือไม่
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // อัปเดตสถานะคำสั่งซื้อในฐานข้อมูล
    $sql = "UPDATE orders SET status_order = 'จัดส่งสินค้าแล้ว' WHERE order_id = '$order_id'";
    
    if (mysqli_query($conn, $sql)) {
        echo "สถานะคำสั่งซื้ออัปเดตเรียบร้อยแล้ว";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสถานะ: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
