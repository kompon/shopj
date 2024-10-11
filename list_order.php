<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ข้อมูลคำสั่งซื้อ</title>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>

    <script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
    </script>

    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }
        h1 {
            margin-bottom: 20px;
        }
        .table th {
            font-size: 1.1rem;
            text-align: center;
            vertical-align: middle;
        }
        .table td {
            text-align: center;
            vertical-align: middle;
        }
        .container {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">ข้อมูลคำสั่งซื้อ</h1>
        <div class="table-responsive">
            <table id="myTable" class="table table-striped table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Name</th>
                        <th>Total</th>
                        <th>Order Date</th>
                        <th>Payment Status</th>
                        <th>สถานะ</th>
                        <th>ส่งสินค้า</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                include_once("connectdb.php");
                // แสดงข้อมูลจากตาราง orders
                $sql = "SELECT * FROM orders ORDER BY order_id ASC";
                $rs = mysqli_query($conn, $sql);
                while ($data = mysqli_fetch_array($rs)) {
                ?>
                    <tr>
                        <td><?=$data['user_id'];?></td>
                        <td><?=$data['username'];?></td>
                        <td><?=$data['phone'];?></td>
                        <td><?=$data['address'];?></td>
                        <td><?=$data['name'];?></td>
                        <td><?=$data['total'];?></td>
                        <td><?=$data['order_date'];?></td>
                        <td><?=$data['payment_status'];?></td>
                        <td><?=$data['status_order'];?></td>
                        <td><button class="btn btn-primary" onclick="sendOrder(<?=$data['order_id'];?>)">ส่งสินค้า</button></td>
                        <script>
                        function sendOrder(orderId) {
                            if (confirm("คุณแน่ใจว่าต้องการส่งสินค้านี้?")) {
                                $.ajax({
                                    url: 'update_order_status.php', // URL ของไฟล์ PHP ที่จะอัปเดตสถานะ
                                    type: 'POST',
                                    data: { order_id: orderId },
                                    success: function(response) {
                                        alert(response); // แสดงข้อความจากเซิร์ฟเวอร์
                                        location.reload(); // รีเฟรชหน้าเพื่อดูการเปลี่ยนแปลง
                                    },
                                    error: function() {
                                        alert("เกิดข้อผิดพลาดในการส่งคำสั่งสินค้า");
                                    }
                                });
                            }
                        }
                    </script>
                    </tr>
                <?php
                }
                mysqli_close($conn);
                ?>
                </tbody>
            </table>
        </div>
    </div>

    <br>
</body>
</html>
