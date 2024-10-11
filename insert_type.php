<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มประเภทสินค้า | ร้านเครื่องประดับ</title>
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
            max-width: 400px;
            margin: 0 auto;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #218838;
        }

        hr {
            margin: 20px auto;
            max-width: 400px;
            border: none;
            border-top: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>เพิ่มประเภทสินค้า</h1>

    <!-- ฟอร์มเพิ่มประเภทสินค้า -->
    <form method="post" action="">
        <label for="cname">ชื่อประเภทสินค้า:</label>
        <input type="text" name="cname" id="cname" required autofocus>

        <button type="submit" name="Submit">เพิ่มประเภทสินค้า</button>
    </form>

    <hr>

    <?php
    // ตรวจสอบว่ามีการกดปุ่ม submit หรือไม่
    if (isset($_POST['Submit'])) {
        include_once("connectdb.php");

        // แทรกข้อมูลประเภทสินค้าใหม่เข้าในฐานข้อมูล
        $sql = "INSERT INTO `category` (`c_id`, `c_name`) VALUES (NULL, '{$_POST['cname']}')";

        // ตรวจสอบว่าการเพิ่มข้อมูลสำเร็จหรือไม่
        if (mysqli_query($conn, $sql)) {
            echo "<script>";
            echo "alert('เพิ่มข้อมูลประเภทสินค้าสำเร็จ');";
            echo "window.location='admin_dashboard.php';"; // เปลี่ยนเส้นทางไปยังหน้าแก้ไขสินค้า
            echo "</script>";
        } else {
            echo "เกิดข้อผิดพลาดในการเพิ่มข้อมูลประเภทสินค้า: " . mysqli_error($conn);
        }

        // ปิดการเชื่อมต่อฐานข้อมูล
        mysqli_close($conn);
    }
    ?>
</body>
</html>
