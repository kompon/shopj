<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่งค่า c_id มาหรือไม่
if(isset($_GET['c_id'])){
    $c_id = $_GET['c_id'];

    // ลบข้อมูลประเภทสินค้าจากฐานข้อมูล
    $sql = "DELETE FROM category WHERE c_id = $c_id";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>";
        echo "alert('ลบประเภทสินค้าสำเร็จ');";
        echo "window.location='admin_dashboard.php';"; // เปลี่ยนเส้นทางกลับไปยังหน้าแก้ไขประเภทสินค้า
        echo "</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการลบประเภทสินค้า: " . mysqli_error($conn);
    }
} else {
    echo "ไม่มีประเภทสินค้าที่จะลบ";
}

mysqli_close($conn);
?>
