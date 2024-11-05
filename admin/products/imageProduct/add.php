<?php
    include "../../../client/DBUntil.php";
    session_start();
    $role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    //     header("Location: ../../../client/login.php"); // Chuyển hướng đến trang đăng nhập
    //     exit;
    // }
    $dbHelper = new DBUntil();
    // $idUser = $_SESSION['idUser'];
    $idUser = 1;

    // var_dump($idUser);
    $users = $dbHelper->select("SELECT * FROM users WHERE idUser = ?", array($idUser));
    $image = $users[0]['image'];
    $name = $users[0]['name'];
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";  

    $errors = [];
    $idProduct = $_GET['id'];
    $images = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_FILES['images'])) {
            $imageNames = [];
            $target_dir = __DIR__ . "/../../../admin/products/image/";
            $IMAGE_TYPES = array('jpg', 'jpeg', 'png' , 'gif' , 'webp');
            
            foreach ($_FILES['images']['name'] as $key => $imageName) {
                $target_file = $target_dir . basename($imageName);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
                // Kiểm tra loại tệp
                if (!in_array($imageFileType, $IMAGE_TYPES)) {
                    $errors['images'][] = "$imageName có loại tệp không hợp lệ.";
                }
    
                // Kiểm tra kích thước tệp
                if ($_FILES['images']["size"][$key] > 10000000000) {
                    $errors['images'][] = "$imageName có kích thước quá lớn.";
                }
    
                // Nếu không có lỗi, tiến hành tải tệp lên
                if (empty($errors)) {
                    if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                        $imageNames[] = htmlspecialchars(basename($imageName));
                    } else {
                        $errors['images'][] = "Có lỗi xảy ra khi tải $imageName.";
                    }
                }
            }
    
            // Chèn vào cơ sở dữ liệu
            foreach ($imageNames as $image) {
                $data = [
                    'idProduct' => $idProduct,
                    'namePicProduct' => $image,
                ];
                $dbHelper->insert('picproduct', $data);
            }
    
            if(empty($errors)) {
                $_SESSION['success'] = "Thêm hình ảnh thành công";
                header("Location: ./list.php?id=$idProduct");
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
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../categories/list.php"
                        class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th-list me-2"></i> Categories
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../sub_categories/list.php"
                        class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th me-2"></i> Category Products
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../products/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-boxes me-2"></i> Products
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../orders/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-shopping-cart me-2"></i> Orders
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../users/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../comments/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-comments me-2"></i> Comments
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../coupons/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tags me-2"></i> Coupons
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="../../settings.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-cogs me-2"></i> Settings
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
                                <h3 class="card-title">Products</h3>

                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Thêm hình ảnh</h1>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="mt-2">
                                                <label for="" class="fw-bold me-2">Hình ảnh:</label>
                                                <input type="file" name="images[]" id="imageInput" multiple>
                                                <?php if (isset($errors['images[]'])) echo "<span class='text-danger'>errors['images[]']</span>"; ?>
                                            </div>
                                            <div id="preview"></div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="list.php?id=<?php echo $idProduct ?>"
                                                    class="return btn text-white btn-dark bg-gradient">
                                                    <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                    Quay lại
                                                </a>

                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Thêm
                                                    hình ảnh</button>
                                            </div>
                                        </form>
                                        <script>
                                        document.getElementById('imageInput').addEventListener('change', function(
                                        event) {
                                            // Xóa các hình ảnh trước đó
                                            const preview = document.getElementById('preview');
                                            preview.innerHTML = '';

                                            // Lấy danh sách các tệp được chọn
                                            const files = event.target.files;

                                            // Duyệt qua từng tệp và hiển thị
                                            for (let i = 0; i < files.length; i++) {
                                                const file = files[i];

                                                // Kiểm tra xem tệp có phải là hình ảnh không
                                                if (file.type.startsWith('image/')) {
                                                    const reader = new FileReader();

                                                    // Khi tệp đã được đọc, tạo phần tử hình ảnh và thêm vào vùng preview
                                                    reader.onload = function(e) {
                                                        const img = document.createElement('img');
                                                        img.src = e.target.result;
                                                        img.style.width =
                                                        '150px'; // Điều chỉnh kích thước hình ảnh
                                                        img.style.margin = '10px';
                                                        preview.appendChild(img);
                                                    }

                                                    // Đọc tệp dưới dạng URL
                                                    reader.readAsDataURL(file);
                                                }
                                            }
                                        });
                                        </script>


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