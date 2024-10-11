<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ข้อมูลผู้ใช้</title>

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
        <h1 class="text-center">ข้อมูลผู้ใช้</h1>
        <div class="mb-3 text-start"> 
            <a href="insert_user.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> เพิ่มข้อมูลผู้ใช้</a>
        </div>
        <div class="table-responsive">
            <table id="myTable" class="table table-striped table-bordered table-hover" style="width:100%">
                <thead>
                    <tr>
                        <th>แก้ไข</th>
                        <th>ลบ</th>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                include_once("connectdb.php");
                $sql = "SELECT id, username, password, email, phone, address, name FROM users ORDER BY id ASC";
                $rs = mysqli_query($conn, $sql);
                while ($data = mysqli_fetch_array($rs)) {
                ?>
                    <tr>
                        <td><a href="edit_user.php?id=<?=$data['id'];?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil"></i> แก้ไข</a></td>
                        <td><a href="delete_user.php?id=<?=$data['id'];?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลลูกค้า?')" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i> ลบ</a></td>
                        <td><?=$data['id'];?></td>
                        <td><?=$data['username'];?></td>
                        <td><?=$data['password'];?></td>
                        <td><?=$data['email'];?></td>
                        <td><?=$data['phone'];?></td>
                        <td><?=$data['address'];?></td>
                        <td><?=$data['name'];?></td>
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
