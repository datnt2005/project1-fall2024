<?php
session_start();
$idUser = $_SESSION['idUser'] ?? null;
include_once("./DBUntil.php");
$dbHelper = new DBUntil();

function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';
}



// Xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['remove'])) {
    $idProductToRemove = $_GET['remove'];
    $sizeToRemove = isset($_GET['size']) ? $_GET['size'] : null;
    $colorToRemove = isset($_GET['color']) ? $_GET['color'] : null;

    if (isset($_SESSION['idUser'])) {
        // Nếu đã đăng nhập, xóa sản phẩm khỏi database
        $removeProduct = $dbHelper->delete("detailcart", "idDetailCart = $idProductToRemove");
        if ($removeProduct) {
            $_SESSION['message'] = "Sản phẩm được xóa khỏi giỏ hàng!";
        }
    } else {
        // Nếu chưa đăng nhập, xóa sản phẩm khỏi session
        foreach ($_SESSION['cart'] as $key => $cartSessionItem) {
            if (
                $cartSessionItem['idProduct'] == $idProductToRemove &&
                $cartSessionItem['size'] == $sizeToRemove &&
                $cartSessionItem['color'] == $colorToRemove
            ) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['message'] = "Sản phẩm được xóa khỏi giỏ hàng!";
                break;
            }
        }
    }
    header("Location: cart.php");
    exit;
}

// Xử lý cập nhật giỏ hàng
if (isset($_POST['update_cart'])) {
    $isUpdated = false;
    if (!isset($_SESSION['idUser']) && isset($_SESSION['cart'])) {
        foreach ($_POST['quantity'] as $idDetailCart => $quantity) {
            // Kiểm tra nếu số lượng hợp lệ
            if (is_numeric($quantity) && $quantity > 0) {
                foreach ($_SESSION['cart'] as &$cartSessionItem) {
                    if ($cartSessionItem['idProduct'] == $idDetailCart) {
                        $cartSessionItem['quantity'] = $quantity; // Cập nhật số lượng
                        $isUpdated = true; // Đánh dấu là đã cập nhật
                    }
                }
            }
        }
    } else {
        foreach ($_POST['quantity'] as $idDetailCart => $quantity) {
            $updateCart = $dbHelper->update("detailcart", ['quantityCart' => $quantity], "idDetailCart = $idDetailCart");
            if ($updateCart) {
                $isUpdated = true;
            }
        }
    }
    if ($isUpdated) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Cập nhật giỏ hàng thành công!',
                    text: 'Giỏ hàng đã được cập nhật.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#69BA31'
                }).then(() => {
                    window.location.href = 'cart.php';
                });
            });
        </script>
    ";
    exit;
    } else {
        $_SESSION['error'] = "Không thể cập nhật giỏ hàng. Vui lòng thử lại!";
    }
}

// Xử lý giỏ hàng khi người dùng đã đăng nhập
if (isset($_SESSION['idUser'])) {
    $checkCart = $dbHelper->select("SELECT * FROM carts WHERE idUser = ?", [$idUser]);

    if (!empty($checkCart)) {
        $idCart = $checkCart[0]['idCart'];
        $productCart = $dbHelper->select("SELECT dca.*, MIN(pic.namePicProduct) AS namePic, pr.*, ps.* 
        FROM carts ca 
        INNER JOIN detailcart dca ON ca.idCart = dca.idCart
        INNER JOIN products pr ON pr.idProduct = dca.idProduct
        LEFT JOIN picproduct pic ON pic.idProduct = pr.idProduct
        LEFT JOIN product_size ps ON ps.idProduct = pr.idProduct
        WHERE ca.idUser = ? AND dca.idCart = ?
        GROUP BY dca.idDetailCart", [$idUser, $idCart]);

        // Mảng để lưu giá của từng sản phẩm theo size
        $productPrices = [];
        foreach ($productCart as $key => $product) {
            $sizeCart = $dbHelper->select("SELECT size FROM detailcart WHERE idCart = ?", [$idCart]);
            foreach ($sizeCart as $sizes) {
                $listSize = $dbHelper->select("SELECT * FROM sizes WHERE nameSize = ?", [$sizes['size']]);
                foreach ($listSize as $size) {
                    // Lấy giá theo size cho mỗi sản phẩm
                    $priceCart = $dbHelper->select("SELECT ps.price 
                                                    FROM product_size ps
                                                    WHERE ps.idProduct = ? AND ps.idSize = ?", [$product['idProduct'], $size['idSize']]);
                    if (!empty($priceCart)) {
                        $productPrices[$product['idProduct']][$size['nameSize']] = $priceCart[0]['price'];
                    }
                }
            }
        }

        // Kiểm tra nếu giỏ hàng trống
        if (empty($productCart)) {
            $_SESSION['error'] = "Giỏ hàng trống.";
        }
    } else {
        $_SESSION['error'] = "Không tìm thấy giỏ hàng.";
    }
} else {
    // Nếu chưa đăng nhập, lấy sản phẩm từ session
    $productCart = [];
    $totalPrice = 0;
    $totalQuantity = 0;

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $cartSessionItem) {
            $idProduct = $cartSessionItem['idProduct'];
            $quantity = $cartSessionItem['quantity'];
            $size = $cartSessionItem['size'];
            $color = $cartSessionItem['color'];

            $product = $dbHelper->select("SELECT pr.*, MIN(pic.namePicProduct) AS namePic
                                          FROM products pr
                                          LEFT JOIN picproduct pic ON pic.idProduct = pr.idProduct
                                          WHERE pr.idProduct = ?", [$idProduct]);
            if (!empty($product)) {
                $product[0]['quantityCart'] = $quantity;
                $product[0]['size'] = $size;
                $productCart[] = $product[0];

                // Tính tổng giá cho giỏ hàng
                $totalQuantity += $quantity;
                $totalPrice += $product[0]['price'] * $quantity;
            }
        }
    } else {
        $_SESSION['error'] = "Giỏ hàng trống.";
    }
}

// Tính tổng giá và số lượng sản phẩm
$totalPrice = 0;
$totalQuantity = 0;
foreach ($productCart as $cartItem) {
    $totalPrice += $cartItem['price'] * $cartItem['quantityCart'];
    $totalQuantity += $cartItem['quantityCart'];
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
                                    <?php if (!empty($productCart)): ?>
                                        <?php foreach ($productCart as $cartItem): ?>
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

                                                                <img src="../admin/products/image/<?php echo htmlspecialchars($cartItem['namePic']); ?>"
                                                                    alt="Items" class="w-100">
                                                            </div>
                                                            <div class="product-content mx-2">

                                                                <p class="product-name line-clamp-1 fw-bold"
                                                                    style="font-size: 16px; color: #353535; hover {color: 69BA31}">
                                                                    <?php echo htmlspecialchars($cartItem['nameProduct']); ?> -
                                                                    <?php echo htmlspecialchars($cartItem['size']); ?></p>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                                <td>
                                                    <span class="price">
                                                        <?php
                                                        // Kiểm tra và hiển thị giá theo size
                                                        if (isset($productPrices[$cartItem['idProduct']][$cartItem['size']])) {
                                                            echo formatCurrencyVND($productPrices[$cartItem['idProduct']][$cartItem['size']]);
                                                        } else {
                                                            echo "Giá chưa có";
                                                        }
                                                        ?>
                                                    </span>

                                                </td>
                                                <td>
                                                    <div class="quantity">
                                                        <input type="number"
                                                            id="quantity-products-<?php echo $cartItem['idDetailCart']; ?>"
                                                            name="quantity[<?php echo $cartItem['idDetailCart']; ?>]"
                                                            class="input-text w-25 text-center btn btn-outline-success qty text bk-product-qty"
                                                            step="1" min="1"
                                                            value="<?php echo htmlspecialchars($cartItem['quantityCart']); ?>"
                                                            title="SL" size="4" autocomplete="off">
                                                    </div>

                                                </td>
                                                <td>
                                                    <span class="total-price">
                                                        <?php
                                                        // Kiểm tra và hiển thị giá theo size
                                                        if (isset($productPrices[$cartItem['idProduct']][$cartItem['size']])) {
                                                            echo formatCurrencyVND($productPrices[$cartItem['idProduct']][$cartItem['size']] * $cartItem['quantityCart']);
                                                        } else {
                                                            echo "Giá chưa có";
                                                        }
                                                        ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-center">Giỏ hàng của bạn đang trống</td>
                                        </tr>
                                    <?php endif; ?>
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
                                    <p class="quantity-products"><?php echo $totalQuantity; ?></p>
                                    <p class="total-quantityInCart ">
                                        <span id="total_price "><?php echo formatCurrencyVND($totalPrice); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="to-checkout mt-3 d-flex">
                            <form method="post" action="checkout.php" class="w-100">
                                <button type="submit" name="checkout" class="btn btn-primary w-100 fw-bold rounded">
                                    THANH TOÁN
                                </button>
                            </form>
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

