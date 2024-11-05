<?php
include "../../client/DBUntil.php";
$dbHelper = new DBUntil();
$id = $_GET['id'];
var_dump($id);
$categories = $dbHelper->delete("subcategory", "idSubCategory = $id");
header("Location: list.php");   