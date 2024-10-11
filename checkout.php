<?php
session_start();
include("connectdb.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูลคำสั่งซื้อ
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    
    // ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล
    $order_sql = "SELECT * FROM orders WHERE order_id = '$order_id'";
    $order_result = mysqli_query($conn, $order_sql);
    $order_data = mysqli_fetch_assoc($order_result);
} else {
    echo "Order not found.";
    exit;
}

// เมื่อผู้ใช้เลือกวิธีการชำระเงินและกดปุ่ม "ชำระเงิน"
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['payment_method'])) {
    $payment_method = $_POST['payment_method'];

    // Handle file upload
    if (isset($_FILES['payment_slip']) && $_FILES['payment_slip']['error'] == 0) {
        $target_dir = "uploads/payment_slips/";
        $file_name = basename($_FILES["payment_slip"]["name"]);
        $target_file = $target_dir . time() . '_' . $file_name;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file size (limit: 5MB)
        if ($_FILES["payment_slip"]["size"] > 5000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        } else {
            // If everything is ok, try to upload file
            if (move_uploaded_file($_FILES["payment_slip"]["tmp_name"], $target_file)) {
                echo "The file " . htmlspecialchars($file_name) . " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit;
            }
        }
    } else {
        $target_file = null; // No file uploaded
    }

    // Insert payment into the database
    $payment_sql = "INSERT INTO payments (order_id, payment_method, payment_status, payment_date, payment_slip) 
                    VALUES ('$order_id', '$payment_method', 'Completed', NOW(), '$target_file')";
    
    if (mysqli_query($conn, $payment_sql)) {
        // Update order payment status
        $payment_id = mysqli_insert_id($conn);
        $update_order_sql = "UPDATE orders SET payment_status = 'Paid' WHERE order_id = '$order_id'";
        mysqli_query($conn, $update_order_sql);

        // Redirect to order details page
        header("Location: order.php?order_id=$order_id");
        exit;
    } else {
        echo "Error: " . $payment_sql . "<br>" . mysqli_error($conn);
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="checkout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include('header.php'); ?>
    
    <main>
        <div class="container">
            <h2>Checkout</h2>
            
            <p>Order ID: <?= htmlspecialchars($order_id) ?></p><br>
            <p>Total: <?= number_format($order_data['total'], 2) ?> THB</p><br>

            <form action="checkout.php?order_id=<?= htmlspecialchars($order_id) ?>" method="POST" enctype="multipart/form-data">
                <label for="payment_method">Choose Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
                
                <div class="bank-details">
                    <p><strong>Bank Name:</strong> K Bank</p>
                    <p><strong>Account Name:</strong> Jewelry Store Co.</p>
                    <p><strong>Account Number:</strong> 123-456-7890</p>
                </div>
                
                <label for="payment_slip">Upload Payment Slip:</label>
                <input type="file" name="payment_slip" id="payment_slip" accept="image/*" required>

                <button type="submit" class="checkout-btn">Pay Now</button>
            </form>

        </div>
    </main>

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
