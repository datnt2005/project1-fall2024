<?php
    include "../../client/DBUntil.php";
    session_start();
    $dbHelper = new DBUntil();
    $categories = $dbHelper->select("SELECT * FROM subcategory");
    $errors = [];
    

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
       
        if(!isset($_POST['name_product']) || empty($_POST['name_product'])) {
                $errors['name_product'] = "Đây là trường bắt buộc <br>" ;
        }
        
        if(!isset($_POST['description']) || empty($_POST['description'])) {
            $errors['description'] = "Đây là trường bắt buộc <br>" ;
        }
        if(!isset($_POST['categories']) || empty($_POST['categories'])) {
            $errors['categories'] = "Đây là trường bắt buộc <br>" ;
        }

        if (count($errors) == 0) {
            $data = [
                'nameProduct' => $_POST['name_product'],
                'description' => $_POST['description'],
                'idSubCategory' => $_POST['categories'],
            ];
            $lastInsertId = $dbHelper->insert('products', $data);
            $id = $dbHelper->lastInsertId();

            $_SESSION['success'] = "Bạn đã thêm thành công";
            header("Location: ./color_size./add.php?id=$id");
            exit();
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
                                <h3 class="card-title">Sản phẩm</h3>

                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Thêm sản phẩm</h1>
                                        <form action="" method="post">
                                            <div class="name-product">
                                                <label for="">Tên sản phẩm</label>
                                                <input type="text" name="name_product" id="name_product"
                                                    class="form-control">
                                                <?php
                                            if(isset($errors['name_product'])) {
                                                echo "<span class='text-danger'>$errors[name_product] </span>";
                                            }
                                        ?>
                                            </div>
                                            
                                            <div class="description-product">
                                                <label for="">Mô tả</label>
                                                <textarea name="description" id="description"
                                                    class="form-control"></textarea>
                                                <?php
                                            if(isset($errors['description'])) {
                                                echo "<span class='text-danger'>$errors[description] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="cate-product">
                                                <label for="">Danh mục</label>
                                                <select name="categories" id="" class="form-select">
                                                    <option value="">Chọn danh mục</option>
                                                    <?php foreach ($categories as $cat) { ?>
                                                    <option value="<?php echo $cat['idSubCategory'] ?>">
                                                        <?php echo $cat['nameSubCategory']?></option>
                                                    <?php } ?>
                                                </select>
                                                <?php
                                            if(isset($errors['categories'])) {
                                                echo "<span class='text-danger'>$errors[categories] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="list.php" class="return btn text-white btn-dark bg-gradient">
                                                    <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                    Quay lại
                                                </a>

                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Thêm sản
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