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

// Truy vấn lấy thông tin đơn hàng

$sql_orders = "SELECT o.idOrder, o.dateOrder, o.totalPrice, o.statusOrder, o.payment, o.noteOrder, o.idAddress, 
                      a.name as address_name, a.village, a.phone, a.email
               FROM orders o
               JOIN detail_address a ON o.idAddress = a.detail_id
               WHERE a.user_id = ?  -- Lọc đơn hàng theo user_id trong bảng detail_address
               ORDER BY o.dateOrder DESC";  // Sắp xếp theo ngày đặt hàng





$orders = $dbHelper->select($sql_orders, [$user_id]);

if (empty($orders)) {
    echo "<p>Không có đơn hàng nào.</p>";
    exit();
}

function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';  // Định dạng tiền Việt Nam
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "./includes/head.php" ?>

<body>
    <?php include "./includes/header.php" ?>

    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="d-flex header-outstanding align-items-center">
                <p class=" m-1 fs-5 fw-bold">Trang chủ/ thông tin đơn hàng</p>
            </div>
        </div>

        <div class="container my-5">
            <h2 class="text-center mb-4">Thông Tin Đơn Hàng Của Bạn</h2>

            <!-- Danh sách đơn hàng -->
            <div class="row row-cols-1 row-cols-md-2 g-4">
                <!-- Hiển thị từng đơn hàng -->
                <?php foreach ($orders as $order): ?>
                <div class="col-md-12">
                    <a href="detail_order.php?id=<?php echo $order['idOrder']; ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm border-0 rounded-4 p-3 hover-effect">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Mã Đơn Hàng: <?php echo $order['idOrder']; ?></h5>
                                <table class="table table-borderless mb-0">
                                    <thead>
                                        <tr>
                                            <th>Ngày Mua</th>
                                            <th>Địa Chỉ</th>
                                            <th>Tổng Tiền</th>
                                            <th>Trạng Thái</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php echo $order['dateOrder']; ?></td>
                                            <td><?php echo $order['address_name'] . ", " . $order['village'] . ", " . $order['phone']; ?></td>
                                            <td><?php echo formatCurrencyVND($order['totalPrice']); ?></td>
                                            <td>
                                                <span class="badge <?php echo ($order['statusOrder'] == 1) ? 'bg-success' : 'bg-warning'; ?>">
                                                    <?php echo ($order['statusOrder'] == 1) ? 'Đã Giao' : 'Đang Xử Lý'; ?>
                                                </span>
                                            </td>
                                            <td><button class="btn btn-primary">Mua lại</button></td>
                                            <td><button class="btn btn-warning" onclick="cancelOrder('<?php echo $order['idOrder']; ?>')">Hủy đơn</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>
<script>
    function cancelOrder(orderId) {
        if (confirm("Bạn có muốn hủy đơn hàng này không?")) {
            // Gửi yêu cầu hủy đơn hàng thông qua AJAX hoặc redirect tới trang xử lý
            window.location.href = 'cancel_order.php?id=' + orderId;
        }
    }
</script>

</html>
