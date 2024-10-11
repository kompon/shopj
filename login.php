<?php
session_start();
include("connectdb.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบรหัสผ่านโดยใช้ MD5
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = MD5('$password')";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
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
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
    <?php include('header.php'); ?>

    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="login.php">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
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
