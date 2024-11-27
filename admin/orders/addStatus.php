<?php
    include "../../client/DBUntil.php";
    session_start();
    $dbHelper = new DBUntil();

    // Lấy idOrder từ URL
    $idOrder = $_GET['id'];
    $errors = [];
    $status = "";

    // Truy vấn thông tin đơn hàng từ bảng detailorder và orders
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        // Kiểm tra trạng thái có được chọn hay không
        if (!isset($_POST['status']) || empty($_POST['status'])) {
            $errors['status'] = "Trạng thái là bắt buộc";
        } else {
            $status = $_POST['status'];
        }

        // Nếu không có lỗi thì tiến hành cập nhật
        if (empty($errors)) {
            $data = ["statusOrder" => $status];
            // Thực hiện cập nhật trạng thái đơn hàng dựa trên idOrder
            $updateOrder = $dbHelper->update("orders", $data, "idOrder = $idOrder");

            if ($updateOrder) { // Thay đổi $updateStatus thành $updateOrder
                // Chuyển hướng sau khi cập nhật thành công
                $_SESSION['success'] = "Đơn hàng đã đổi trạng thái thành công.";
                header("Location: list.php");
                exit();
            } else {
                $errors['database'] = "Cập nhật trạng thái đơn hàng thất bại.";
            }
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
    <div id="wrapper">
        <?php include "../includes/sidebar.php" ?>
        <!-- Page Content -->
        <div id="content">
            <?php include "../includes/nav.php" ?>
            <!-- Main Content -->
            <div class="container-fluid">
                <!-- Place your content here -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Orders</h3>

                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Trạng thái đơn hàng</h1>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="status mb-3 p-3 lh-4 fs-5">
                                                <input type="radio" name="status" id="" value="4">  Đơn hàng đang được giao đến bạn <br>
                                                <input type="radio" name="status" id="" value="5">  Giao hàng hàng thành công<br>
                                                <input type="radio" name="status" id="" value="6">  Giao hàng thất bại <br>
                                                <?php
                                            if(isset($errors['status'])) {
                                                echo "<span class='text-danger'>$errors[status] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="list.php" class="return btn text-white btn-dark bg-gradient">
                                                    <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                    Quay lại
                                                </a>

                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Cập nhật</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</body>

</html>