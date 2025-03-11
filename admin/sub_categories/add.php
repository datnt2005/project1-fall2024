<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();
$errors = [];
$nameSubCategory = "";
$categories = $dbHelper->select("SELECT * FROM categories");
$idCategory = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nameSubCategory']) || empty($_POST['nameSubCategory'])) {
        $errors['nameSubCategory'] = "Tên là bắt buộc";
    } else {
        $nameSubCategory = $_POST['nameSubCategory'];
    }

    if (!isset($_POST['idCategory']) || empty($_POST['idCategory'])) {
        $errors['idCategory'] = "Danh mục là bắt buộc";
    } else {
        $idCategory = $_POST['idCategory'];
    }
    if (empty($errors)) {
        $data = [
            'nameSubCategory' => $nameSubCategory,
            'idCategory' => $idCategory
        ];
        $isCreate = $dbHelper->insert("subcategory", $data);
        if ($isCreate) {
            // Redirect to the list page
            $_SESSION['success'] = "Bạn đã thêm thành công danh mục con mới";
            header("Location: list.php");
            exit();
        } else {
            $errors['database'] = "Failed to create new subcategory";
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Danh mục con</h3>
                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Thêm danh mục con</h1>
                                        <form action="" method="POST">
                                            <div class="nameSubCategory mb-3">
                                                <label for="">Tên danh mục con</label>
                                                <input type="text" name="nameSubCategory" id="nameSubCategory" class="form-control">
                                                <?php
                                                if(isset($errors['nameSubCategory'])) {
                                                    echo "<span class='text-danger'>$errors[nameSubCategory]</span>";
                                                }
                                                ?>
                                            </div>
                                            <div class="idCategory mb-3">
                                                <label for="">Nhóm danh mục</label>
                                                <select name="idCategory" id="idCategory" class="form-control">
                                                    <option value="">-- Chọn nhóm danh mục --</option>
                                                    <?php foreach ($categories as $category) {
                                                        echo "<option value='" . $category['idCategory'] . "'>" . $category['nameCategory'] . "</option>";
                                                    } ?>
                                                </select>
                                                <?php
                                                if(isset($errors['idCategory'])) {
                                                    echo "<span class='text-danger'>$errors[idCategory]</span>";
                                                }
                                                ?>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="list.php" class="return btn text-white btn-dark bg-gradient">
                                                    <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                    Quay lại
                                                </a>
                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Thêm danh mục</button>
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
