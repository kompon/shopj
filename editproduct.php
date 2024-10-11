<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>หน้าแก้ไขสินค้า</title>

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
        img {
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">แก้ไขสินค้า</h1>
        <div class="mb-3 text-start">
            <a href="insert.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> เพิ่มสินค้า</a>
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table table-striped table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                        <th>Picture</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Detail</th>
                        <th>Price</th>
                        <th>Type</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                include_once("connectdb.php");
                $sql = "SELECT * FROM product AS p LEFT JOIN category AS c ON p.c_id = c.c_id ORDER BY p.p_id ASC";
                $rs = mysqli_query($conn, $sql);
                while ($data = mysqli_fetch_array($rs)) {
                ?>
                    <tr>
                        <td><a href="edit.php?p_id=<?=$data['p_id'];?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> แก้ไข</a></td>
                        <td><a href="delete.php?p_id=<?=$data['p_id'];?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> ลบ</a></td>
                        <td><img src="images/<?=$data['p_id'];?>.<?=$data['p_img'];?>" width="60" alt="<?=$data['p_name'];?>"></td>
                        <td><?=$data['p_id'];?></td>
                        <td><?=$data['p_name'];?></td>
                        <td><?=$data['p_detail'];?></td>
                        <td><?=$data['p_price'];?></td>
                        <td><?=$data['c_id'];?></td>
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
