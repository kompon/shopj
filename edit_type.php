<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขประเภทสินค้า | ร้านเครื่องประดับ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        hr {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<h1>แก้ไขประเภทสินค้า</h1>

<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่ง c_id มาหรือไม่
if (isset($_GET['c_id'])) {
    $c_id = $_GET['c_id'];

    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare("SELECT * FROM category WHERE c_id = ?");
    $stmt->bind_param("i", $c_id); // "i" หมายถึง integer
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    // ตรวจสอบว่ามีข้อมูลหรือไม่
    if (!$data) {
        echo "<div class='alert alert-danger text-center'>ไม่พบข้อมูลประเภทสินค้าที่ต้องการแก้ไข</div>";
        exit;
    }
} else {
    echo "<div class='alert alert-danger text-center'>ไม่มีประเภทสินค้าที่จะแก้ไข</div>";
    exit;
}

// เมื่อข้อมูลถูกต้อง ให้แสดงฟอร์มแก้ไข
?>

<!-- ฟอร์มแก้ไขประเภทสินค้า -->
<form method="post" action="">
    <div class="mb-3">
        <label for="cname" class="form-label">ชื่อประเภทสินค้า:</label>
        <input type="text" class="form-control" id="cname" name="cname" value="<?= htmlspecialchars($data['c_name']) ?>" required autofocus>
    </div>
    
    <input type="hidden" name="c_id" value="<?= $data['c_id']; ?>">
    <button type="submit" name="Submit" class="btn btn-primary">แก้ไข</button>
</form>

<hr>

<?php
// ตรวจสอบว่ามีการกดปุ่ม submit หรือไม่
if (isset($_POST['Submit'])) {
    // แก้ไขข้อมูลประเภทสินค้าจากฐานข้อมูล
    $c_name = $_POST['cname'];
    $c_id = $_POST['c_id'];

    // เตรียมคำสั่ง SQL สำหรับการอัปเดต
    $update_stmt = $conn->prepare("UPDATE category SET c_name = ? WHERE c_id = ?");
    $update_stmt->bind_param("si", $c_name, $c_id); // "si" หมายถึง string, integer

    // ตรวจสอบว่าการแก้ไขข้อมูลสำเร็จหรือไม่
    if ($update_stmt->execute()) {
        echo "<script>";
        echo "alert('แก้ไขข้อมูลประเภทสินค้าสำเร็จ');";
        echo "window.location='admin_dashboard.php';"; // เปลี่ยนเส้นทางไปยังหน้าแก้ไขประเภทสินค้า
        echo "</script>";
    } else {
        echo "<div class='alert alert-danger text-center'>เกิดข้อผิดพลาดในการแก้ไขข้อมูลประเภทสินค้า: " . mysqli_error($conn) . "</div>";
    }

    // ปิดการเชื่อมต่อฐานข้อมูล
    $update_stmt->close();
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
