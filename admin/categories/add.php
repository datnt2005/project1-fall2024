<?php
    include "../../client/DBUntil.php";
    session_start();
    $dbHelper = new DBUntil();
    $errors = [];
    $nameCategory = "";
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['nameCategory']) || empty($_POST['nameCategory'])) {
            $errors['nameCategory'] = "Tên là bắt buộc";
        } else {
            $nameCategory = $_POST['nameCategory'];
        }
    if (empty($errors)) {
        $data = [
            'nameCategory' => $nameCategory,
        ];
        
        $isCreate = $dbHelper->insert('categories', $data);
        if ($isCreate) {
            session_start();
            $_SESSION['success'] = "Bạn đã thêm thành công danh mục mới";
            header("Location: list.php");
            exit();
        } else {
            $errors['database'] = "Thêm danh mục thất bại";
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
                                <h3 class="card-title">Categories</h3>

                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Thêm danh mục</h1>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="name mb-3">
                                                <label for="">Tên danh mục</label>
                                                <input type="text" name="nameCategory" id="nameCategory" class="form-control">
                                                <?php
                                            if(isset($errors['nameCategory'])) {
                                                echo "<span class='text-danger'>$errors[nameCategory] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="list.php" class="return btn text-white btn-dark bg-gradient">
                                                    <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                    Quay lại
                                                </a>

                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Thêm
                                                    danh mục</button>
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