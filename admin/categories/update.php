<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();
$id = $_GET['id'];
$categories = $dbHelper->select("SELECT * FROM categories WHERE idCategory = ?", [$id])[0];
$errors = [];
$nameCategory = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nameCategory']) || empty($_POST['nameCategory'])) {
        $errors['nameCategory'] = "Tên là bắt buộc";
    } else {
        $nameCategory = $_POST['nameCategory'];
    }
    if (empty($errors)) {
        $updateData = [
            'nameCategory' => $nameCategory,
        ];
            $isUpdate = $dbHelper->update("categories", $updateData, "idCategory = $id");   
        if ($isUpdate) {
            $_SESSION['success'] = "Bạn đã cập nhật thành công";
            header("Location: list.php");
            exit();
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
                                <h3 class="card-title">Danh mục</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4 bg-light">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Cập nhật danh mục</h1>
                                        <span name="database" class="text-danger fs-5">
                                            <?php
                                    if(isset($errors['database'])) {
                                        echo $errors['database'];
                                    }
                                ?>
                                        </span>
                                        <form method="POST" action="" enctype="multipart/form-data">
                                            <input type="hidden" name="idUser"
                                                value="<?php echo htmlspecialchars($categories['idCategory']); ?>">
                                            <div class="mb-3">
                                                <label for="nameCategory" class="form-label">Tên</label>
                                                <input type="text" name="nameCategory" class="form-control"
                                                    placeholder="Tên danh mục"
                                                    value="<?php echo htmlspecialchars($categories['nameCategory']); ?>">
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