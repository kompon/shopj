<?php
session_start();
include('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ตรวจสอบว่ามีการส่ง order_id มา
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $user_id = $_SESSION['user_id'];

    // ตรวจสอบว่า order_id นั้นเป็นของผู้ใช้ที่ล็อกอินอยู่
    $check_order_sql = "SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = '$user_id'";
    $check_order_result = mysqli_query($conn, $check_order_sql);

    if (mysqli_num_rows($check_order_result) > 0) {
        // ลบรายการสินค้าที่เกี่ยวข้องใน order_items ก่อน
        $delete_order_items_sql = "DELETE FROM order_items WHERE order_id = '$order_id'";
        mysqli_query($conn, $delete_order_items_sql);

        // จากนั้นลบคำสั่งซื้อจากตาราง orders
        $delete_order_sql = "DELETE FROM orders WHERE order_id = '$order_id'";
        mysqli_query($conn, $delete_order_sql);

        // รีเฟรชกลับมายังหน้า orders.php โดยการใช้ header location
        header("Location: " . $_SERVER['HTTP_REFERER']); // กลับไปยังหน้าเดิมที่เรียก delete_order.php
        exit;
    } else {
        echo "Order not found or you do not have permission to delete this order.";
    }
} else {
    echo "No order ID provided.";
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>
