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
$id = $_GET['id'];
var_dump($id);
$categories = $dbHelper->delete("categories", "idCategory = $id");
header("Location: list.php");   