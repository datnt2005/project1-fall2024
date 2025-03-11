<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();
$id = $_GET['id'];
$categories = $dbHelper->select("SELECT * FROM categories");
$subcategory = $dbHelper->select("SELECT * FROM subcategory WHERE idSubCategory = ?", [$id])[0];
$nameCategory = $dbHelper->select("SELECT nameCategory FROM categories WHERE idCategory = ?", [$subcategory['idCategory']])[0]['nameCategory'];
$errors = [];
$nameSubCategory = "";
$idCategory = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nameSubCategory']) || empty($_POST['nameSubCategory'])) {
        $errors['nameSubCategory'] = "Tên là bắt buộc";
    } else {
        $nameSubCategory = $_POST['nameSubCategory'];
    }
    if(!isset($_POST['idCategory']) || empty($_POST['idCategory'])) {
        // $errors['idCategory'] = "Danh mục là bắt buộc";
        $idCategory = $subcategory['idCategory'];
    } else {
        $idCategory = $_POST['idCategory'];
    }
    if (empty($errors)) {
        $updateData = [
            'nameSubCategory' => $nameSubCategory,
            'idCategory' => $idCategory
        ];
            $isUpdate = $dbHelper->update("subcategory", $updateData, "idSubCategory = $id");   
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
                                <h3 class="card-title">Danh mục con</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4 bg-light">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Cập nhật danh mục con</h1>
                                        <span name="database" class="text-danger fs-5">
                                            <?php
                                                if(isset($errors['database'])) {
                                                echo $errors['database'];
                                                }
                                            ?>
                                        </span>
                                        <form method="POST" action="">
                                            <input type="hidden" name="idUser"
                                                value="<?php echo htmlspecialchars($subcategory['idSubCategory']); ?>">
                                            <div class="mb-3">
                                                <label for="nameSubCategory" class="form-label">Tên</label>
                                                <input type="text" name="nameSubCategory" class="form-control"
                                                    placeholder="Tên danh mục"
                                                    value="<?php echo htmlspecialchars($subcategory['nameSubCategory']); ?>">
                                                <?php
                                                    if(isset($errors['nameSubCategory'])) {
                                                    echo "<span class='text-danger'>$errors[nameSubCategory] </span>";
                                                    }
                                                ?>
                                            </div>
                                            <div class="idSubCategory mb-3">
                                                <label for="">Nhóm danh mục</label>
                                                <select name="idCategory" id="idCategory" class="form-control">
                                                    <option value="<?php echo $subcategory['idCategory']; ?>">
                                                        -- <?php echo $nameCategory; ?> --
                                                    </option>
                                                    <?php foreach ($categories as $category) {
                                                        echo "<option value='" . $category['idCategory'] . "'>" . $category['nameCategory'] . "</option>";
                                                    } ?>
                                                </select>
                                                <?php
                                                    if(isset($errors['idCategory'])) {
                                                    echo "<span class='text-danger'>$errors[idCategory] </span>";
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