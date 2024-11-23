<?php

use Google\Service\Adsense\Alert;

include_once("./DBUntil.php");
$dbHelper = new DBUntil();
session_start();

// Initialize the $errors array
$errors = [];

$email = "";
$password = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kiểm tra tên đăng nhập
    if (empty($_POST['email'])) {
        $errors['email'] = "Email hoặc tên đăng nhập là bắt buộc";
    } else {
        $email = trim($_POST['email']); // Dùng trim để loại bỏ khoảng trắng thừa
    }

    // Kiểm tra mật khẩu
    if (empty($_POST['password'])) {
        $errors['password'] = "Mật khẩu là bắt buộc";
    } elseif (strlen($_POST['password']) < 6) {
        $errors['password'] = "Mật khẩu phải có độ dài ít nhất 6 ký tự.";
    } else {
        $password = $_POST['password'];
    }

    // Nếu không có lỗi, kiểm tra thông tin đăng nhập
    if (count($errors) == 0) {
        $query = $dbHelper->select("SELECT * FROM users WHERE (email = ? OR username = ?) AND password = ?", [$email, $email, $password]);

        if (count($query) > 0) {
            // Đăng nhập thành công
            $_SESSION['idUser'] = $query[0]['idUser'];
            $_SESSION['success'] = true;
            $_SESSION['role'] = $query[0]['role'];
            header('Location: index.php');
            exit();
        } else {
            $errors['login'] = "Sai Tên đăng nhập hoặc Mật khẩu.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php"; ?>
<style>
    body {
        background: linear-gradient(rgba(117, 245, 25, 0.6), rgba(105, 186, 49, 0.6)), url('./images/background.jpg') no-repeat center center fixed;
        background-size: cover;
    }
</style>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-5" style="width: 30rem; background-color: var(--color-header); ">
            <img src="./images/logo_du_an_1 2.png" class="d-block mx-auto mb-4" alt="Logo" style="width: 130px; height: 100px;">
            <h3 class="text-center mb-4">Đăng nhập bằng</h3>
            <div class="d-flex justify-content-between mt-2">
                    <?php
                    require_once 'facebook-login/login.php';
                    ?>
            </div>
            <div class="d-flex justify-content-between mt-2">
                <?php
                require_once 'php-google-login/google-login.php';
                ?>
            </div>
            <hr>
            <h3 class="text-center mb-4">Đăng nhập</h3>
            <form method="post" action="">
                <div class="form-group mb-3">
                    <label for="email" class="form-label">Tên đăng nhập</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="Nhập tên đăng nhập của bạn" required>
                    <?php if (isset($errors['email'])): ?>
                        <span class='errors text-danger'><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="password" class="form-label">Mật khẩu</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Nhập mật khẩu của bạn" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class='errors text-danger'><?= $errors['password'] ?></span>
                    <?php endif; ?>
                </div>

                <button style="margin-top: 20px; background-color: var(--color); color: var(--color-main);" type="submit" class="btn form-control btn-submit">
                    Đăng nhập
                </button>
                <?php if (isset($errors['login'])): ?>
                    <span class='text-danger mt-2 d-block'><?= $errors['login'] ?></span>
                <?php endif; ?>

                <div class="d-flex justify-content-between mt-3">
                    <a href="forgotPassword/forgotPassword.php" class="text-decoration-none text-danger">Quên mật khẩu?</a>
                    <a href="register.php" class="text-decoration-none text-primary">Đăng ký</a>
                </div>
            </form>
        </div>
    </div>

</body>

</html>