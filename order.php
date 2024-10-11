<?php
session_start();
include('connectdb.php');

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id']; // ไอดีของผู้ใช้ปัจจุบัน

// ดึงข้อมูลคำสั่งซื้อทั้งหมดของผู้ใช้จากตาราง orders
$order_sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY order_date DESC";
$order_result = mysqli_query($conn, $order_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jewelry Store</title>
    <link rel="stylesheet" href="orders.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    
<?php include('header.php'); ?>

<main>
    <div class="orders-container">
        <h2>Your Orders</h2>

        <?php
        if (mysqli_num_rows($order_result) > 0) {
            // มีคำสั่งซื้อแสดงรายการคำสั่งซื้อ
            echo "<table class='orders-table'>";
            echo "<thead><tr><th>Order</th><th>Items</th><th>Address</th><th>Total</th><th>Date</th><th>Payment</th><th>Status</th></tr></thead>";
            echo "<tbody>";

            // ลูปผ่านผลลัพธ์จากการ query
            while ($order = mysqli_fetch_assoc($order_result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($order['order_id']) . "</td>";

                // ดึงข้อมูลสินค้าที่เกี่ยวข้องกับคำสั่งซื้อจาก order_items และ product รวมถึง quantity
                $order_id = $order['order_id'];
                $item_sql = "
                    SELECT oi.product_id, p.p_name, oi.quantity
                    FROM order_items oi
                    JOIN product p ON oi.product_id = p.p_id
                    WHERE oi.order_id = '$order_id'
                ";
                $item_result = mysqli_query($conn, $item_sql);
                
                // สร้างเซลล์สำหรับการแสดงรายการสินค้าด้านหลัง order_id
                echo "<td>";
                if (mysqli_num_rows($item_result) > 0) {
                    echo "<div class='order-items'>";
                    // ลูปผ่านสินค้าของคำสั่งซื้อแต่ละรายการ
                    while ($item = mysqli_fetch_assoc($item_result)) {
                        echo "<div class='order-item'>";
                        echo "<img src='images/" . htmlspecialchars($item['product_id']) . ".jpg' style='width: 50px; height: auto;' alt='" . htmlspecialchars($item['p_name']) . "'>";
                        echo "<span>" . htmlspecialchars($item['p_name']) . " (Qty: " . htmlspecialchars($item['quantity']) . ")</span>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "No items.";
                }
                echo "</td>";

                echo "<td>" . htmlspecialchars($order['address']) . "</td>";
                echo "<td>" . number_format($order['total'], 2) . " THB</td>";
                echo "<td>" . htmlspecialchars($order['order_date']) . "</td>";
                
                // ตรวจสอบสถานะการชำระเงิน
                if ($order['payment_status'] == 'Paid') {
                    echo "<td><span class='payment-success'>Payment Successful</span></td>";
                } else {
                    echo "<td><a href='checkout.php?order_id=" . $order['order_id'] . "' class='btn-pay'>Pay Now</a></td>";
                    echo "<td><a href='delete_order.php?order_id=" . $order['order_id'] . "' class='btn-delete'>Delete</a></td>";
                }

                // แสดงสถานะคำสั่งซื้อ
                echo "<td>" . htmlspecialchars($order['status_order']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody></table>";
        } else {
            echo "<p>You have no orders yet.</p>";
        }
        ?>

    </div>
</main>

<?php
// ปิดการเชื่อมต่อฐานข้อมูล
mysqli_close($conn);
?>

</body>
</html>
