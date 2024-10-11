<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่งค่า p_id มาหรือไม่
if (isset($_GET['p_id'])) {
    $p_id = $_GET['p_id'];

    // ดึงข้อมูลสินค้าที่ต้องการแก้ไข
    $sql = "SELECT * FROM product WHERE p_id = $p_id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);

    if (!$data) {
        echo "ไม่พบสินค้าที่ต้องการแก้ไข";
        exit;
    }
} else {
    echo "ไม่มีสินค้าที่ต้องการแก้ไข";
    exit;
}

// อัปเดตข้อมูลสินค้าเมื่อกดบันทึก
if (isset($_POST['update'])) {
    $pname = $_POST['pname'];
    $pdetail = $_POST['pdetail'];
    $pprice = $_POST['pprice'];
    $pcat = $_POST['pcat'];
    $pimg = $data['p_img']; // กรณีที่ผู้ใช้ไม่อัปเดตภาพใหม่

    // ตรวจสอบว่ามีการอัปเดตรูปภาพใหม่หรือไม่
    if ($_FILES['pimg']['name'] != '') {
        $file_name = $_FILES['pimg']['name'];
        $ext = substr($file_name, strrpos($file_name, '.') + 1);
        $pimg = $ext;
        // ลบไฟล์เก่าก่อน
        if (file_exists("images/" . $p_id . "." . $data['p_img'])) {
            unlink("images/" . $p_id . "." . $data['p_img']);
        }
        // คัดลอกไฟล์ใหม่
        copy($_FILES['pimg']['tmp_name'], "images/" . $p_id . "." . $pimg);
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE product SET p_name = '$pname', p_detail = '$pdetail', p_price = '$pprice', c_id = '$pcat', p_img = '$pimg' WHERE p_id = $p_id";
    if (mysqli_query($conn, $sql)) {
        echo "<script>";
        echo "alert('อัปเดตสินค้าสำเร็จ');";
        echo "window.location='admin_dashboard.php';";
        echo "</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตสินค้า: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า | ร้านเครื่องประดับ</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"], input[type="number"], select, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        img {
            display: block;
            margin-bottom: 20px;
            max-width: 150px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <h1>แก้ไขสินค้า</h1>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="pname">ชื่อสินค้า:</label>
        <input type="text" name="pname" id="pname" value="<?=$data['p_name'];?>" required>

        <label for="pdetail">รายละเอียดสินค้า:</label>
        <textarea name="pdetail" id="pdetail" rows="5" required><?=$data['p_detail'];?></textarea>

        <label for="pprice">ราคา:</label>
        <input type="text" name="pprice" id="pprice" value="<?=$data['p_price'];?>" required>

        <label for="pimg">รูปภาพ:</label>
        <input type="file" name="pimg">
        <img src="images/<?=$p_id;?>.<?=$data['p_img'];?>" alt="Product Image">

        <label for="pcat">ประเภทสินค้า:</label>
        <select name="pcat" id="pcat">
            <?php
            $sql2 = "SELECT * FROM category ORDER BY c_name ASC";
            $rs2 = mysqli_query($conn, $sql2);
            while ($cat = mysqli_fetch_array($rs2)) {
                $selected = ($cat['c_id'] == $data['c_id']) ? "selected" : "";
            ?>
                <option value="<?=$cat['c_id'];?>" <?=$selected;?>><?=$cat['c_name'];?></option>
            <?php } ?>
        </select>

        <button type="submit" name="update">บันทึกการแก้ไข</button>
    </form>
</body>
</html>
