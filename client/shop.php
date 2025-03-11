<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
$login_success = false;
// echo ($_SESSION['id']);
if (isset($_SESSION['success'])) {
    $login_success = true;
}
function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . ' đ';
}
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";
$minPrice = isset($_GET['min_price']) ? intval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? intval($_GET['max_price']) : PHP_INT_MAX;
$idCategory = isset($_GET['category']) ? intval($_GET['category']) : null;
$idSubCategory = isset($_GET['view']) ? intval($_GET['view']) : null;

// Bắt đầu truy vấn SQL
$query = "SELECT p.*, MIN(pic.namePicProduct) AS namePic, sctg.nameSubCategory, ctg.nameCategory  ,prds.price
          FROM products p
          INNER JOIN product_size prds ON p.idProduct = prds.idProduct
          JOIN picproduct pic ON p.idProduct = pic.idProduct
          JOIN subcategory sctg ON p.idSubCategory = sctg.idSubCategory
          JOIN categories ctg ON ctg.idCategory = sctg.idCategory
          WHERE prds.price BETWEEN $minPrice AND $maxPrice";

// Lọc theo danh mục nếu có
if ($idCategory !== null) {
    $query .= " AND ctg.idCategory = $idCategory";
}
//lọc theo subdanh mục nếu có
if ($idSubCategory !== null) {
    $query .= " AND sctg.idSubCategory = $idSubCategory";
}
// Lọc theo từ khóa tìm kiếm nếu có
if ($searchTerm !== "") {
    $query .= " AND p.nameProduct LIKE '%$searchTerm%'";
}

// Nhóm và sắp xếp sản phẩm
$query .= " GROUP BY p.idProduct ORDER BY p.idProduct";

// Lấy kết quả
$products = $dbHelper->select($query);
?>
<style>
    .pagination .page-link {
        background-color: white;
        /* Màu nền trắng cho nút không được chọn */
        color: #69BA31;
        /* Màu chữ xanh cho nút không được chọn */
        border: 1px solid #69BA31;
        /* Thêm viền xanh để nổi bật nút */
    }

    .pagination .page-item.active .page-link {
        background-color: #69BA31;
        /* Màu nền xanh cho nút đang active */
        color: white;
        /* Màu chữ trắng khi nút active */
    }

    .pagination .page-item .page-link:hover {
        background-color: #4C8E26;
        /* Màu nền khi hover (di chuột vào) */
        color: white;
        /* Màu chữ trắng khi hover */
    }
</style>
<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<body>

    <?php include "./includes/header.php" ?>

    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến
                với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="container align-items-center">
                <div class="d-flex pt-2">
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ Sản Phẩm</p>
                </div>
            </div>
        </div>
        <div id="shop">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <?php include "./includes/aside.php" ?>

                    </div>
                    <div class="col-md-9 mt-4">
                        <div class="container">
                            <div class="row banner">
                                <h3 class="fw-bold fs-4">Nguồn dinh dưỡng <span class="fw-normal">từ hạt!</span></h3>
                                <div class="banner">
                                    <img src="./images/assorted-nuts-bazzini-500x900-min.png" alt="banner" width="100%"
                                        height="300" class>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <?php foreach ($products as $product) { ?>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none" href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2 position-relative">
                                            <div class="image-product position-relative">
                                                <img class="w-100 mt-1 main-image" src="../admin/products/image/<?= $product['namePic'] ?>"
                                                    alt="Hình ảnh sản phẩm">
                                                <img class="w-100 mt-1 hover-image" src="./images/icon/anh_hat_dieu.jpg"
                                                    alt="Hình ảnh thay thế">
                                                <div class="action-buttons d-flex justify-content-center align-items-center">
                                                    <button class="btn-custom me-2">Mua Ngay</button>
                                                    <button class="btn btn-secondary">
                                                        <i class="fas fa-shopping-cart"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5"><?= $product['nameProduct'] ?></span>
                                                <p class="fw-bold price fs-5"><?= formatCurrencyVND($product['price']) ?></p>
                                                <div class="evaluate d-flex justify-content-start mx-5">
                                                    <i class="fas fa-star text-warning mt-2 me-1"></i>
                                                    <p class="fw-medium text-dark fs-5">4.5</p>
                                                    <div class="bought d-flex justify-content-start mx-4 mt-1">
                                                        <p class="text-dark fs-6">Đã bán 11k</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        
                    </div>

                </div>
    </main>
    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>