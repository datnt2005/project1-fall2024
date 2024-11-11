<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
var_dump($_SESSION['idUser']) ?? null;
$login_success = false;
// echo ($_SESSION['id']);
if (isset($_SESSION['success'])) {
    $login_success = true;
}
?>
<style>
.pagination .page-link {
    background-color: white; /* Màu nền trắng cho nút không được chọn */
    color: #69BA31; /* Màu chữ xanh cho nút không được chọn */
    border: 1px solid #69BA31; /* Thêm viền xanh để nổi bật nút */
}

.pagination .page-item.active .page-link {
    background-color: #69BA31; /* Màu nền xanh cho nút đang active */
    color: white; /* Màu chữ trắng khi nút active */
}

.pagination .page-item .page-link:hover {
    background-color: #4C8E26; /* Màu nền khi hover (di chuột vào) */
    color: white; /* Màu chữ trắng khi hover */
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
                            <?php foreach ($listProducts as $product) { ?>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none" href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2 position-relative">
                                            <div class="image-product position-relative">
                                                <img class="w-100 mt-1 main-image" src="../admin/products/image/<?= $product['namePicProduct'] ?>"
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
                                                        <p class="text-dark fs-6">Đã bán <?= $product['total_quantity'] ?>k</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="pagination-container d-flex justify-content-center">
                            <ul class="pagination">
                                <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
                                    <li class="page-item<?php echo ($i == $page) ? ' active' : ''; ?>">
                                        <a class="page-link" href="shop.php?page=<?php echo $i;
                                                                                    echo $categoryId ? '&category=' . $categoryId : '';
                                                                                    echo $subcategoryId ? '&subcategory=' . $subcategoryId : '';
                                                                                    echo $minPrice ? '&min_price=' . $minPrice : '';
                                                                                    echo $maxPrice ? '&max_price=' . $maxPrice : ''; ?>">
                                            <?php echo $i; ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>

                </div>
    </main>
    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>