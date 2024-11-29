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

// Kiểm tra và lấy thông tin địa chỉ mặc định hoặc địa chỉ đã chọn
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
    echo "<script>alert('Bạn chưa có địa chỉ mặc định, vui lòng chọn một địa chỉ hoặc thêm mới!'); window.location.href = 'listAddress.php';</script>";
    exit();
}

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

// Initialize variables to prevent undefined variable issues
$orderId = $orderId ?? '';
$orderDate = $orderDate ?? '';
$orderTotal = $orderTotal ?? '';

// Coupon application
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action == 'coupon' && isset($_POST['coupon'])) {
        $couponCode = $_POST['coupon'];

        // Kiểm tra mã giảm giá
        $sql = "SELECT * FROM coupons WHERE codeCoupon = ? AND startDate <= NOW() AND endDate >= NOW() AND quantityCoupon > 0";
        $coupon = $dbHelper->select($sql, [$couponCode]);

        if ($coupon) {
            // Coupon hợp lệ
            $coupon = $coupon[0];

            if ($coupon['quantityCoupon'] > 0) {
                $discountAmount = $coupon['discount'];

                // Cập nhật số lượng coupon
                $newQuantity = $coupon['quantityCoupon'] - 1;
                $updateCouponSQL = "UPDATE coupons SET quantityCoupon = ? WHERE idCoupon = ?";
                $dbHelper->select($updateCouponSQL, [$newQuantity, $coupon['idCoupon']]);

                $discountedPrice = $totalPrice - ($totalPrice * $discountAmount / 100);
                $_SESSION['discountedPrice'] = $discountedPrice;
                $_SESSION['couponId'] = $coupon['idCoupon'];

                echo "<script>alert('Mã giảm giá đã được áp dụng! Giảm: $discountAmount%');</script>";
            } else {
                echo "<script>alert('Mã giảm giá đã hết lượt sử dụng.');</script>";
            }
        } else {
            echo "<script>alert('Mã giảm giá không hợp lệ hoặc đã hết hạn.');</script>";
        }
    }

    if ($action == 'order') {
        $paymentMethod = $_POST['payment'] ?? null;
        $noteOrder = $_POST['noteOrder'] ?? '';
        $errors = [];

        $addressOrder = $_POST['addressOrder'] ?? null;
        if (!$addressOrder) {
            $errors['addressOrder'] = "Vui lòng chọn địa chỉ nhận hàng.";
        }

        if (!$paymentMethod) {
            $errors['payment_method'] = "Vui lòng chọn phương thức thanh toán.";
        }

        if (count($errors) === 0) {
            // Xử lý đơn hàng
            $currentDateTime = getdate();
            $mysqlDateTime = date("Y-m-d H:i:s", mktime(
                $currentDateTime['hours'] + 5,
                $currentDateTime['minutes'],
                $currentDateTime['seconds'],
                $currentDateTime['mon'],
                $currentDateTime['mday'],
                $currentDateTime['year']
            ));

            // Thêm đơn hàng vào cơ sở dữ liệu
            $dataInsertOrder = [
                "dateOrder" => $mysqlDateTime,
                "statusOrder" => $paymentMethod == 1 ? 1 : 2,
                "noteOrder" => $noteOrder,
                "totalPrice" => $totalPrice + 30000,
                "payment" => $paymentMethod,
                "idAddress" => $addressOrder,
            ];
            $insertOrder = $dbHelper->insert("orders", $dataInsertOrder);

            if ($insertOrder) {
                // Lấy idOrder vừa thêm
                $idOrder = $dbHelper->lastInsertId();

                // Lấy chi tiết đơn hàng vừa thêm để hiển thị
                $orderDetails = $dbHelper->select("SELECT * FROM orders WHERE idOrder = ?", [$idOrder]);

                // Debug: check the fetched order details
                var_dump($orderDetails);

                if (!empty($orderDetails)) {
                    $orderDetail = $orderDetails[0]; // Assuming you get one order

                    $orderId = $orderDetail['idOrder'];
                    $orderDate = $orderDetail['dateOrder'];
                    $orderTotal = formatCurrencyVND($orderDetail['totalPrice']);
                }

                // Thêm chi tiết đơn hàng
                foreach ($productCart as $cartItem) {
                    $data = [
                        "quantityOrder" => $cartItem['quantityCart'],
                        "sizeOrder" => $cartItem['size'],
                        "idProduct" => $cartItem['idProduct'],
                        "idOrder" => $idOrder,
                    ];
                    $insertDetailOrder = $dbHelper->insert("detailorder", $data);
                    if (!$insertDetailOrder) {
                        $errors['database'] = "Không thể thêm chi tiết đơn hàng.";
                        break;
                    }
                }

                if (!isset($errors['database'])) {
                    // Thành công
                    $removeCart = $dbHelper->delete("detailcart", "idCart = $idCart");
                    echo "<script>alert('Mua hàng thành công!'); window.location.href = 'thankyou.php';</script>";
                    exit();
                }
            }
        }

        // Hiển thị lỗi
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }
    }
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
            <div class="d-flex header-outstanding align-items-center">
                <p class=" m-1 fs-5 fw-bold">Trang chủ/ thông tin đơn hàng</p>
            </div>
        </div>

        <div class="container text-center my-5">
            <div class="card shadow-lg p-4 rounded-4">
                <div class="card-body">
                    <i class="fa-solid fa-face-laugh-wink" style="font-size: 100px; color: #69BA31;"></i>
                    <h2 class="card-title  mb-4" style="color: #69BA31;">Cảm ơn bạn đã đặt hàng!</h2>
                    <p class="lead">Đơn hàng của bạn đã được xác nhận và chúng tôi đang xử lý. Bạn sẽ sớm nhận được thông tin chi tiết qua email.</p>

                    <!-- Chi tiết đơn hàng -->
                    <div class="mt-4">
                        <h5 class="mb-3">Chi tiết đơn hàng</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Mã Đơn Hàng:</th>
                                <td><?php echo $orderId; ?></td>
                            </tr>
                            <tr>
                                <th>Ngày Đặt Hàng:</th>
                                <td><?php echo date("d/m/Y", strtotime($orderDate)); ?></td>
                            </tr>
                            <tr>
                                <th>Tổng Tiền:</th>
                                <td><?php echo $orderTotal; ?></td>
                            </tr>
                        </table>
                    </div>


                    <!-- Nút trở về trang chủ -->
                    <a href="index.php" class="btn mt-4" style="background-color: #69BA31; color: white;">Quay về trang chủ</a>
                </div>
            </div>
        </div>


    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>