<?php
session_start();
include "./DBUntil.php";
require './connnect.php';

// Kiểm tra user đã đăng nhập chưa
$user_id = $_SESSION['idUser'] ?? null;

if (!$user_id) {
    echo "<script>alert('Bạn cần đăng nhập trước!'); window.location.href = 'login.php';</script>";
    exit();
}

// Lấy ID địa chỉ từ URL
$detail_id = $_GET['detail_id'] ?? null;

if (!$detail_id) {
    echo "<script>alert('Không tìm thấy địa chỉ!'); window.location.href = 'address.php';</script>";
    exit();
}

// kiểm tra địa chỉ
$sql = "SELECT * FROM detail_address WHERE detail_id = '$detail_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    echo "<script>alert('Không tìm thấy địa chiề!'); window.location.href = 'address.php';</script>";
    exit();
}

// Thực hiện chức năng
$delete_sql = "DELETE FROM detail_address WHERE detail_id = '$detail_id' AND user_id = '$user_id'";

if (mysqli_query($conn, $delete_sql)) {
    echo "<script>alert('Xóa địa chỉ thành công!'); window.location.href = 'listAddress.php';</script>";
} else {
    echo "<script>alert('Xóa địa chỉ không thành công!'); window.location.href = 'listAddress.php';</script>";
}
?>