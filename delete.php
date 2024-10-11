<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่งค่า p_id มาหรือไม่
if(isset($_GET['p_id'])){
    $p_id = $_GET['p_id'];
    
    // ดึงข้อมูลรูปภาพของสินค้าที่จะลบ
    $sql = "SELECT p_img FROM product WHERE p_id = $p_id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    
    // ลบข้อมูลสินค้าจากฐานข้อมูล
    $sql = "DELETE FROM product WHERE p_id = $p_id";
    if(mysqli_query($conn, $sql)){
        // ลบรูปภาพออกจากโฟลเดอร์ images
        $img_path = "images/".$p_id.".".$data['p_img'];
        if(file_exists($img_path)){
            unlink($img_path);
        }
        
        echo "<script>";
        echo "alert('ลบสินค้าสำเร็จ');";
        echo "window.location='admin_dashboard.php';";
        echo "</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการลบสินค้า: " . mysqli_error($conn);
    }
} else {
    echo "ไม่มีสินค้าที่จะลบ";
}

mysqli_close($conn);
?>
