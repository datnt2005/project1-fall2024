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
$name = "";
$phone = "";
$passwordConfirm = "";
$role = "user";
$image = "User-avatar.png";
$status = "Đang hoạt động";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = "Tên là bắt buộc";
    } else {
        $name = $_POST['name'];
    }
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
    if (!isset($_POST['phone']) || empty($_POST['phone'])) {
        $errors['phone'] = "Số điện thoại là bắt buộc";
    } else {
        if (!isVietnamesePhoneNumber($_POST['phone'])) {
            $errors['phone'] = "Số điện thoại không được định dạng chính xác";
        } else {
            if (ischeckPhone($_POST["phone"])) {
                $errors['phone'] = "Số điện thoại đã tồn tại";
            } else {
                $phone = $_POST['phone'];
            }
        }
    }

    // If no errors, insert data into the database
    if (empty($errors)) {
        $data = [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => $role,
            'status' => $status,
            'image' => $image,
        ];

        $isCreate = $dbHelper->insert('users', $data);

        if ($isCreate) {
            // Redirect to the same page to see the new record in the table
            $_SESSION['success'] = true;
            header("Location: login.php " . $_SERVER['PHP_SELF']);
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
<div id="alerts-container"></div>
<?php if (isset($_SESSION['success']) && $_SESSION['success'] === true): ?>
        <script>
            alert('Đăng kí thành công!');
        </script>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-3" style="width: 40rem; background-color: var(--color-header); padding: 1rem 2rem;  ">
            <img src="./images/logo_du_an_1 2.png" class="d-block mx-auto" alt="" style="width: 150px; height: 130px;">
            <h3 class="text-center mb-4">Đăng nhập bằng</h3>
            <div class="d-flex justify-content-between mt-3">

            </div>
            <hr>
            <h3 class="text-center mb-2">Đăng kí</h3>
            <form method="post" action="">
                <div class="form-group mb-3">
                    <label for="name">Họ và Tên</label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="Nhập họ và tên" required>
                    <?php if (isset($errors['name'])): ?>
                        <span class='errors text-danger'><?= $errors['name'] ?></span>
                    <?php endif; ?>
                </div>

                <div class="form-group mb-3">
                    <label for="phone">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" id="phone" placeholder="Nhập số điện thoại" required>
                    <?php if (isset($errors['phone'])): ?>
                        <span class='errors text-danger'><?= $errors['phone'] ?></span>
                    <?php endif; ?>
                </div>

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