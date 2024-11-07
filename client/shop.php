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
                        <aside class="sidebar">
                            <div class="sidebar">
                                <div class="sidebar-product-general pt-4 ">
                                    <ul class="px-0 heading">
                                        <li class="category-item  fw-bold fs-4">
                                            DANH MỤC SẢN PHẨM
                                        </li>
                                    </ul>
                                    <ul class="category-list px-0">
                                        <li class="category-item mt-4">
                                            <a href="shop.php?category=<?php echo $category['idCategory'] ?>"
                                                class="fw-bold fs-5 text-uppercase">Hạt
                                                dinh dưỡng</a>
                                        </li>

                                        <li class="category-item subcategory">
                                            <a href="shop.php?view=<?php echo $subcategory['idSubCategory'] ?>"
                                                class="fw-normal">Hạt
                                                Macca</a>
                                        </li>
                                        <li class="category-item subcategory">
                                            <a href="shop.php?view=<?php echo $subcategory['idSubCategory'] ?>"
                                                class="fw-normal">Hạt
                                                Điều</a>
                                        </li>
                                        <li class="category-item subcategory">
                                            <a href="shop.php?view=<?php echo $subcategory['idSubCategory'] ?>"
                                                class="fw-normal">Hạt Dẻ
                                                cười</a>
                                        </li>
                                        <li class="category-item mt-4">
                                            <a href="shop.php?category=<?php echo $category['idCategory'] ?>"
                                                class="fw-bold fs-5 text-uppercase">Cho
                                                Mẹ bầu</a>
                                        </li>
                                        <li class="category-item subcategory">
                                            <a href="shop.php?view=<?php echo $subcategory['idSubCategory'] ?>"
                                                class="fw-normal">Ngũ
                                                Cốc</a>
                                        </li>
                                        <li class="category-item subcategory">
                                            <a href="shop.php?view=<?php echo $subcategory['idSubCategory'] ?>"
                                                class="fw-normal">Sữa
                                                Hạt</a>
                                        </li>
                                    </ul>
                                    <ul class="mt-4 px-0">
                                        <li class="category-item">
                                            <a href="shop.php?view=Dog_products"
                                                class="fw-bold fs-5 text-uppercase">Giá</a>
                                        </li>
                                        <li class="category-item  price"><a
                                                href="../client/shop.php?min_price=0&amp;max_price=5000000">0k
                                                - 100k</a></li>
                                        <li class="category-item price"><a
                                                href="../client/shop.php?min_price=5000000&amp;max_price=10000000">100k
                                                - 200k</a></li>
                                        <li class="category-item price"><a
                                                href="../client/shop.php?min_price=10000000&amp;max_price=15000000">200k
                                                - 300k</a></li>
                                        <li class="category-item price"><a
                                                href="../client/shop.php?min_price=15000000&amp;max_price=20000000">300k
                                                - 400k</a></li>
                                        <li class="category-item price"><a
                                                href="../client/shop.php?min_price=20000000&amp;max_price=30000000">400k
                                                - 500k</a></li>
                                        <li class="category-item price"><a
                                                href="../client/shop.php?min_price=30000000&amp;max_price=40000000">500k
                                                - 1000k</a></li>
                                    </ul>
                                </div>
                            </div>
                        </aside>
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
                            <div class="row mt-5">
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-1" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price fs-5">135.000 VNĐ</p>
                                                <div class="evaluate d-flex justify-content-start px-5 ">
                                                    <i class="fas fa-star text-warning mt-2 me-1 mx-2"></i>
                                                    <p class="fw-medium text-dark fs-5">4.5</p>
                                                    <div class="bought d-flex justify-content-start mx-4 mt-1 ">
                                                        <p class=" text-dark fs-6">Đã bán 2.4k</p>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 text-center products">
                                    <a class="text-decoration-none "
                                        href="detailProduct.php?id=<?= $product['idProduct'] ?>">
                                        <div class="product-items border border-2">
                                            <div class="image-product">
                                                <img class="w-100 mt-2" src="./images/Untitled-2-1.png" alt>
                                            </div>
                                            <div class="name-price text-center">
                                                <span class="text-dark fw-bold fs-5">Hạt dẻ cười</span>
                                                <p class="fw-bold price pt-2 fs-5">135.000 VNĐ</p>
                                            </div>
                                            <!-- <div class="add-cart display-block">
                                                    <button type="button"
                                                        class="btn btn-primary">Mua
                                                        hàng</button>
                                                </div> -->
                                        </div>
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>