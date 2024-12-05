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

    $errors = [];
    $idProductSize = $_GET['id'];
    $listPS = $dbHelper->select("SELECT * FROM product_size PS
                                     JOIN sizes S ON PS.idSize = S.idSize
                                     WHERE idProductSize = ?", [$idProductSize])[0];
    $optionSize = $dbHelper->select("SELECT * FROM sizes ");
    $price = "";
    $size = "";
    $quantity = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['size']) || empty($_POST['size'])) {
            $size = $listPS['idSize'];
        } else {
            $size = $_POST['size'];
        }

        if (!isset($_POST['price']) || empty($_POST['price'])) {
            $errors['price'] = "Giá sản phẩm là bắt buộc";
        } else {
            $price = $_POST['price'];
        }
        if (!isset($_POST['quantity']) || empty($_POST['quantity'])) {
            $errors['quantity'] = "Số lượng sản phẩm là bắt buộc";
        } else {
            $quantity = $_POST['quantity'];
        }

        if (count($errors) == 0) {
        $data = [
            'idSize' => $size,
            'price' => $price,
            'quantityProduct' => $quantity,
        ];
        $updatePSC = $dbHelper->update('product_size', $data, "idProductSize = $idProductSize");
        $idProduct = $_SESSION['idProduct'];
        if ($updatePSC) {
            $_SESSION['success'] = "Cập nhật thành công";
            header("Location: list.php?id=$idProduct");
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
                                <h3 class="card-title">Products Color, Size</h3>

                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Cập nhật size, color</h1>
                                        <form action="" method="POST">
                                            <div>
                                                <label for="" class="fw-bold me-2">Size:</label>
                                                <select id="sizeSelect" name="size" class="form-control w-50">
                                                    <option value=""><?= htmlspecialchars($listPS['nameSize']) ?>
                                                    </option>
                                                    <?php foreach ($optionSize as $size) { ?>
                                                    <option value="<?php echo $size['idSize'] ?>">
                                                        <?php echo $size['nameSize'] ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>



                                            <div class="mt-2">
                                                <label for="" class="fw-bold me-2 mt-2">Giá</label>
                                                <input type="text" placeholder="Price" name="price"
                                                    class="form-control w-50" id="priceInput" required
                                                    value="<?= htmlspecialchars($listPS['price']) ?>">

                                            </div>
                                            <div class="mt-2">
                                                <label for="" class="fw-bold me-2 mt-2">Số lượng</label>
                                                <input type="text" placeholder="Quantity" name="quantity"
                                                    class="form-control w-50" id="quantityInput" required
                                                    value="<?= htmlspecialchars($listPS['quantityProduct']) ?>">

                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="./list.php?id=<?php echo $_SESSION['idProduct'] ?>"
                                                    class="return btn text-white btn-dark bg-gradient">
                                                    <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                    Quay lại
                                                </a>

                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Cập
                                                    nhật sản
                                                    phẩm</button>
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