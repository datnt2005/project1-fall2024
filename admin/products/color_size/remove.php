<?php
include "../../../client/DBUntil.php";
session_start();
$role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    //     header("Location: ../../../client/login.php"); // Chuyển hướng đến trang đăng nhập
    //     exit;
    // }
$dbHelper = new DBUntil();
$idProduct = $_SESSION['idProduct'];
$idProductSize = $_GET['id'];
var_dump($id);
$product_size = $dbHelper->delete("product_size", "idProductSize = $idProductSize");
header("Location: list.php?id=$idProduct");   