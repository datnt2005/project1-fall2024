<?php
include "../../../client/DBUntil.php";
session_start();
$role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    //     header("Location: ../../../client/login.php"); // Chuyển hướng đến trang đăng nhập
    //     exit;
    // }$role = $_SESSION['role'] ?? null;
$dbHelper = new DBUntil();
$idProduct = $_SESSION['idProduct'];
$idPicProduct = $_GET['id'];
var_dump($id);
$products = $dbHelper->delete("picproduct", "idPicProduct = $idPicProduct");
header("Location: list.php?id=$idProduct");   