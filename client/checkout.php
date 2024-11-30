<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
require './connnect.php';
require './sendMail.php';

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

        echo "<pre>";
        print_r($_POST); // Check if 'addressOrder' is present
        echo "</pre>";

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

                $_SESSION['orderDetails'] = [
                    'idOrder' => $idOrder,
                    'dateOrder' => $mysqlDateTime,
                    'totalPrice' => $totalPrice + 30000, // Đã bao gồm phí vận chuyển
                ];

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

                // Thay vì truy vấn email từ bảng 'users', truy vấn từ bảng 'detail_address'
                $sql_get_user_email = "SELECT email FROM detail_address WHERE user_id = ?";
                $user_email = $dbHelper->select($sql_get_user_email, [$idUser]);

                if ($user_email) {
                    $toEmail = $user_email[0]['email'];  // Lấy email từ kết quả truy vấn
                
                    // Thiết lập tiêu đề và nội dung email
                    $subject = "Xác nhận đơn hàng #{$idOrder} của bạn từ Vua Hạt Dinh Dưỡng Ngon";
                    $body = "
                    <html>
                    <head>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                line-height: 1.6;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 20px;
                            }
                            .container {
                                background-color: #ffffff;
                                padding: 20px;
                                border-radius: 8px;
                                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            }
                            h2 {
                                color: #333;
                            }
                            p {
                                color: #555;
                            }
                            .order-details {
                                margin-top: 20px;
                            }
                            .order-details p {
                                margin: 5px 0;
                            }
                            .footer {
                                margin-top: 40px;
                                font-size: 12px;
                                color: #888;
                            }
                            .footer a {
                                color: #1a73e8;
                                text-decoration: none;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='container'>
                            <h2>Cảm ơn bạn đã đặt hàng tại Vua Hạt Dinh Dưỡng Ngon!</h2>
                            <p>Chúng tôi rất vui mừng thông báo rằng đơn hàng của bạn đã được xác nhận. Dưới đây là thông tin chi tiết về đơn hàng:</p>
                            <div class='order-details'>
                                <p><strong>Đơn hàng số: </strong>#{$idOrder}</p>
                                <p><strong>Ngày đặt: </strong>{$mysqlDateTime}</p>
                                <p><strong>Tổng giá trị đơn hàng: </strong>" . formatCurrencyVND($totalPrice + 30000) . "</p>
                                <p><strong>Phương thức thanh toán: </strong>" . ($paymentMethod == 1 ? 'Thanh toán khi nhận hàng' : 'Thanh toán trực tuyến') . "</p>
                            </div>
                            <p>Chúng tôi sẽ xử lý và giao hàng cho bạn trong thời gian sớm nhất. Nếu có bất kỳ câu hỏi nào, xin vui lòng liên hệ với chúng tôi qua email này hoặc qua số điện thoại hỗ trợ khách hàng.</p>
                            <div class='footer'>
                                <p>Trân trọng,</p>
                                <p>Đội ngũ Vua Hạt Dinh Dưỡng Ngon</p>
                                <p><a href='https://www.vuahathd.com'>www.vuahathd.com</a></p>
                            </div>
                        </div>
                    </body>
                    </html>
                    ";
                
                    // Gửi email
                    sendMail($toEmail, $subject, $body);
                } else {
                    echo "<script>alert('Không tìm thấy email người dùng.');</script>";
                }
                


                // Now remove the cart
                if (!isset($errors['database'])) {
                    $removeCart = $dbHelper->delete("detailcart", "idCart = $idCart");

                    // Check payment method
                    if ($paymentMethod == 1) {
                        // Redirect to thank you page
                        header("Location: thankyou.php");
                        exit();
                    } elseif ($paymentMethod == 2) {
                        error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
                        date_default_timezone_set('Asia/Ho_Chi_Minh');

                        // Define constants
                        define("VNPAY_TMN_CODE", "CPU9YGXX"); // Mã TMN của bạn
                        define("VNPAY_HASH_SECRET", "9V37D08FEMQ5X84JM8XQM44I2JR1370S"); // Chuỗi bí mật của bạn
                        define("VNPAY_URL", "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html"); // URL thanh toán sandbox (thay bằng URL production khi deploy)
                        define("VNPAY_RETURN_URL", "http://localhost/project1-fall2024/client/thankyou.php");

                        // VNPAY information
                        $vnp_Url = VNPAY_URL;
                        $vnp_Returnurl = VNPAY_RETURN_URL;
                        $vnp_TmnCode = VNPAY_TMN_CODE;
                        $vnp_HashSecret = VNPAY_HASH_SECRET;

                        // Unique transaction reference
                        $vnp_TxnRef = $idOrder;
                        $vnp_OrderInfo = "Thanh toán đơn hàng"; // Thông tin đơn hàng
                        $vnp_OrderType = "billpayment"; // Loại thanh toán

                        // Convert total price to match VNPAY requirements (in VND and multiplied by 100)
                        $vnp_Amount = ($totalPrice + 30000) * 100; // Ensure this is in the correct format

                        $vnp_Locale = "vn"; // Ngôn ngữ
                        $vnp_IpAddr = $_SERVER['REMOTE_ADDR']; // Địa chỉ IP của khách hàng

                        // Get current time and set expiration time
                        $startTime = date("YmdHis");
                        $expire = date('YmdHis', strtotime('+15 minutes', strtotime($startTime)));

                        // Prepare input data for the VNPAY request
                        $inputData = array(
                            "vnp_Version" => "2.1.0",
                            "vnp_TmnCode" => $vnp_TmnCode,
                            "vnp_Amount" => $vnp_Amount,
                            "vnp_Command" => "pay",
                            "vnp_CreateDate" => $startTime,
                            "vnp_CurrCode" => "VND",
                            "vnp_IpAddr" => $vnp_IpAddr,
                            "vnp_Locale" => $vnp_Locale,
                            "vnp_OrderInfo" => $vnp_OrderInfo,
                            "vnp_OrderType" => $vnp_OrderType,
                            "vnp_ReturnUrl" => $vnp_Returnurl,
                            "vnp_TxnRef" => $vnp_TxnRef,
                            "vnp_ExpireDate" => $expire
                        );

                        // Sort input data by keys and generate query string
                        ksort($inputData);
                        $query = "";
                        $i = 0;
                        $hashdata = "";
                        foreach ($inputData as $key => $value) {
                            if ($i == 1) {
                                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                            } else {
                                $hashdata .= urlencode($key) . "=" . urlencode($value);
                                $i = 1;
                            }
                            $query .= urlencode($key) . "=" . urlencode($value) . '&';
                        }

                        $vnp_Url = $vnp_Url . "?" . $query;
                        if (isset($vnp_HashSecret)) {
                            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
                            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
                        }

                        // Redirect to VNPAY for payment
                        header('Location: ' . $vnp_Url);
                        exit();
                    }
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
                            <?php if (!empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= htmlspecialchars($error); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <div class="address-delivery">
                                <input type="hidden" name="addressOrder" value="<?= htmlspecialchars($address['detail_id']) ?>">

                                <p class="fw-bold mb-2">1. Địa chỉ nhận hàng</p>
                                <div class="detail-address px-2">
                                    <a href="listAddress.php?id=<?php echo $idUser; ?>"
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