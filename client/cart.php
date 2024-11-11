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
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ Giỏ hàng</p>
                </div>
            </div>
        </div>

        <section id="cart" height="100%" class="">
            <div class="container">
                <div class="row">
                    <div class="col-md-9">
                        <form method="post">
                            <table class="table table-cart text-center">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th class="text-start">Sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Tạm tính</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="remove-cart d-flex align-items-center justify-content-center">
                                                <a href="?remove=<?php echo $cartItem['idDetailCart']; ?>"
                                                    class="fw-bold text-decoration-none text-warning">Xóa</a>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="detailProduct.php?id=<?php echo $cartItem['idProduct']; ?>"
                                                class="name-products text-decoration-none">
                                                <div class="cart-products d-flex align-items-center">

                                                    <div class="image-products">
                                                        <img src="./images/Untitled-2-1.png" alt="Items" class="w-100">

                                                    </div>
                                                    <div class="product-content mx-2">

                                                        <p class="product-name text-dark line-clamp-1 fw-bold fs-6">Hạt
                                                            Dẻ Cười - 250g</p>
                                                    </div>

                                                </div>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="price">135.000 VNĐ</span>
                                        </td>
                                        <td>
                                            <div class="quantity">
                                                <button class="prev"
                                                    onclick="updateQuantity(this, -1, 4, 6,23)">-</button>
                                                <input type="text" class="quantity-cart" name="quantity-cart" value="1">
                                                <button class="pluss"
                                                    onclick="updateQuantity(this, 1, 4, 6, 23)">+</button>
                                            </div>

                                        </td>
                                        <td>
                                            <span class="total-price">135.000 VNĐ</span>
                                        </td>
                                    </tr>
                                    

                                    <!-- <tr>
                                            <td colspan="5" class="text-center">Giỏ hàng của bạn đang trống</td>
                                        </tr> -->
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-between mt-3 mb-4">
                                <div class="to-shop">
                                    <a href="./shop.php"
                                        class="text-decoration-none text-center toCheck py-2 px-4 text-dark fw-bold">TIẾP
                                        TỤC MUA HÀNG</a>
                                </div>
                                <div class="update-cart">
                                    <button type="submit" class="button btn text-center" name="update_cart"
                                        value="Cập nhật giỏ hàng" aria-label="Cập nhật">CẬP NHẬT GIỎ HÀNG</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3">
                        <p class="fw-bold  text-dark">Tổng giỏ hàng</p>
                        <hr>
                        <div class="d-flex justify-content-start">

                            <div class="general-cart d-flex justify-content-between align-items-center">

                                <div class="start-items text-start ">
                                    <p class="fw-bold color-main">Tổng sản phẩm:</p>
                                    <p class="fw-bold color-main">TỔNG: </p>
                                </div>
                                <div class="end-items text-end justify-content-end ">
                                    <p class="quantity-products">1</p>
                                    <p class="total-quantityInCart fw-bold">
                                        <span id="total_price ">135.000 VNĐ</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="to-checkout mt-3 d-flex">
                            <a href="checkout.php"
                                class="toCheck text-decoration-none w-100 text-center py-2 m-auto px-2 bg-primary text-white fw-bold rounded">THANH
                                TOÁN</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>