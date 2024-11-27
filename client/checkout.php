<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
require './connnect.php';

$user_id = $_SESSION['idUser'] ?? null;

if (!$user_id) {
    echo "<script>alert('Bạn cần đăng nhập trước!'); window.location.href = 'login.php';</script>";
    exit();
}

// Nếu có địa chỉ mặc định, lấy địa chỉ đó
$sql_default_address = "SELECT da.*, p.name AS province_name, d.name AS district_name, w.name AS ward_name
                        FROM detail_address da
                        JOIN province p ON da.province_id = p.province_id
                        JOIN district d ON da.district_id = d.district_id
                        JOIN wards w ON da.ward_id = w.wards_id
                        WHERE da.user_id = '$user_id' AND da.is_default = 1";

$result_default = mysqli_query($conn, $sql_default_address);

if (mysqli_num_rows($result_default) > 0) {
    $address = mysqli_fetch_assoc($result_default);
} else {
    // Nếu không có địa chỉ mặc định, bạn có thể hiển thị thông báo yêu cầu chọn địa chỉ hoặc cho phép người dùng thêm địa chỉ mới
    echo "<script>alert('Bạn chưa có địa chỉ mặc định, vui lòng chọn một địa chỉ hoặc thêm mới!'); window.location.href = 'listAddress.php';</script>";
    exit();
}

// Nếu người dùng đã chọn địa chỉ, lấy thông tin địa chỉ đó
$selected_address_id = $_POST['selected_address'] ?? null;

if ($selected_address_id) {
    $sql = "SELECT da.*, p.name AS province_name, d.name AS district_name, w.name AS ward_name
            FROM detail_address da
            JOIN province p ON da.province_id = p.province_id
            JOIN district d ON da.district_id = d.district_id
            JOIN wards w ON da.ward_id = w.wards_id
            WHERE da.detail_id = '$selected_address_id' AND da.user_id = '$user_id'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $address = mysqli_fetch_assoc($result);
    } else {
        echo "<script>alert('Không tìm thấy địa chỉ này!'); window.location.href = 'listAddress.php';</script>";
        exit();
    }
}


function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';
}

if (isset($_SESSION['idUser'])) {
    $idUser = $_SESSION['idUser'];

    // Lấy giỏ hàng của người dùng
    $checkCart = $dbHelper->select("SELECT * FROM carts WHERE idUser = ?", [$idUser]);

    if (!empty($checkCart)) {
        $idCart = $checkCart[0]['idCart'];

        // Lấy thông tin sản phẩm trong giỏ hàng
        $productCart = $dbHelper->select(
            "SELECT dca.*, pr.*, MIN(pic.namePicProduct) AS namePic
             FROM detailcart dca
             INNER JOIN products pr ON pr.idProduct = dca.idProduct
             LEFT JOIN picproduct pic ON pic.idProduct = pr.idProduct
             WHERE dca.idCart = ? 
             GROUP BY dca.idProduct, dca.size",
            [$idCart]
        );
        $totalPrice = 0;
        $totalQuantity = 0;

        // Tính tổng tiền và số lượng
        foreach ($productCart as $cartItem) {
            $totalPrice += $cartItem['price'] * $cartItem['quantityCart'];
            $totalQuantity += $cartItem['quantityCart'];
        }
    } else {
        $_SESSION['error'] = "Giỏ hàng trống.";
    }
}
// coupon
// coupon application
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['coupon'])) {
    $couponCode = $_POST['coupon'];

    // Check if the coupon exists in the database
    $sql = "SELECT * FROM coupons WHERE codeCoupon = ? AND startDate <= NOW() AND endDate >= NOW() AND quantityCoupon > 0";
    $coupon = $dbHelper->select($sql, [$couponCode]);

    if ($coupon) {
        // Coupon found and valid
        $coupon = $coupon[0]; // Get the first (and only) coupon

        // Apply coupon to the cart total if there’s stock left
        if ($coupon['quantityCoupon'] > 0) {
            // Apply discount to the total
            $discountAmount = $coupon['discount']; // Discount percentage

            // Update the coupon quantity in the database (decrease by 1)
            $newQuantity = $coupon['quantityCoupon'] - 1;
            $updateCouponSQL = "UPDATE coupons SET quantityCoupon = ? WHERE idCoupon = ?";
            $dbHelper->select($updateCouponSQL, [$newQuantity, $coupon['idCoupon']]);

            // Calculate new total price after discount
            $discountedPrice = $totalPrice - ($totalPrice * $discountAmount / 100);
            $_SESSION['discountedPrice'] = $discountedPrice; // Store discounted price in session
            $_SESSION['couponId'] = $coupon['idCoupon']; // Store coupon ID to track usage

            // Optionally, show a message to the user
            echo "<script>alert('Mã giảm giá đã được áp dụng! Giảm: $discountAmount%');</script>";
        } else {
            echo "<script>alert('Mã giảm giá đã hết lượt sử dụng.');</script>";
        }
    } else {
        echo "<script>alert('Mã giảm giá không hợp lệ hoặc đã hết hạn.');</script>";
    }
}

// Cancel coupon
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_coupon'])) {
    // Remove the discounted price from the session
    unset($_SESSION['discountedPrice']);

    // Restore the coupon quantity in the database (increment by 1)
    if (isset($_SESSION['couponId'])) {
        $couponId = $_SESSION['couponId'];

        // Get the coupon details
        $coupon = $dbHelper->select("SELECT * FROM coupons WHERE idCoupon = ?", [$couponId]);

        if ($coupon) {
            // Increment the quantity of the coupon by 1 to return it to the pool
            $newQuantity = $coupon[0]['quantityCoupon'] + 1;
            $updateCouponSQL = "UPDATE coupons SET quantityCoupon = ? WHERE idCoupon = ?";
            $dbHelper->select($updateCouponSQL, [$newQuantity, $couponId]);

            // Remove the coupon ID from the session after cancellation
            unset($_SESSION['couponId']);
        }
    }

    // Optionally, you can redirect the user to reset prices
    echo "<script>window.location.href = 'checkout.php';</script>";
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
include "./includes/head.php" ?>

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
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ Thanh toán</p>
                </div>
            </div>
        </div>

        <section id="checkout" class="h-100">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="fs-4 fw-bold">THÔNG TIN THANH TOÁN</h3>
                        <form action="checkout.php" method="post">
                            <div class="address-delivery">
                                <p class="fw-bold mb-2">1. Địa chỉ nhận hàng</p>
                                <div class="detail-address px-2">
                                    <a href="./listAddress.php?php echo $idUser ?>"
                                        class="add_to--address nav-link d-flex align-items-center ms-2">
                                        <i class="fa-solid fa-plus fs-3"></i>
                                        <label class="ms-3">Thêm thông tin nhận hàng</label>
                                    </a>
                                    <div class="address-content text-secondary">
                                        <p><strong>Tên:</strong> <?= htmlspecialchars($address['name']) ?></p>
                                        <p><strong>SĐT:</strong> <?= htmlspecialchars($address['phone']) ?></p>
                                        <p><strong>Email:</strong> <?= htmlspecialchars($address['email']) ?></p>
                                        <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($address['village']) ?>, <?= htmlspecialchars($address['ward_name']) ?>, <?= htmlspecialchars($address['district_name']) ?>, <?= htmlspecialchars($address['province_name']) ?></p>
                                        <!-- <p class="m-0">Chọn địa điểm giao hàng</p>     -->
                                    </div>
                                    <div class="noteOrder mt-3">
                                        <label for="note">Ghi chú</label>
                                        <textarea name="noteOrder" id="note"
                                            class="w-100" rows="3" placeholder="Ghi chú ..."></textarea>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="delivery-price">
                                <p class="fw-bold mb-2">2. Vận chuyển</p>
                                <div class="d-flex justify-content-between px-2">
                                    <div class="input-checked">
                                        <label class="fs-6">GHN-Tiêu chuẩn</label>
                                    </div>
                                    <p class="price delivery">+30.000đ</p>
                                </div>
                            </div>
                            <hr>
                            <div class="payment">
                                <p class="fw-bold mb-2">3. Phương thức thanh toán</p>
                                <div class="payment-method px-2">
                                    <div class="pay">
                                        <input type="radio" name="payment" value="1" id="payment1"> <label class="fs-6 mx-2" for="payment1">Thanh toán khi nhận hàng</label>
                                    </div>
                                    <div class="vnPay mt-1">
                                        <input type="radio" name="payment" value="2" id="payment2">
                                        <label class="fs-6 mx-2" for="payment2">Thanh toán VNPAY</label>
                                    </div>
                                    <!-- <?php
                                            if (isset($errors['payment'])) {
                                                echo "<span class='text-danger'>$errors[payment] </span>";
                                            }
                                            ?> -->
                                </div>
                            </div>
                            <hr>
                            <div class="coupon">
                                <p class="fw-bold mb-2">4. Áp dụng mã giảm giá</p>
                                <div class="coupon-input px-2">
                                    <input type="search" name="coupon" id="coupon" placeholder="Nhập mã giảm giá">
                                    <button type="submit" id="apply-coupon">Sử dụng</button>
                                </div>
                                <!--                                 
                                <?php
                                if (isset($errors['coupon'])) {
                                    echo "<span class='text-danger mx-2'>{$errors['coupon']}</span>";
                                }
                                if ($discountAmount) {
                                    foreach ($coupons as $value) {
                                        echo "<span class='text-success mx-2'>Bạn được giảm {$value['discount']}%</span>";
                                    }
                                }
                                ?> -->

                            </div>
                            <input type="hidden" name="action" id="action" value="order">

                            <input type="submit" class="btn btn-primary fw-bold w-100 mt-5" value="MUA HÀNG">
                        </form>
                        <script>
                            document.getElementById('apply-coupon').addEventListener('click', function() {
                                // Đặt giá trị action là 'coupon' để phân biệt với nút mua hàng
                                document.getElementById('action').value = 'coupon';
                                document.querySelector('form').submit(); // Submit form
                            });

                            document.querySelector('form').addEventListener('submit', function() {
                                // Đặt giá trị action là 'order' trước khi form được gửi nếu nút mua hàng được click
                                document.getElementById('action').value = 'order';
                            });
                        </script>
                        <!-- <?php
                                if (isset($errors['database'])) {
                                    echo "<div class='alert alert-danger mt-3'>$errors[database]</div>";
                                }
                                ?> -->
                    </div>
                    <!-- Modal for Coupon Success -->
<div id="couponSuccessModal" class="modal" style="display: none;">
    <div class="modal-content">
        <p id="couponMessage" class="fw-bold">Mã giảm giá đã được áp dụng!</p>
        <button id="closeCouponMessage" class="btn btn-primary">Đóng</button>
    </div>
</div>

                    <div class="col-md-6">
                        <h3 class="fs-4 fw-bold">THÔNG TIN SẢN PHẨM</h3>
                        <table class="table table-cart">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
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
                                                <div class="cart-products d-flex align-items-center">
                                                    <div class="image-products">
                                                        <img src="../admin/products/image/<?php echo htmlspecialchars($cartItem['namePic']); ?>" alt="Items" class="w-100">
                                                    </div>
                                                    <div class="product-content mx-2">
                                                        <a href="detailProduct.php?id=<?php echo $cartItem['idProduct']; ?>" class="name-products text-decoration-none">
                                                            <p class="product-name text-dark"><?php echo htmlspecialchars($cartItem['nameProduct']); ?> - <?php echo htmlspecialchars($cartItem['size']); ?></p>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="price"><?php echo formatCurrencyVND($cartItem['price']); ?></span>
                                            </td>
                                            <td>
                                                <span class="quantity text-center"><?php echo $cartItem['quantityCart']; ?></span>
                                            </td>
                                            <td>
                                                <span class="total-price"><?php echo formatCurrencyVND($cartItem['price'] * $cartItem['quantityCart']); ?></span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Giỏ hàng của bạn đang trống</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="general-cart d-flex justify-content-between">
                            <div class="start-items">
                                <p class="fw-bold">Tổng sản phẩm:</p>
                                <p class="fw-bold">Tổng tiền:</p>
                                <?php if (isset($_SESSION['discountedPrice'])): ?>
                                    <p class="fw-bold">Giảm giá:</p>
                                    <p class="fw-bold">Tổng tiền sau giảm:</p>
                                <?php endif; ?>
                            </div>
                            <div class="end-items text-end">
                                <p class="quantity-products"><?php echo $totalQuantity; ?></p>
                                <p class="total-quantityInCart"><?php echo formatCurrencyVND($totalPrice); ?></p>
                                <?php if (isset($_SESSION['discountedPrice'])): ?>
                                    <p class="discount"><?php echo formatCurrencyVND($totalPrice - $_SESSION['discountedPrice']); ?></p>
                                    <p class="total-quantityInCart"><?php echo formatCurrencyVND($_SESSION['discountedPrice']); ?></p>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['discountedPrice'])): ?>
                                    <form action="checkout.php" method="POST">
                                        <input type="hidden" name="cancel_coupon" value="1">
                                        <button type="submit" class="btn btn-danger">Hủy mã giảm giá</button>
                                    </form>
                                <?php endif; ?>
                            </div>

                        </div>

                        <hr>
                    </div>


        </section>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>