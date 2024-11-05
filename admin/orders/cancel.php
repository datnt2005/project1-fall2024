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
$idOrder = $_GET['id'];
var_dump($idOrder);
$data = ["statusOrder" => 7];
$updateOrder = $dbHelper->update("orders", $data, "idOrder = $idOrder");
header("Location: list.php");   