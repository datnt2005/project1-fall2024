<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
require './connnect.php';



$user_id = $_SESSION['idUser'] ?? null;  // Kiểm tra người dùng đã đăng nhập chưa

if (!$user_id) {
    echo "<script>alert('Bạn cần đăng nhập trước!'); window.location.href = 'login.php';</script>";
    exit();
}

$order_id = $_GET['idOrder'] ?? null; // Lấy idOrder từ URL
if (!$order_id) {
    echo "<script>alert('Không tìm thấy mã đơn hàng!'); window.location.href = 'orders.php';</script>";
    exit();
}

function getPaymentMethod($payment)
{
    $methods = [
        1 => 'Thanh toán khi nhận hàng (COD)',
        2 => 'Thanh toán qua VNPay',
    ];

    return $methods[$payment] ?? 'Không xác định';
}

function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';  // Định dạng tiền Việt Nam
}

// $sql_order_details = "
//     SELECT 
//         o.idOrder, o.dateOrder, o.statusOrder, o.noteOrder, o.totalPrice, o.payment,
//         a.name AS customer_name, a.village, a.phone, a.email,
//         d.quantityOrder, p.nameProduct, d.sizeOrder
//     FROM orders o
//     JOIN detail_address a ON o.idAddress = a.detail_id
//     JOIN detailorder d ON o.idOrder = d.idOrder
//     JOIN products p ON d.idProduct = p.idProduct
//     WHERE o.idOrder = ?
// ";
$listOrders = $dbHelper->select("SELECT * FROM detailorder WHERE idOrder = ?", [$order_id]);
$size = $listOrders[0]['sizeOrder'];
$listSize = $dbHelper->select("SELECT * FROM sizes WHERE nameSize = ?", [$size]);
$idSize = $listSize[0]['idSize'];
// var_dump($idSize);
$sql_order_details = " SELECT ord.*, dadr.*, dor.*, pr.* FROM orders ord
                        INNER JOIN detailorder dor ON ord.idOrder = dor.idOrder
                        INNER JOIN products pr ON dor.idProduct = pr.idProduct
                        INNER JOIN detail_address dadr ON ord.idAddress = dadr.detail_id
                        WHERE ord.idOrder = ?";
$order_details = $dbHelper->select($sql_order_details, [$order_id]);
$listPrice = $dbHelper->select("SELECT * FROM product_size WHERE idProduct = ? AND idSize = ?", [$order_details[0]['idProduct'], $idSize]);
$price = $listPrice[0]['price'];
// echo ($listPrice[0]['price']);
// var_dump($order_details);

if (empty($order_details)) {
    echo "<p>Không tìm thấy thông tin đơn hàng với mã $order_id.</p>";
    exit();
}

$order_info = $order_details[0]; // Lấy thông tin chung từ kết quả đầu tiên
$shipping_fee = 30000;

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
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ Chi tiết đơn hàng</p>
                </div>
            </div>
        </div>
        <div class="container my-5">


            <!-- Thông tin khách hàng -->
            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
                <h5 class="mb-3">Thông Tin Giao hàng</h5>
                <table class="table table-borderless mb-4">
                    <tbody>
                        <tr>
                            <td><strong>Họ và Tên:</strong></td>
                            <td><?php echo $order_info['name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Số Điện Thoại:</strong></td>
                            <td><?php echo $order_info['phone']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?php echo $order_info['email']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Địa Chỉ:</strong></td>
                            <td><?php echo $order_info['village']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Ghi Chú:</strong></td>
                            <td><?php echo $order_info['noteOrder']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
                <h5 class="mb-3">Thông Tin Sản Phẩm</h5>
                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th>Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <th>Trọng lượng</th>
                            <th>Giá</th>
                            <th>Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_details as $detail): ?>
                            <?php
                            $size = $detail['sizeOrder'];
                            $listSize = $dbHelper->select("SELECT idSize FROM sizes WHERE nameSize = ?", [$size]);
                            $idSize = $listSize[0]['idSize'] ?? null;

                            $listPrice = $dbHelper->select("SELECT price FROM product_size WHERE idProduct = ? AND idSize = ?", [$detail['idProduct'], $idSize]);
                            $price = $listPrice[0]['price'] ?? 0;
                            ?>
                            <tr>
                                <td><?php echo $detail['nameProduct']; ?></td>
                                <td><?php echo $detail['quantityOrder']; ?></td>
                                <td><?php echo $detail['sizeOrder']; ?></td>
                                <td><?php echo formatCurrencyVND($price); ?></td>
                                <td><?php echo formatCurrencyVND($detail['quantityOrder'] * $price); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <!-- Tiền ship -->
                <div class="d-flex justify-content-end">
                    <p class="fw-bold">Phí vận chuyển: <?php echo formatCurrencyVND($shipping_fee); ?></p>
                </div>
                <div class="d-flex justify-content-end">
                    <h5 class="fw-bold">Tổng tiền: <?php echo formatCurrencyVND($order_info['totalPrice']); ?></h5>
                </div>
            </div>


            <!-- Phương thức thanh toán -->
            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
                <h5 class="mb-3">Phương Thức Thanh Toán</h5>
                <p><?php echo getPaymentMethod($order_info['payment']); ?></p>
            </div>
        </div>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>