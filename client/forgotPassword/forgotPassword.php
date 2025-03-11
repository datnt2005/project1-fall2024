<?php
require_once('./user.php');
$email = '';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = "Email là bắt buộc";
    } else {
        $email = $_POST['email'];
    }
    if (count($errors) == 0) {
        $isCheck = $dbHelper->select("SELECT * FROM users WHERE email = ?", [$email]);

        if (!$isCheck || count($isCheck) == 0) {
            $errors['email'] = "Email không tồn tại";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>

    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/1d3d4a43fd.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(rgba(117, 245, 25, 0.6), rgba(105, 186, 49, 0.6)), url('../images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body style="background-color: var(--color); ">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div id="forgotPassword" class="card p-5" style="width: 40rem; background-color: var(--color-header);">
            <img src="../images/logo_du_an_1 2.png" class="d-block mx-auto" alt="" style="width: 180px; height: 130px;">
            <h3 class="text-center mb-2">Quên mật khẩu</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email"></label>
                    <input type="email" name="email" class="form-control" id="email-forgot" placeholder="Nhập email" required>
                    <?php
                    if (isset($errors['email'])) {
                        echo "<span class='errors text-danger'>{$errors['email']}</span>";
                    }
                    ?>
                </div>
                <button id="submit" style="margin-top: 20px; background-color: var(--color); color: var(--color-main);"
                    type="submit" name="action" value="forgot" class="btn form-control btn-forgot">Quên mật khẩu</button>
                <a href="../login.php" class="text-decoration-none text-success">Bạn đã có tài khoản</a>
            </form>
        </div>
    </div>
</body>

</html>