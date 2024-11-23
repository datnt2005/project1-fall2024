<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();
function formatCurrencyVND($number) {
    // Sử dụng number_format để định dạng số tiền mà không có phần thập phân
    return number_format($number, 0, ',', '.') . 'đ';
} 

// Kiểm tra xem người dùng đã tìm kiếm chưa
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
// Thực hiện tìm kiếm nếu có từ khóa, nếu không thì lấy tất cả danh mục
if (!empty($searchTerm)) {
    $listProducts = $dbHelper->select("SELECT PR.*,
        SUM(PS.quantityProduct) AS total_quantity, PS.price AS price,
            (SELECT PI.namePicProduct
            FROM picproduct PI
            WHERE PI.idProduct = PR.idProduct
            ORDER BY PI.idPicProduct
            LIMIT 1) AS namePicProduct
        FROM products PR
        INNER JOIN product_size PS ON PR.idProduct = PS.idProduct 
        WHERE PR.nameProduct LIKE ? 
        GROUP BY PR.idProduct", array('%' . $searchTerm . '%'));
} else {
    $listProducts = $dbHelper->select("SELECT PR.*,
        SUM(PS.quantityProduct) AS total_quantity, PS.price AS price,
            (SELECT PI.namePicProduct
            FROM picproduct PI
            WHERE PI.idProduct = PR.idProduct
            ORDER BY PI.idPicProduct
            LIMIT 1) AS namePicProduct
        FROM products PR
        INNER JOIN product_size PS ON PR.idProduct = PS.idProduct
        GROUP BY PR.idProduct");
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>
<style>
.dropdown-menus {
    display: none;
    list-style: none;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
}

.dropdown-menus li {
    margin: 5px 0;
}

.dropdown-menus li:hover {
    color: #c46a2f;
}

.dropdown-menus li a {
    text-decoration: none;
    color: #000;
}

.action_dad {
    width: 100px;
}
</style>

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
                                <h3 class="card-title">Products</h3>
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
                                    <div class="add-category d-flex align-items-center mb-3">
                                        
                                        <div class="position-relative action_dad ">
                                            
                                            <a href="#" class="update_product 
                                                            text-decoration-none fw-bold mx-2"
                                                onclick="showChange(event, this)">
                                                <i class="fa-solid fa-bars fa-xl text-dark"></i>
                                            </a>
                                            <ul class="dropdown-menus position-absolute top-3 end-0 px-3 py-1"
                                                id="dropdown-menu">
                                                <li><a href="./add.php" class="dropdown-item">Thêm sản phẩm</a></li>
                                                <li><a class="dropdown-item"
                                                        href="./sizes/list.php">Thêm kích cỡ</a></li>
                                                
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Hình ảnh</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>Mô tả</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listProducts as $product) { ?>
                                        <tr class="align-middle">
                                            <td><img src="./image/<?php echo $product['namePicProduct']?>"
                                                    style="width: 100px; height: 100px;" alt="" class="image_list"></td>
                                            <td><?php echo $product['nameProduct']?></td>
                                            <td><?php echo formatCurrencyVND($product['price'])?></td>
                                            <td><?php echo $product['total_quantity']?></td>
                                            <td><?php echo $product['description']?></td>
                                            <td class="action_dad">
                                                <div class="action d-flex">
                                                <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none mx-3"
                                                        onclick="confirmDelete('<?php echo $product['idProduct'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>
                                                    <div class="position-relative">
                                                        <a href="#" class="update_product 
                                                            text-decoration-none fw-bold mx-2"
                                                            onclick="showChange(event, this)">
                                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                                        </a>
                                                        <ul class="dropdown-menus position-absolute top-3 end-0 px-3 py-1"
                                                            id="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="update.php?id=<?php echo $product['idProduct'] ?>">Cập
                                                                    nhật thông tin</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="color_size/list.php?id=<?php echo $product['idProduct'] ?>">Danh
                                                                    sách kích cỡ</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="imageProduct/list.php?id=<?php echo $product['idProduct'] ?>">Danh
                                                                    sách hình ảnh</a></li>
                                                        </ul>
                                                    </div>
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
        <!-- /#page-content-wrapper -->
        <script>
        let openDropdown = null;

        function showChange(event, element) {
            event.preventDefault();

            // Close any currently open dropdowns
            if (openDropdown && openDropdown !== element.nextElementSibling) {
                openDropdown.style.display = 'none';
            }

            // Toggle the clicked dropdown
            let dropdown = element.nextElementSibling;
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                openDropdown = null;
            } else {
                dropdown.style.display = 'block';
                openDropdown = dropdown;
            }
        }
        </script>
    </div>
</body>
</html>