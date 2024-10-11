<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // เริ่มต้น Transaction เพื่อให้แน่ใจว่าขั้นตอนทั้งหมดสำเร็จหรือย้อนกลับได้
    mysqli_begin_transaction($conn);

    try {
        // ลบข้อมูลในตาราง payments ที่มี order_id เชื่อมโยงกับ user_id
        $sql_payments = "DELETE FROM payments WHERE order_id IN (SELECT order_id FROM orders WHERE user_id = $id)";
        mysqli_query($conn, $sql_payments);

        // ลบข้อมูลในตาราง order_items ที่มี order_id เชื่อมโยงกับ user_id
        $sql_order_items = "DELETE FROM order_items WHERE order_id IN (SELECT order_id FROM orders WHERE user_id = $id)";
        mysqli_query($conn, $sql_order_items);

        // ลบข้อมูลในตาราง orders ที่มี user_id เท่ากับ id ที่ต้องการลบ
        $sql_orders = "DELETE FROM orders WHERE user_id = $id";
        mysqli_query($conn, $sql_orders);

        // ลบข้อมูลผู้ใช้จากฐานข้อมูล
        $sql_users = "DELETE FROM users WHERE id = $id";

        if (mysqli_query($conn, $sql_users)) {
            // ถ้าลบสำเร็จให้ Commit Transaction
            mysqli_commit($conn);
            echo "<script>";
            echo "alert('ลบข้อมูลผู้ใช้สำเร็จ');";
            echo "window.location.href='admin_dashboard.php?loaduser=1';"; // เปลี่ยนไปที่หน้า admin_dashboard.php
            echo "loadlistuser();"; // เรียกใช้ฟังก์ชัน loadlistuser เพื่อโหลดหน้า list_user.php
            echo "</script>";
        } else {
            // ถ้าลบไม่สำเร็จให้ Rollback Transaction
            mysqli_rollback($conn);
            echo "เกิดข้อผิดพลาดในการลบข้อมูลผู้ใช้: " . mysqli_error($conn);
        }
    } catch (Exception $e) {
        // ถ้ามีข้อผิดพลาดให้ Rollback Transaction
        mysqli_rollback($conn);
        echo "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
} else {
    echo "ไม่มีผู้ใช้ที่ต้องการลบ";
}
?>
