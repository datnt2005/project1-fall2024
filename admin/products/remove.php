<?php
include "../../client/DBUntil.php";
session_start();
$role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../../client/login.php"); // Chuyển hướng đến trang đăng nhập
        exit;
    }
$dbHelper = new DBUntil();
$idProduct = $_GET['id'];


    // Xóa các bản ghi trong bảng picproduct
    $picProduct = $dbHelper->delete("picproduct", "idProduct = $idProduct");

    // Xóa các bản ghi trong bảng product_size_color
    $product_size_color = $dbHelper->delete("product_size_color", "idProduct = $idProduct");

    // Xóa sản phẩm trong bảng products
    $product = $dbHelper->delete("products", "idProduct = $idProduct");
    
    // Chuyển hướng sau khi xóa thành công
    header("Location: list.php");


?>
