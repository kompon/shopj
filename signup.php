<?php
session_start();

// นับจำนวนสินค้าทั้งหมดในตะกร้า
$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $quantity) {
        $cart_count += $quantity;
    }
}

// เชื่อมต่อฐานข้อมูล
include("connectdb.php");

$error = ''; // เก็บข้อความผิดพลาด

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $name = mysqli_real_escape_string($conn, $_POST['name']); // เปลี่ยนชื่อตัวแปรให้ถูกต้อง

    // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($password === $confirm_password) {
        $hashed_password = md5($password);

        // ตรวจสอบว่า email นี้มีอยู่ในระบบหรือยัง
        $check_query = "SELECT * FROM users WHERE email='$email'";
        $check_result = mysqli_query($conn, $check_query);

        if (mysqli_num_rows($check_result) == 0) {
            // เพิ่มข้อมูลผู้ใช้ใหม่
            $sql = "INSERT INTO users (username, password, email, phone, address, name) 
                    VALUES ('$username', '$hashed_password', '$email', '$phone', '$address', '$name')";

            if (mysqli_query($conn, $sql)) {
                // ถ้าสำเร็จ redirect ไปยังหน้า login
                header("Location: login.php");
                exit();
            } else {
                $error = "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            $error = "This email has already been used.";
        }
    } else {
        $error = "Passwords don't match";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php include('header.php'); ?>

<div class="form-container signup-form">
    <h2>Sign Up</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="signup.php">
        
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required>

        <label for="phone">Phone:</label>
        <input type="text" name="phone" id="phone" required>

        <label for="address">Address:</label>
        <textarea name="address" id="address" required></textarea>

        <button type="submit">Sign Up</button>
    </form>
</div>

<footer>
    <p>Follow us: 
        <a href="https://www.facebook.com/jewellista.official">Facebook</a> | 
        <a href="https://www.instagram.com/jewellistath/">Instagram</a> | 
        <a href="https://shop.line.me/@jewellista">Line</a>
    </p>
    <p>© 2024 Jewelry Store. All rights reserved.</p>
</footer>

</body>
</html>
