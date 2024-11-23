<?php
require_once('./user.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // if (!isset($_POST['email-forgot']) || empty($_POST['email-forgot'])) {
    //     $errors['email-forgot'] = "Email là bắt buộc";
    // } 
    if (!isset($_POST['otp']) || empty($_POST['otp'])) {
        $errors['otp'] = "OTP là bắt buộc";
    }
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = "Mật khẩu là bắt buộc";
    } else if (strlen($_POST['password']) < 6) {
        $errors['password'] = "Mật khẩu phải lớn hơn 6 kí tự";
    }
    if (!isset($_POST['passConfirm-forgot']) || empty($_POST['passConfirm-forgot'])) {
        $errors['passConfirm-forgot'] = "Xác nhận mật khẩu là bắt buộc";
    } elseif ($_POST['passConfirm-forgot'] != $_POST['password']) {
        $errors['passConfirm-forgot'] = "Xác nhận mật khẩu không đúng";
    } elseif (strlen($_POST['passConfirm-forgot']) < 6) {
        $errors['passConfirm-forgot'] = "Xác nhận mật khẩu phải lớn hơn 6 kí tự";
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
    <div id="forgotPassword" class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-5" style="width: 40rem; background-color: var(--color-header);">
            <img src="../images/logo_du_an_1 2.png" class="d-block mx-auto" alt="" style="width: 180px; height: 130px;">
            <h3 class="text-center mb-2">Đổi mật khẩu</h3>
            <form action="" method="POST">
                <div class="form-group">
                    <label for="otp"></label>
                    <input type="otp" class="form-control" name="otp" id="otp" placeholder="Nhập otp" required>
                    <?php
                    if (isset($errors['otp'])) {
                        echo "<span class='errors text-danger'>{$errors['otp']}</span>";
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="password"></label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Nhập mật khẩu mới" required>
                    <?php
                    if (isset($errors['password'])) {
                        echo "<span class='errors text-danger'>{$errors['password']}</span>";
                    }
                    ?>
                </div>
                <div class="form-group">
                    <label for="confirmpassword"></label>
                    <input type="confirmpassword" class="form-control" name="passConfirm-forgot" id="passConfirm-forgot"
                        placeholder="Nhập lại mật khẩu mới" required>
                    <?php
                    if (isset($errors['passConfirm-forgot'])) {
                        echo "<span class='errors text-danger'>{$errors['passConfirm-forgot']}</span>";
                    }
                    ?>
                </div>
                <button id="submit" style="margin-top: 20px; background-color: var(--color); color: var(--color-main);"
                    type="submit" name="action" value="reset" class="btn form-control">Xác nhận</button>
            </form>
        </div>
    </div>
</body>

</html>