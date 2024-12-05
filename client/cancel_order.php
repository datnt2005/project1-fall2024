<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();

$user_id = $_SESSION['idUser'] ?? null;

if (!$user_id) {
    echo "<script>alert('Bạn cần đăng nhập trước!'); window.location.href = 'login.php';</script>";
    exit();
}

$idOrder = $_GET['id'] ?? null;

if (!$idOrder) {
    echo "<script>alert('Không tìm thấy đơn hàng để hủy!'); window.location.href = 'order.php';</script>";
    exit();
}

// Kiểm tra trạng thái đơn hàng
$sql_check = "SELECT statusOrder FROM orders WHERE idOrder = ? AND idAddress IN 
              (SELECT detail_id FROM detail_address WHERE user_id = ?)";
$order = $dbHelper->select($sql_check, [$idOrder, $user_id]);

if (empty($order)) {
    echo "<script>alert('Đơn hàng không hợp lệ hoặc không thuộc về bạn!'); window.location.href = 'order.php';</script>";
    exit();
}

// Kiểm tra trạng thái đơn hàng (chỉ hủy nếu trạng thái là "Đang chờ xác nhận" = 1)
if ($order[0]['statusOrder'] != 1) {
    echo "<script>alert('Chỉ có thể hủy đơn hàng ở trạng thái Đang chờ xác nhận!'); window.location.href = 'order.php';</script>";
    exit();
}

// Cập nhật trạng thái đơn hàng thành "Hủy đơn" (7)
$sql_update = "UPDATE orders SET statusOrder = ? WHERE idOrder = ?";
$result = $dbHelper->execute($sql_update, [7, $idOrder]);

if ($result) {
    echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Hủy đơn hàng thành công!',
                    text: 'Đơn hàng đã được hủy.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#69BA31'
                }).then(() => {
                    window.location.href = 'order.php';
                });
            });
        </script>
        ";
} else {
    echo "<script>alert('Hủy đơn hàng thất bại, vui lòng thử lại sau!'); window.location.href = 'order.php';</script>";
}
?>
