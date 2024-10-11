<?php
session_start();
session_destroy(); // ลบข้อมูล session ทั้งหมด
header("Location: index.php"); // กลับไปหน้าแรก
exit();
?>