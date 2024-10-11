<?php
if (session_status() == PHP_SESSION_NONE) {
    // กำหนดชื่อ session ให้กับระบบหลังบ้าน
    session_name('admin_session');
    session_start();
}
include("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['a_name'];
    $password = $_POST['a_pass'];

    // ตรวจสอบรหัสผ่านโดยใช้ MD5
    $sql = "SELECT * FROM admin_user WHERE a_name = '$username' AND a_pass = MD5('$password')";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['a_id'];
        $_SESSION['username'] = $user['a_name'];
        header("Location:admin_dashboard.php");
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบหลังบ้าน Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="login-container">
        <h2>Login หลังบ้าน</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="adminlogin.php">
            <label for="a_name">Username:</label>
            <input type="text" id="a_name" name="a_name" required>
            <label for="a_pass">Password:</label>
            <input type="password" id="a_pass" name="a_pass" required>
            <button type="submit">Login</button>
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
<?php

