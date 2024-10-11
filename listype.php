<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>หน้าแก้ไขประเภทสินค้า</title>
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
        h1 {
            margin-bottom: 20px;
        }
        .container {
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 10px;
        }
        .table th {
            font-size: 1.2rem; /* ขนาดตัวอักษรของหัวข้อ */
            text-align: center; /* จัดกึ่งกลางหัวข้อ */
            vertical-align: middle;
        }
        .table td {
            text-align: center; /* จัดข้อมูลให้อยู่กึ่งกลางในแต่ละเซลล์ */
            vertical-align: middle;
        }
        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
        /* กำหนดความกว้างของคอลัมน์ */
        .table td:nth-child(1), .table td:nth-child(2) {
            width: 80px; /* กำหนดความกว้างคอลัมน์แก้ไขและลบ */
        }
    </style>
</head>

<body>

<div class="container">
    <h1 class="text-center">หน้าแก้ไขประเภทสินค้า</h1>
    <a href="insert_type.php" class="btn btn-success mb-3">
        <i class="bi bi-plus-circle me-2"></i>เพิ่มประเภทสินค้า
    </a>
    <div class="table-responsive">
        <table id="myTable" class="table table-striped table-bordered table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                    <th>ID</th>
                    <th>ชื่อประเภทสินค้า</th>
                </tr>
            </thead>
            <tbody>
            <?php
            include_once("connectdb.php");
            $sql = "SELECT * FROM category ORDER BY c_id ASC";
            $rs = mysqli_query($conn, $sql);
            while ($data = mysqli_fetch_array($rs)) {
            ?>
                <tr>
                    <td><a href="edit_type.php?c_id=<?=$data['c_id'];?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>แก้ไข</a></td>
                    <td><a href="delete_type.php?c_id=<?=$data['c_id'];?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบประเภทสินค้านี้?')" class="btn btn-danger btn-sm"><i class="bi bi-trash me-1"></i>ลบ</a></td>
                    <td><?=$data['c_id'];?></td>
                    <td><?=$data['c_name'];?></td>
                </tr>
            <?php
            }
            mysqli_close($conn);
            ?>
            </tbody>
        </table>
    </div>
</div>

<br><br>

</body>
</html>
