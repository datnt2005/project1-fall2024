<?php
session_start();
unset($_SESSION['idUser']);
session_destroy();
header("Location: index.php");
?>