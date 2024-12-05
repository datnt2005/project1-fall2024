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
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
    $idSize = $_GET['id'];
    $sizes = $dbHelper->select("SELECT * FROM Sizes WHERE idSize = ?", [$idSize])[0];
    $errors = [];
    $size = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nameSize']) || empty($_POST['nameSize'])) {
        $errors['nameSize'] = "Kích cỡ là bắt buộc";
    } else {
        $size = $_POST['nameSize'];
    }            
    // If no errors, update data in      the database
    
    if (empty($errors)) {
        $updateData = [
            'nameSize' => $size,
        ];
            $isUpdate = $dbHelper->update("sizes", $updateData, "idSize = $idSize");   
        if ($isUpdate) {
            $_SESSION['success'] = "Cập nhật size thành công";
            header("Location: list.php");
            exit();
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "../../includes/head.php" ?>
<link rel="stylesheet" href="../../css/style.css">

<body>
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
            </nav>
            <!-- Main Content -->
            <div class="container-fluid">
                <!-- Place your content here -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sizes</h3> 
                            </div>
                            <div class="card-body">
                                <div class="row mt-4 bg-light">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Cập nhật màu sắc</h1>
                                        <span name="database" class="text-danger fs-5">
                                            <?php
                                                if(isset($errors['database'])) {
                                                echo $errors['database'];
                                                }
                                            ?>
                                        </span>
                                        <form method="POST" action="" >
                                            <input type="hidden" name="idSize"
                                                value="<?php echo htmlspecialchars($sizes['idSize']); ?>">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Màu sắc</label>
                                                <input type="text" name="nameSize" class="form-control"
                                                    placeholder="Tên người dùng"
                                                    value="<?php echo htmlspecialchars($sizes['nameSize']); ?>">
                                                <?php
                                            if(isset($errors['nameSize'])) {
                                                echo "<span class='text-danger'>$errors[nameSize] </span>";
                                            }
                                        ?>
                                            </div>
                                                <div class="d-flex justify-content-between mt-3">
                                                    <a href="list.php" class="return btn text-white btn-dark bg-gradient">
                                                        <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                        Quay lại
                                                    </a>
                                                    <button type="submit" class="btn btn-dark bg-gradient text-white">Cập
                                                        nhật</button>
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