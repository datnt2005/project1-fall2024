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
                <p class=" m-1 fs-5 fw-bold">Trang chủ/ Chi tiết đơn hàng</p>
            </div>
        </div>
        <div class="container my-5">
            <h2 class="text-center mb-4">Chi Tiết Đơn Hàng</h2>

            <!-- Thông tin khách hàng -->
            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
                <h5 class="mb-3">Thông Tin Giao hàng</h5>
                <table class="table table-borderless mb-4">
                    <tbody>
                        <tr>
                            <td><strong>Họ và Tên:</strong></td>
                            <td>Nguyễn Văn A</td>
                        </tr>
                        <tr>
                            <td><strong>Số Điện Thoại:</strong></td>
                            <td>0123 456 789</td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td>example@example.com</td>
                        </tr>
                        <tr>
                            <td><strong>Địa Chỉ:</strong></td>
                            <td>123 Đường ABC, Phường XYZ, Quận 1, TP.HCM</td>
                        </tr>
                        <tr>
                            <td><strong>Ghi Chú:</strong></td>
                            <td>Giao hàng giờ hành chính</td>
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
                            <th>Giá</th>
                            <th>Voucher giảm giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> Sữa Rửa Mặt Cerave</td>
                            <td>2</td>
                            <td>100,000 VND</td>
                            <td>0 VND</td>
                            <td>200,000 VND</td>
                        </tr>
                        <!-- Thêm sản phẩm khác nếu có -->
                    </tbody>
                </table>
            </div>

            <!-- Phương thức thanh toán -->
            <div class="card shadow-sm border-0 rounded-4 p-4 mt-4">
                <h5 class="mb-3">Phương Thức Thanh Toán</h5>
                <p>Thanh toán khi nhận hàng (COD)</p>
            </div>
        </div>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>