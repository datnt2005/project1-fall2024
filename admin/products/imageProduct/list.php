<?php
include "../../../client/DBUntil.php";
session_start();
$role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../../client/login.php"); // Chuyển hướng đến trang đăng nhập
        exit;
    }
$dbHelper = new DBUntil();
$idUser = $_SESSION['idUser'];
// var_dump($idUser);
$users = $dbHelper->select("SELECT * FROM users WHERE idUser = ?", array($idUser));
$image = $users[0]['image'];
$name = $users[0]['name'];
$idProduct = $_GET['id'];
$_SESSION['idProduct'] = $idProduct;
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
$listProducts = [];
$products= $dbHelper->select("SELECT * FROM products WHERE idProduct = ?", array($idProduct));
if (!empty($searchTerm)) {
    $listProducts = $dbHelper->select("SELECT * FROM products prd 
        JOIN picproduct pic ON prd.idProduct = pic.idProduct
        WHERE prd.nameProduct LIKE ? OR PR.idProduct LIKE ?", 
        array('%' . $searchTerm . '%', '%' . $searchTerm . '%'));
} else {
    $listProducts = $dbHelper->select("SELECT * FROM products PR
        JOIN picproduct pic ON PR.idProduct = pic.idProduct
        WHERE PR.idProduct = ?", array($idProduct));
}

// Further processing or output
?>




<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php" ?>
<link rel="stylesheet" href="../../css/style.css">

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
        <div id="sidebar-wrapper" class="bg-dark px-3">
        <ul class="sidebar-nav mt-3 mb-5">
                <li class="sidebar-brand d-flex align-items-center px-5">
                    <div class="logo_sidebar">
                        <a href="../../index.php">
                        <img src="../../../client/images/logo_du_an_1 2.png" width="80" alt="logo">
                        </a>
                    </div>
                </li>
                <li class="sidebar-nav-item mt-4">
                    <a href="../../index.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i> Trang chủ
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../categories/list.php"
                        class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th-list me-2"></i> Danh mục
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../sub_categories/list.php"
                        class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th me-2"></i> Danh mục con
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../products/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-boxes me-2"></i> Sản phẩm
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../orders/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../users/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-users me-2"></i> Người dùng
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../comments/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-comments me-2"></i> Bình luận
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../coupons/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tags me-2"></i> Khuyến mãi
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../settings.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-cogs me-2"></i> Cài đặt
                    </a>
                </li>
            </ul>
        </div>
        <!-- Page Content -->
        <div id="content">
            <nav class="navbar">
                <div class="search-nav mx-5">
                    <form action>
                        <input type="search" name="search" id="search" placeholder="Search...">
                        <button>
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </form>
                </div>
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - User Information -->
                    <li class="nav-item  no-arrow d-flex align-items-center mr-3">
                        <a class="nav-link" href="#" id="user" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small "><?php echo $name ?></span>
                            <i class="fa-regular fa-envelope text-gray-400 fs-5 mr-2 mx-2"></i>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="logout">
                            <a class="nav-link" href="../../../client/index.php" data-toggle="modal"
                                data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw fs-5 mr-2 text-gray-400"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav> <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sản phẩm</h3>
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
                                        <a href="./add.php?id=<?= $idProduct?>" class="btn btn-primary px-4 mb-3 mx-5 py-2">Thêm hình ảnh</a>
                                    </div>
                                </div>
                                
                                <div class="container">
                                    <div class="row">
                                            <h3>Sản phẩm: <b><?php 
                                                                if(!empty($listProducts)){
                                                                    echo $listProducts[0]['nameProduct'];
                                                                }else {
                                                                    echo $products[0]['nameProduct'];
                                                                }
                                                                ?>
                                                            </b>
                                            </h3>
                                            <?php 
                                                if(!empty($listProducts)) {
                                                foreach ($listProducts as $product) { 
                                            ?>
                                        <div class="col-md-3 mt-3">
                                                <div class="img">
                                                    <img src="../image/<?= $product['namePicProduct']?>" style="width: 200px; height: 200px;"  alt="">
                                                    <div class="action mt-3 mx-5 ">
                                                        <a href="update.php?id=<?= $product['idPicProduct'] ?>" class="fw-bold text-primary text-decoration-none mx-3"><i class="fs-5 fa-solid fa-pen-nib"></i></a>
                                                        <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="confirmDelete('<?php echo $product['idPicProduct'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>
                                                </div>
                                                </div>
                                        </div>  
                                        <?php } 
                                            }else{
                                                echo "<p class='text-center'>Sản phẩm chưa có hình ảnh.</p>";
                                            }
                                        ?>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Có, xóa nó!',
        cancelButtonText: 'Không, hủy!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'remove.php?id=' + id;
        }
    });
}
</script>
</html>