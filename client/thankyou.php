<?php
session_start();

function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';
}

// Kiểm tra nếu dữ liệu đơn hàng đã được lưu trong session
if (isset($_SESSION['orderDetails'])) {
    $orderDetails = $_SESSION['orderDetails'];
    $idOrder = $orderDetails['idOrder'];
    $dateOrder = $orderDetails['dateOrder'];
    $totalPrice = $orderDetails['totalPrice'];

    // Xóa thông tin đơn hàng sau khi đã xử lý để tránh lạm dụng session
    unset($_SESSION['orderDetails']);
} else {
    // Nếu không có thông tin đơn hàng, có thể chuyển hướng người dùng về trang chủ
    header("Location: index.php");
    exit;
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
                                <td><?= $idOrder ?></td>
                            </tr>
                            <tr>
                                <th>Ngày Đặt Hàng:</th>
                                <td><?= $dateOrder ?></td>
                            </tr>
                            <tr>
                                <th>Tổng Tiền:</th>
                                <td><?= formatCurrencyVND($totalPrice) ?></td>
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



