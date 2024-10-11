<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            background-color: #ffe5e5; /* สีพื้นหลังที่ดูสบายตา */
            font-family: 'Arial', sans-serif; /* เปลี่ยนฟอนต์ให้เรียบง่าย */
        }
        .sidebar {
            background-color: #ffffff; /* สีพื้นหลังของ sidebar */
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* เงาให้ sidebar */
        }
        .content-area {
            padding: 20px;
            overflow-y: auto;
            height: calc(100vh - 56px); /* ให้ความสูงของ content area เท่ากับความสูงเต็มจอ ลบ header */
            background-color: #ffffff; /* สีพื้นหลังของ content area */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* เงาให้ content area */
            border-radius: 8px; /* ทำมุมให้มน */
        }
        header {
            background-color: #dc3545; /* สีพื้นหลังของ header */
            color: white; /* ตัวอักษรสีขาว */
        }
        footer {
            background-color: #f8f9fa; /* สีพื้นหลังของ footer */
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row h-100">
            <nav class="col-md-2 sidebar">
                <h4>เมนูหลัก</h4>
                <button class="btn btn-danger mb-3 w-100 text-start" onclick="loadEditProduct()">
                    <i class="bi bi-backpack me-2"></i> จัดการสินค้า
                </button>
                <button class="btn btn-danger mb-3 w-100 text-start" onclick="loadlistype()">
                    <i class="bi bi-basket-fill me-2"></i> จัดการประเภทสินค้า
                </button>
                <button class="btn btn-danger mb-3 w-100 text-start" onclick="loadlistoders()">
                    <i class="bi bi-box-seam-fill me-2"></i> ข้อมูลคำสั่งซื้อ
                </button>
                <button class="btn btn-danger mb-3 w-100 text-start" onclick="loadlistuser()">
                    <i class="bi bi-person-circle me-2"></i> จัดการข้อมูลลูกค้า
                </button>
                <button class="btn btn-danger mb-3 w-100 text-start" onclick="window.location.href='home.php';">
                    <i class="bi bi-person-circle me-2"></i> ออกจากระบบ
                </button>
            </nav>

            <!-- Content area for displaying edit product page -->
            <div class="col-md-10 content-area" id="contentArea">
                <h2 class="h5">ระบบจัดการหลังบ้าน</h2>
            </div>
        </div>
    </div>
    <script>
        function loadEditProduct() {
            $("#contentArea").load("editproduct.php", function(response, status, xhr) {
                if (status == "error") {
                    var msg = "เกิดข้อผิดพลาด: ";
                    $("#contentArea").html(msg + xhr.status + " " + xhr.statusText);
                }
            });
        }
        
        function loadlistype() {
            $("#contentArea").load("listype.php", function(response, status, xhr) {
                if (status == "error") {
                    var msg = "เกิดข้อผิดพลาด: ";
                    $("#contentArea").html(msg + xhr.status + " " + xhr.statusText);
                }
            });
        }
        function loadinsertproduct() {
            $("#contentArea").load("insert.php", function(response, status, xhr) {
                if (status == "error") {
                    var msg = "เกิดข้อผิดพลาด: ";
                    $("#contentArea").html(msg + xhr.status + " " + xhr.statusText);
                }
            });
        }
        function loadlistoders() {
            $("#contentArea").load("list_order.php", function(response, status, xhr) {
                if (status == "error") {
                    var msg = "เกิดข้อผิดพลาด: ";
                    $("#contentArea").html(msg + xhr.status + " " + xhr.statusText);
                }
            });
        }
        function loadlistuser() {
            $("#contentArea").load("list_user.php", function(response, status, xhr) {
                if (status == "error") {
                    var msg = "เกิดข้อผิดพลาด: ";
                    $("#contentArea").html(msg + xhr.status + " " + xhr.statusText);
                }
            });
        }

        // Load "จัดการสินค้า" page when the document is ready
        $(document).ready(function() {
            loadEditProduct();
        });
    </script>
</body>
</html>
