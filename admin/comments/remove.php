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
$idComment = $_GET['id'];
var_dump($id);
$deletePicComment = $dbHelper->delete("piccomment", "idComment = $idComment");
$deleteComment = $dbHelper->delete("comments", "idComment = $idComment");
header("Location: list.php");   