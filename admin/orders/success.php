<?php
include "../../client/DBUntil.php";
session_start();
$role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    // if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    //     header("Location: ../../client/login.php"); // Chuyển hướng đến trang đăng nhập
    //     exit;
    // }
$dbHelper = new DBUntil();
$idOrder = $_GET['id'];
$listOder = $dbHelper-> select("SELECT ord.*, dadr.*, dor.*,
                                        GROUP_CONCAT(DISTINCT CONCAT(prd.nameProduct, ' (', dor.sizeOrder, ')') SEPARATOR ', ') AS products
                                        FROM orders ord
                                        INNER JOIN detailorder dor ON ord.idOrder = dor.idOrder
                                        INNER JOIN products prd ON dor.idProduct = prd.idProduct
                                        INNER JOIN detail_address dadr ON ord.idAddress = dadr.detail_id 
                                        WHERE ord.idOrder = $idOrder GROUP BY ord.idOrder");
$idProduct = $listOder[0]['idProduct'];
$size = $listOder[0]['sizeOrder'];
$price = $listOder[0]['price'];
$product = $dbHelper->select("SELECT prd.*, ps.*, s.* FROM products prd
                                INNER JOIN product_size ps ON prd.idProduct = ps.idProduct
                                INNER JOIN sizes s ON ps.idSize = s.idSize
                                WHERE prd.idProduct = ? AND s.nameSize = ?", [$idProduct, $size]);
var_dump($product);
$idPS = $product[0]['idProductSize'];
$quantity = $product[0]['quantityProduct'] - $listOder[0]['quantityOrder'];
$data = [
    "quantityProduct" => $quantity,
    "price" => $product[0]['price'],
    "idSize" => $product[0]['idSize'],
];
$updateProduct = $dbHelper->update("product_size", $data, "idProductSize = $idPS");

var_dump($idOrder);
$data = ["statusOrder" => 3];
$updateOrder = $dbHelper->update("orders", $data, "idOrder = $idOrder");
if($updateOrder && $updateProduct) {
    header("Location: list.php");
}
 