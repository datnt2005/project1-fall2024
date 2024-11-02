<?php
include "../../../client/DBUntil.php";
$dbHelper = new DBUntil();
$id = $_GET['id'];
var_dump($id);
$products = $dbHelper->delete("sizes", "idSize = $id");
header("Location: list.php");   