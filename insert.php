<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า | ร้านเครื่องประดับ</title>
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

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        hr {
            margin: 40px 0;
            border: 0;
            border-top: 1px solid #ccc;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>ร้านเครื่องประดับ - เพิ่มสินค้า</h1>

    <form method="post" action="" enctype="multipart/form-data">
        <label for="pname">ชื่อสินค้า:</label>
        <input type="text" name="pname" id="pname" required autofocus>

        <label for="pdetail">รายละเอียดสินค้า:</label>
        <textarea name="pdetail" id="pdetail" rows="5" required></textarea>

        <label for="pprice">ราคา:</label>
        <input type="number" step="0.01" name="pprice" id="pprice" required>

        <label for="pimg">รูปภาพ:</label>
        <input type="file" name="pimg" id="pimg" required>

        <label for="pcat">ประเภทสินค้า:</label>
        <select name="pcat" id="pcat" required>
            <?php	
            include_once("connectdb.php");
            $sql2 = "SELECT * FROM category ORDER BY c_name ASC";
            $rs2 = mysqli_query($conn, $sql2);
            while ($data2 = mysqli_fetch_array($rs2)) {
            ?>
                <option value="<?=$data2['c_id'];?>"><?=$data2['c_name'];?></option>
            <?php } ?>
        </select>

        <button type="submit" name="Submit">เพิ่มสินค้า</button>
    </form>
    <hr>

    <?php
    if (isset($_POST['Submit'])) {
        // ตรวจสอบและจัดการการอัปโหลดไฟล์รูปภาพ
        $file_name = $_FILES['pimg']['name'];
        $file_tmp = $_FILES['pimg']['tmp_name'];
        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        // ตรวจสอบนามสกุลไฟล์
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        if (!in_array($ext, $allowed_ext)) {
            echo "<p class='error'>ไฟล์รูปภาพต้องเป็น JPG, JPEG, PNG หรือ GIF เท่านั้น</p>";
            exit;
        }

        // แทรกข้อมูลสินค้าใหม่เข้าในฐานข้อมูล
        $pname = mysqli_real_escape_string($conn, $_POST['pname']);
        $pdetail = mysqli_real_escape_string($conn, $_POST['pdetail']);
        $pprice = mysqli_real_escape_string($conn, $_POST['pprice']);
        $pcat = mysqli_real_escape_string($conn, $_POST['pcat']);

        $sql = "INSERT INTO product (p_name, p_detail, p_price, p_img, c_id) 
                VALUES ('$pname', '$pdetail', '$pprice', '$ext', '$pcat')";

        if (mysqli_query($conn, $sql)) {
            $idauto = mysqli_insert_id($conn);  // ดึงค่า id ของสินค้าที่เพิ่มล่าสุด

            // คัดลอกไฟล์ภาพไปยังโฟลเดอร์ images โดยตั้งชื่อไฟล์ตาม p_id
            if (move_uploaded_file($file_tmp, "images/" . $idauto . "." . $ext)) {
                echo "<p class='success'>เพิ่มข้อมูลสินค้าสำเร็จ</p>";
                echo "<script>window.location='admin_dashboard.php';</script>";
            } else {
                echo "<p class='error'>เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ</p>";
            }
        } else {
            echo "<p class='error'>เกิดข้อผิดพลาดในการเพิ่มข้อมูลสินค้า: " . mysqli_error($conn) . "</p>";
        }
    }

    mysqli_close($conn);
    ?>
</body>
</html>
