<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();

// Kiểm tra xem người dùng đã tìm kiếm chưa
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
$subCategories = [];

// Thực hiện tìm kiếm nếu có từ khóa, nếu không thì lấy tất cả danh mục
if (!empty($searchTerm)) {
    $subCategories = $dbHelper->select("SELECT * FROM subcategory INNER JOIN categories ON subcategory.idCategory = categories.idCategory WHERE nameSubCategory LIKE ?", array('%' . $searchTerm . '%'));
} else {
    $subCategories = $dbHelper->select("SELECT * FROM subcategory INNER JOIN categories ON subcategory.idCategory = categories.idCategory");
}         
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
    <?php
if (isset($_SESSION['success'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{$_SESSION['success']}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>";
    unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
}
?>
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
                                <h3 class="card-title">Category Products</h3>
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
                                    <div class="add-category">
                                        <a href="./add.php" class="btn btn-primary px-4 mb-3 mx-5 py-2">Thêm danh mục
                                            con</a>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Id Category Products</th>
                                            <th>Name Category Products</th>
                                            <th>Name Category</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($subCategories as $subCategories) { ?>
                                        <tr>
                                            <td><?php echo $subCategories['idSubCategory']?></td>
                                            <td><?php echo $subCategories['nameSubCategory']?></td>
                                            <td><?php echo $subCategories['nameCategory']?></td>
                                            <td>
                                                <div class="action">
                                                    <a href="update.php?id=<?php echo $subCategories['idSubCategory']; ?>"
                                                        class="update_product text-decoration-none fw-bold mx-2"><i class="fs-5 fa-solid fa-pen-nib"></i></a>
                                                    <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="confirmDelete('<?php echo $subCategories['idSubCategory'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>