<?php
include_once("./DBUntil.php");
session_start();
$dbHelper = new DBUntil();
function isVietnamesePhoneNumber($number)
{
    return preg_match('/^(03|05|07|08|09|01[2689])[0-9]{8}$/', $number) === 1;
}
function ischeckmail($email)
{
    $dbHelper = new DBUntil();
    $emailExists = $dbHelper->select("SELECT email FROM users WHERE email = ?", [$email]);
    return count($emailExists) > 0;
}
function ischeckUsername($username)
{
    $dbHelper = new DBUntil();
    $UsernameExists = $dbHelper->select("SELECT username FROM users WHERE username = ?", [$username]);
    return count($UsernameExists) > 0;
}
function ischeckPhone($phone)
{
    $dbHelper = new DBUntil();
    $PhoneExists = $dbHelper->select("SELECT phone FROM users WHERE phone = ?", [$phone]);
    return count($PhoneExists) > 0;
}
$errors = [];
$email = "";
$username = "";
$password = "";
$passwordConfirm = "";
$role = "user";
$image = "User-avatar.png";
$status = "Đang hoạt động";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['username']) || empty($_POST['username'])) {
        $errors['username'] = "Tên đăng nhập là bắt buộc";
    } else {
        if (ischeckUsername($_POST["username"])) {
            $errors['username'] = "Tên đăng nhập đã tồn tại";
        } else {
            $username = $_POST['username'];
        }
    }
    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = "Email là bắt buộc";
    } else {
        if (ischeckmail($_POST["email"])) {
            $errors['email'] = "Email đã tồn tại";
        } else {
            $email = $_POST['email'];
        }
    }
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = "Mật khẩu là bắt buộc";
    } elseif (strlen($_POST['password']) < 6) {
        $errors['password'] = "Mật khẩu phải có độ dài ít nhất 6 ký tự.";
    } else {
        $password = $_POST['password'];
    }
    if (!isset($_POST['passwordConfirm']) || empty($_POST['passwordConfirm'])) {
        $errors['passwordConfirm'] = "Xác nhận mật khẩu là bắt buộc";
    } elseif (strlen($_POST['passwordConfirm']) < 6) {
        $errors['passwordConfirm'] = "Mật khẩu phải có độ dài ít nhất 6 ký tự.";
    } elseif ($_POST['passwordConfirm'] != $password) {
        $errors['passwordConfirm'] = "Xác nhận mật khẩu không đúng";
    } else {
        $passwordConfirm = $_POST['passwordConfirm'];
    }

    // If no errors, insert data into the database
    if (empty($errors)) {
        $data = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'status' => $status,
            'image' => $image,
        ];

        $isCreate = $dbHelper->insert('users', $data);

        if ($isCreate) {
            $_SESSION['success'] = true;
            header("Location: register.php"); // Chuyển hướng về cùng trang để thực hiện thông báo
            exit(); // Dừng thực thi mã sau khi chuyển hướng
        } else {
            $errors['database'] = "Failed to create new user";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<style>
    body {
        background: linear-gradient(rgba(117, 245, 25, 0.6), rgba(105, 186, 49, 0.6)), url('./images/background.jpg') no-repeat center center fixed;
        background-size: cover;
    }
</style>

<body id="login" style="background-color: var(--color); ">
<?php if (isset($_SESSION['success']) && $_SESSION['success'] === true): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Đăng ký thành công!',
                text: 'Tài khoản của bạn đã được tạo thành công.',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-ok-button'
                }
            }).then(() => {
                window.location.href = 'login.php'; // Chuyển hướng đến trang đăng nhập
            });
        });
    </script>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-5" style="width: 30rem; background-color: var(--color-header); ">
            <img src="./images/logo_du_an_1 2.png" class="d-block mx-auto mb-4" alt="Logo" style="width: 130px; height: 100px;">
            <h3 class="text-center mb-4">Đăng kí</h3>
            <form method="post" action="">
                <div class="form-group mb-3">
                <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Nhập email" required>
                    <?php if (isset($errors['email'])): ?>
                        <span class='errors text-danger'><?= $errors['email'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                <label for="username">Tên đăng nhập</label>
                    <input type="text" name="username" class="form-control" id="username" placeholder="Tên đăng nhập của bạn" required>
                    <?php if (isset($errors['username'])): ?>
                        <span class='errors text-danger'><?= $errors['username'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                <label for="password">Mật khẩu</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Nhập mật khẩu" required>
                    <?php if (isset($errors['password'])): ?>
                        <span class='errors text-danger'><?= $errors['password'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                <label for="passwordConfirm">Xác nhận lại mật khẩu</label>
                    <input type="password" name="passwordConfirm" class="form-control" id="passwordConfirm" placeholder="Nhập lại mật khẩu" required>
                    <?php if (isset($errors['passwordConfirm'])): ?>
                        <span class='errors text-danger'><?= $errors['passwordConfirm'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between mt-3">
                <a href="login.php" class="text-decoration-none text-primary">Bạn đã có tài khoản? Đăng nhập</a>
                </div>

                <button id="submit" style="background-color: var(--color); color: var(--color-main);" onclick="alertSuccessfully('ĐĂNG KÍ')" type="submit" class="btn btn-submit form-control mt-3">Đăng kí</button>
            </form>
        </div>
    </div>
   
</body>

</html>
<style>
    .custom-ok-button {
        background-color: #69BA31 !important;
        color: white !important;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .custom-ok-button:hover {
        background-color: #57a228 !important;
    }
</style>
