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
    // Sử dụng number_format để định dạng số tiền mà không có phần thập phân
    return number_format($number, 0, ',', '.') . 'đ';
}

$listProducts = $dbHelper->select("SELECT PR.*,
SUM(PS.quantityProduct) AS total_quantity, PS.price AS price,
    (SELECT PI.namePicProduct
    FROM picproduct PI
    WHERE PI.idProduct = PR.idProduct
    ORDER BY PI.idPicProduct
    LIMIT 1) AS namePicProduct
FROM products PR
INNER JOIN product_size PS ON PR.idProduct = PS.idProduct
GROUP BY PR.idProduct LIMIT 8" );
?>
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
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>

            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100"
                        style="height: 400px; object-fit: cover;" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="./images/pngtree-assorted.png" class="d-block w-100"
                        style="height: 400px; object-fit: cover;" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="./images/background.jpg" class="d-block w-100"
                        style="height: 400px; object-fit: cover;" alt="Slide 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container my-5">
            <div class="row">
                <div class="col-md-4">
                    <div class="card h-100" style="background-color: white; height: 148px; width: 100%; padding: 20px;">
                        <div class="row h-100">
                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <img src="./images/fast-shipping-4-710528.png" alt=""
                                    style="width: 100px; height: 100px;">
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                <h6>Cửa hàng siêu tốc!</h6>
                                <p>Tại TP. Hồ Chí Minh 90 phút sau nhận hàng!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100" style="background-color: white; height: 148px; width: 100%; padding: 20px;">
                        <div class="row h-100">
                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <img src="./images/return-of-investment-seo-business-startup-optimization-34141.png"
                                    alt="" style="width: 100px; height: 100px;">
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                <h6>Đổi trả hàng miễn phí!</h6>
                                <p>Đổi, trả hàng miễn phí, giải quyết nhanh chóng!</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100" style="background-color: white; height: 148px; width: 100%; padding: 20px;">
                        <div class="row h-100">
                            <div class="col-md-6 d-flex justify-content-center align-items-center">
                                <img src="./images/eat-routine-539278-1.png" alt=""
                                    style="width: 100px; height: 100px;">
                            </div>
                            <div class="col-md-6 d-flex flex-column justify-content-center">
                                <h6>Ăn thử miễn phí!</h6>
                                <p>Tặng thêm hàng ăn thử miễn phí!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-6">
            <div class="col-md-12 text-center">
                <h4>Chào mừng bạn đến với Vua Hạt cửa hàng Hạt Dinh Dưỡng</h4>
                <p style="max-width: 600px; margin: 0 auto;">
                    Hạt Ngon mang đến cho quý khách những loại hạt dinh dưỡng ngon nhất, hạt Điều từ miền đất đỏ bazan
                    Bình Phước, hạt Hạnh nhân từ Mỹ, Hạt Macca từ Nam Phi...trong những bao bì đẹp, sang trọng nhất, để
                    quý khách thưởng thức trọn vẹn vị ngon của hạt, và trao món quà sức khỏe cho những người thân yêu
                    của mình.
                </p>
            </div>
        </div>
        <br>
        <div style="background-color: #F3EFEF">
            <div class="container my-7">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <br>
                        <h2>Sản Phẩm</h2>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center product-item">
                        <div class="text-center">
                            <img src="./images/icon/—Pngtree—yellow cashew nut illustration_4670632.png"
                                class="d-block w-100" style="height: 80px; object-fit: cover;" alt="">
                            <p class="mb-0">Hạt điều</p>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center product-item">
                        <div class="text-center">
                            <img src="./images/icon/—Pngtree—delicious and delicious specialty macadamia_5601576.png"
                                class="d-block w-100" style="height: 80px; object-fit: cover;" alt="">
                            <p class="mb-0">Hạt Macca</p>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center product-item">
                        <div class="text-center">
                            <img src="./images/icon/png-clipart-brown-nut-almond-single-food-nuts-thumbnail.png"
                                class="d-block w-100" style="height: 80px; object-fit: cover;" alt="">
                            <p class="mb-0">Hạt Hạnh Nhân</p>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center product-item">
                        <div class="text-center">
                            <img src="./images/icon/png-clipart-green-and-brow-nut-pistachio-nut-pistachio-s-food-pistachio-thumbnail.png"
                                class="d-block w-100" style="height: 80px; object-fit: cover;" alt="">
                            <p class="mb-0">Hạt Dẻ Cười</p>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center product-item">
                        <div class="text-center">
                            <img src="./images/icon/png-clipart-nut-euclidean-walnut-food-nuts-thumbnail.png"
                                class="d-block w-100" style="height: 80px; object-fit: cover;" alt="">
                            <p class="mb-0">Hạt Óc Chó</p>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex flex-column align-items-center product-item">
                        <div class="text-center">
                            <img src="./images/icon/png-clipart-pumpkin-seed-pumpkin-seed-cucurbita-food-pumpkin-sunflower-seed-vegetables-thumbnail.png"
                                class="d-block w-100" style="height: 80px; object-fit: cover;" alt="">
                            <p class="mb-0">Hạt Bí</p>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <hr style="width: 700px; margin: 0 auto; border: 1px solid #000;">
            <br>
            <div class="container my-4">
                <div class="row">
                    <?php foreach ($listProducts as $product) { ?>
                        <div class="col-md-3 text-center products">
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
                <div class=" text-center">
                    <button class="hover" style="width: 150px ; height: 50px ;">
                        VIEW ALL</button>
                </div>
                <br>
            </div>

            <br>
            <hr style="width: 700px; margin: 0 auto; border: 1px solid #000;">
        </div>
        <br>
        <div class="container my-3">
            <div class="row">
                <div class="col-md-3">
                    <h2>Latest Blog Posts</h2>
                </div>
                <div class="col-md-9">
                    <hr class="mt-4">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <img src="./images/icon/hat-lanh.png" alt="" style="width: 100%; height: 200px;">
                    <div class="content-box">
                        <p class="mb-0">7 loại hạt dinh dưỡng tốt cho sức khỏe mà bạn nên biết</p>
                    </div>
                    <div class="text-center">
                        <p class="mt-1 username">Vua Hạt</p>
                        <p class="text-muted">Các loại hạt là các nguồn chất xơ tốt. Chúng chứa chất béo không lành, dầu
                            không bão hòa...</p>
                    </div>
                </div>
                <div class="col-md-8">
                    <img src="./images/icon/870X180-HATNGONBANERGIUATRANGCHU.png" alt=""
                        style="width: 100%; height: 200px;">
                    <div class="content-box text-center">
                        <p class="mb-0">Giá trị dinh dưỡng từ các loại hạt</p>
                    </div>
                    <div class="text-center">
                        <p class="mt-1 username">Các loại hạt có nhiều chất xơ, protein tốt và cả chất béo lành mạnh.
                            Các loại hạt mang lại nhiều lợi ích sức khỏe khác nhau - đặc biệt là giảm các nguy cơ dẫn
                            đến mắc về bệnh tim.</p>
                        <p class="text-muted">Hạt là loại thực phẩm rất phổ biến, được sử dụng trong tất cả các chế độ
                            ăn, từ ăn kiêng, keto đến ăn chay. Các loại hạt có chứa nhiều chất béo nhưng là các chất béo
                            lành mạnh, tốt cho sức khỏe của con người.</p>
                    </div>
                </div>
            </div>
        </div>


    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js">

    </script>

</body>

</html>