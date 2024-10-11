<?php
session_start();
include("connectdb.php");

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// ดึงข้อมูลผู้ใช้
$user_id = $_SESSION['user_id'];

// ดึงข้อมูลจากตาราง users
$user_sql = "SELECT username, phone, address, name FROM users WHERE id = '$user_id'";
$user_result = mysqli_query($conn, $user_sql);
$user_data = mysqli_fetch_assoc($user_result);

// ดึงข้อมูลจากฐานข้อมูลเมื่อยืนยันการสั่งซื้อ
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['total'])) {
    $total_price = $_POST['total'];

    // ดึงข้อมูลของผู้ใช้จาก $user_data
    $username = $user_data['username'];
    $phone = $user_data['phone'];
    $address = $user_data['address'];
    $name = $user_data['name'];
    
    // เพิ่มคำสั่งซื้อใหม่ในฐานข้อมูล
    $order_sql = "INSERT INTO orders (user_id, username, phone, address, name, total, order_date) 
                  VALUES ('$user_id', '$username', '$phone', '$address', '$name', '$total_price', NOW())";
    
    if (mysqli_query($conn, $order_sql)) {
        $order_id = mysqli_insert_id($conn); // ดึง ID คำสั่งซื้อที่เพิ่งเพิ่ม

        // เพิ่มสินค้าทั้งหมดจากตะกร้าลงใน order_items
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            $product_sql = "SELECT * FROM product WHERE p_id = '$product_id'";
            $result = mysqli_query($conn, $product_sql);
            $product = mysqli_fetch_assoc($result);

            $price = $product['p_price'];
            $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price) 
                         VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            mysqli_query($conn, $item_sql);
        }

        // ล้างตะกร้าหลังจากทำรายการเสร็จ
        unset($_SESSION['cart']);
        
        // เปลี่ยนเส้นทางไปหน้า order.php
        header("Location: order.php?order_id=$order_id");
        exit;
    } else {
        echo "Error: " . $order_sql . "<br>" . mysqli_error($conn);
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
    <link rel="stylesheet" href="cart.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>

<?php
// Count the total items in the cart
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

<?php include('header.php'); ?>

<main>
    <div class="container-co">
        <h2>Your Cart</h2>

        <?php
        if (empty($_SESSION['cart'])) {
            echo "<p>Your cart is currently empty.</p>";
        } else {
            $total = 0;
        ?>

        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $product_id => $quantity) { 
                    $sql = "SELECT * FROM product WHERE p_id = $product_id";
                    $result = mysqli_query($conn, $sql);
                    $product = mysqli_fetch_assoc($result);
                    $subtotal = $product['p_price'] * $quantity;
                    $total += $subtotal;
                ?>
                <tr>
                    <td><?= htmlspecialchars($product['p_name']); ?></td>
                    <td><img src="images/<?= htmlspecialchars($product_id); ?>.jpg" alt="<?= htmlspecialchars($product['p_name']); ?>" style="width: 100px; height: auto;"></td>
                    <td><?= number_format($product['p_price'], 2); ?> THB</td>
                    <td>
                        <form action="update_cart.php" method="POST" class="cart-update-form">
                            <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                            <input type="number" name="quantity" value="<?= $quantity; ?>" min="1">
                            <button type="submit" class="update2-btn">Update</button>
                        </form>
                    </td>
                    <td><?= number_format($subtotal, 2); ?> THB</td>
                    <td>
                        <form action="remove_from_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $product_id; ?>">
                            <button type="submit" class="remove-btn">Remove</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table><br>

        <div class="cart-summary">
            <h3>Total: <?= number_format($total, 2); ?> THB</h3>

            <!-- Form that sends to this page for placing an order -->
            <form action="" method="POST">
                <input type="hidden" name="total" value="<?= $total; ?>">
                <button type="submit" class="checkout-btn">Order confirmation</button>
            </form>
        </div>

        <?php } ?>
    </div>
</main>

</body>
</html>
