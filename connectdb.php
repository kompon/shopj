<?php
    $host = "localhost";
    $usr = "u543902539_root";
    $pwd = "aa123456tT";
    $db = "u543902539_shopj";

    $conn = mysqli_connect($host,$usr,$pwd) or die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ โปรดลองใหม่");//เชื่อต่อ db
    mysqli_select_db($conn,$db) or die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ โปรดลองใหม่");//เลือก db
    mysqli_query($conn,"SET NAMES utf8");//อ่านภาษาไทย
    
?>