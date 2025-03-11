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
$idProduct = $_SESSION['idProduct'];
$idPicProduct = $_GET['id'];
  

$listPic= $dbHelper->select("SELECT * FROM picproduct WHERE idPicProduct = ?", [$idPicProduct])[0];
$imagePicProduct = $listPic['namePicProduct'];
$image = "";
$errors = []; // Khai báo mảng lỗi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/../../../admin/products/image/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $IMAGE_TYPES = array('jpg', 'jpeg', 'png', 'gif' , 'webp');
        
        if (!in_array($imageFileType, $IMAGE_TYPES)) {
            $errors['image'] = "Image type must be JPG, JPEG, or PNG.";
        }

        if ($_FILES['image']["size"] > 1000000) {
            $errors['image'] = "Image file size is too large.";
        }

        // If no errors, proceed with file upload
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = htmlspecialchars(basename($_FILES["image"]["name"]));
            } else {
                $errors['image'] = "Sorry, there was an error uploading your file.";
            }
        }
    }else{
        $image = $imagePicProduct;
    }
        if(empty($errors)) {
            $data = [
                'idProduct' => $idProduct,
                'namePicProduct' => $image,
            ];
            $update = $dbHelper->update('picproduct', $data ,"idPicProduct = '$idPicProduct'");

            $_SESSION['success'] = "Cập nhật hình ảnh thành công";
            header("Location: ./list.php?id=$idProduct");
            exit();
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
            </nav> <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Products Images</h3>
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
                                </div>
                                <div class="container">
                                    <div class="image">
                                        <img class="img-fluid rounded mx-auto d-block"
                                            src="../../products/image/<?= htmlspecialchars($imagePicProduct) ?>" width="300px" alt="anh">
                                    </div>
                                    <h3 class="text-center">Update</h3>
                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <input type="file" name="image" id="imageInput" class="mx-auto d-block">
                                        </div>
                                        <?php
                                            if(isset($errors['image'])) {
                                                echo "<span class='text-danger'>$errors[image] </span>";
                                            }
                                        ?>
                                        <div id="preview" class=""></div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <a href="list.php?id=<?= $idProduct?>" class="return btn text-white btn-dark bg-gradient">
                                                <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                Quay lại
                                            </a>
                                            <button type="submit" class="btn btn-dark bg-gradient text-white">Cập
                                                nhật</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    img.style.width = '150px'; // Điều chỉnh kích thước hình ảnh
                    img.style.display = 'block';
                    img.style.marginLeft = 'auto';
                    img.style.marginRight = 'auto'; // Căn giữa hình ảnh
                    img.style.marginTop = '30px'; // Căn giữa hình ảnh

                    preview.appendChild(img);
                }

                // Đọc tệp dưới dạng URL
                reader.readAsDataURL(file);
            }
        }
    });
    </script>
</body>

</html>