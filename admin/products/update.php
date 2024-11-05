<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();

$idProduct = $_GET['id'];
$listProduct = $dbHelper->select("SELECT PR.*,
        SUM(SC.quantityProduct) AS total_quantity
        FROM products PR
        INNER JOIN product_size_color SC ON PR.idProduct = SC.idProduct
        WHERE PR.idProduct = ?", [$idProduct])[0];
$nameProduct = "";
$price = "";
$total_quantity = "";
$description = "";
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['nameProduct']) || empty($_POST['nameProduct'])) {
        $errors['nameProduct'] = "Tên sản phẩm là bắt buộc";
    } else {
        $nameProduct = $_POST['nameProduct'];
    }

    if (!isset($_POST['price']) || empty($_POST['price'])) {
        $errors['price'] = "Giá sản phẩm là bắt buộc";
    } else {
        $price = $_POST['price'];
    }

    if (!isset($_POST['total_quantity']) || empty($_POST['total_quantity'])) {
        $errors['total_quantity'] = "Số lượng sản phẩm là bắt buộc";
    } else {
        $total_quantity = $_POST['total_quantity'];
    }

    if (!isset($_POST['description']) || empty($_POST['description'])) {
        $errors['description'] = "Mô tả sản phẩm là bắt buộc";
    } else {
        $description = $_POST['description'];
    }

    if (count($errors) == 0) {
        $data = [
            'nameProduct' => $nameProduct,
            'price' => $price,

            'description' => $description,
        ];
        $quantity = [
            'quantityProduct' => $total_quantity,
        ];

        $product = $dbHelper->update("products", $data, "idProduct = $idProduct");
        $quantityProduct = $dbHelper->update("product_size_color", $quantity, "idProduct = $idProduct");
        $_SESSION['success'] = "Bạn đã cập nhật thành công";
        header("Location: list.php");
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
                                <h3 class="card-title">Products</h3>
                            </div>
                            <div class="card-body">
                                <div class="row mt-4 bg-light">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Cập nhật sản phẩm</h1>
                                        <span name="database" class="text-danger fs-5">
                                            <?php
                                                if(isset($errors['database'])) {
                                                    echo $errors['database'];
                                                }
                                            ?>
                                        </span>
                                        <form method="POST" action="" enctype="multipart/form-data">
                                            <input type="hidden" name="idUser"
                                                value="<?php echo htmlspecialchars($listProduct['idProduct']); ?>">
                                            <div class="mb-3">
                                                <label for="nameProduct" class="form-label">Tên sản phẩm</label>
                                                <input type="text" name="nameProduct" class="form-control"
                                                    placeholder="Tên sản phẩm"
                                                    value="<?php echo htmlspecialchars($listProduct['nameProduct']); ?>">
                                                <?php
                                            if(isset($errors['nameProduct'])) {
                                                echo "<span class='text-danger'>$errors[nameProduct] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Giá</label>
                                                <input type="number" min="0" name="price" class="form-control" placeholder="Giá"
                                                    value="<?php echo htmlspecialchars($listProduct['price']); ?>">
                                                <?php
                                            if(isset($errors['price'])) {
                                                echo "<span class='text-danger'>$errors[price] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="total_quantity" class="form-label">Số lượng</label>
                                                <input type="number" min="0" name="total_quantity" class="form-control"
                                                    placeholder="Số lượng"
                                                    value="<?php echo htmlspecialchars($listProduct['total_quantity']); ?>">
                                                <?php
                                            if(isset($errors['total_quantity'])) {
                                                echo "<span class='text-danger'>$errors[total_quantity] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="description" class="form-label">Mô tả</label>
                                                <input type="text" name="description" class="form-control"
                                                    placeholder="Môt tả"
                                                    value="<?php echo htmlspecialchars($listProduct['description']); ?>">
                                                <?php
                                            if(isset($errors['description'])) {
                                                echo "<span class='text-danger'>$errors[emdescriptionil] </span>";
                                            }
                                        ?>
                                                <div class="d-flex justify-content-between mt-3">
                                                    <a href="list.php"
                                                        class="return btn text-white btn-dark bg-gradient">
                                                        <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                        Quay lại
                                                    </a>
                                                    <button type="submit"
                                                        class="btn btn-dark bg-gradient text-white">Cập
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