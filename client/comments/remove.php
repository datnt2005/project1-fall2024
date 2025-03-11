<?php
include "./../DBUntil.php";
$dbHelper = new DBUntil();
$idComment = $_GET['idComment'];
$idProduct = $_GET['idProduct'];
var_dump($idComment);
var_dump($idProduct);
$removePicComment = $dbHelper->delete("piccomment", "idComment = $idComment");
$removeComment = $dbHelper->delete("comments", "idComment = $idComment");

    header('Location: ../detailProduct.php?id=' . $idProduct);

?>