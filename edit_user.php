<?php
include_once("connectdb.php");

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if(isset($_GET['id'])){
    $id = $_GET['id'];

    // ดึงข้อมูลผู้ใช้ที่ต้องการแก้ไข
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    
    if (!$data) {
        echo "ไม่พบผู้ใช้ที่ต้องการแก้ไข";
        exit;
    }
} else {
    echo "ไม่มีผู้ใช้ที่ต้องการแก้ไข";
    exit;
}

// อัปเดตข้อมูลผู้ใช้เมื่อกดบันทึก
if(isset($_POST['update'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $name = $_POST['name'];

    // เข้ารหัสรหัสผ่านด้วย MD5
    $hashed_password = md5($password);

    // อัปเดตข้อมูลในฐานข้อมูล
    $sql = "UPDATE users SET username = '$username', password = '$hashed_password', email = '$email', phone = '$phone', address = '$address', name = '$name' WHERE id = $id";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>";
        echo "alert('อัปเดตข้อมูลผู้ใช้สำเร็จ');";
        echo "window.location='admin_dashboard.php?loaduser=1';"; // เปลี่ยนเป็นชื่อหน้าที่จะแสดงรายการผู้ใช้
        echo "</script>";
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูลผู้ใช้: " . mysqli_error($conn);
    }
}
?>

<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลผู้ใช้ | ร้านเครื่องประดับ</title>
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
            margin-bottom: 40px;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>แก้ไขข้อมูลผู้ใช้</h1>

<form method="post" action="">
    <div class="mb-3">
        <label for="username" class="form-label">ชื่อผู้ใช้</label>
        <input type="text" class="form-control" id="username" name="username" value="<?=$data['username'];?>" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">รหัสผ่าน</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="กรุณากรอกรหัสผ่านใหม่" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">อีเมล</label>
        <input type="email" class="form-control" id="email" name="email" value="<?=$data['email'];?>" required>
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">โทรศัพท์</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?=$data['phone'];?>" required>
    </div>
    <div class="mb-3">
        <label for="address" class="form-label">ที่อยู่</label>
        <input type="text" class="form-control" id="address" name="address" value="<?=$data['address'];?>" required>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">ชื่อจริง</label>
        <input type="text" class="form-control" id="name" name="name" value="<?=$data['name'];?>" required>
    </div>

    <button type="submit" name="update" class="btn btn-primary w-100">บันทึกการแก้ไข</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-P2i6m2d4E2lUYzDiXpobxKaeMeFyfYrT7J/Li3GDQoDa1UKie9SM9FqvDA6bJZr6" crossorigin="anonymous"></script>

</body>
</html>
