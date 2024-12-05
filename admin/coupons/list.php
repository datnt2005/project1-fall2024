<?php
include_once('../../client/DBUntil.php');
session_start();
$dbHelper = new DBUntil();
$login_success = false;

// Kiểm tra xem người dùng đã tìm kiếm chưa
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
$coupons = [];

// Thực hiện tìm kiếm nếu có từ khóa, nếu không thì lấy tất cả danh mục
if (!empty($searchTerm)) {
    $coupons = $dbHelper->select("SELECT * FROM coupons WHERE nameCoupon LIKE ?", array('%' . $searchTerm . '%'));
} else {
    $coupons = $dbHelper->select("SELECT * FROM coupons");
}
if (isset($_SESSION['success']) && $_SESSION['success'] === true) {
    $login_success = true;
    unset($_SESSION['success']); // Unset the session variable to avoid repeated alerts
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
<?php
if (isset($_SESSION['success'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{$_SESSION['success']}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>";
    unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
}
?>    
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
                                <h3 class="card-title">Khuyến mãi</h3>
                            </div>
                            <div class="card-body">
                            <div class="d-flex justify-content-between mt-4">
                                    <div class="search-items">
                                        <form method="GET">
                                            <input class="input-search mb-3" type="search" name="search" id="search"
                                                placeholder="Tìm kiếm" style="height: 35px;">
                                            <button type="submit" class="btn btn-dark bg-gradient text-white"
                                                style="height: 35px">Search</button>
                                        </form>
                                    </div>
                                    <div class="add-category">
                                        <a href="./add.php" class="btn btn-primary px-4 mb-3 mx-5 py-2">Thêm giảm giá</a>
                                    </div>
                                </div>
                                <table class="table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Mã giảm giá</th>
                                            <th>Số lượng</th>
                                            <th>Giảm giá</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            foreach ($coupons as $items) { 
                                        ?>
                                        <tr>
                                            <td><?php echo $items['idCoupon']?></td>
                                            <td><?php echo $items['nameCoupon']?></td>
                                            <td><?php echo $items['codeCoupon']?></td>
                                            <td><?php echo $items['quantityCoupon']?></td>
                                            <td><?php echo $items['discount']?> %</td>
                                            <td><?php echo $items['startDate']?></td>
                                            <td><?php echo $items['endDate']?></td>
                                            <td>
                                                <div class="action">
                                                    <a href="update.php?id=<?php echo $items['idCoupon']; ?>"
                                                        class="update_coupons text-decoration-none fw-bold mx-2" ><i class="fs-5 fa-solid fa-pen-nib"></i></a>
                                                        <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="confirmDelete('<?php echo $items['idCoupon'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>
                                            </td>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</body>
<script src="../js/script.js"></script>
</html>