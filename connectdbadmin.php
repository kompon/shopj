<?php
    $host = "localhost";
    $usr = "root";
    $pwd = "";
    $db = "admin";

    $conn = mysqli_connect($host,$usr,$pwd) or die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ โปรดลองใหม่");//เชื่อต่อ db
    mysqli_select_db($conn,$db) or die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ โปรดลองใหม่");//เลือก db
    mysqli_query($conn,"SET NAMES utf8");//อ่านภาษาไทย
    
?>