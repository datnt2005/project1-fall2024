<?php
session_start();
include_once("./DBUntil.php");
$dbHelper = new DBUntil();
var_dump($_SESSION['idUser']) ?? null;
$user_id = $_GET['id'];
$users = $dbHelper->select("SELECT * FROM users WHERE idUser = ?", [$user_id]);
$userPassword = $users[0]['password'];
// var_dump($userPassword);
$errors =[];
$newPassword = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = "Mật không cũ là bắt buộc";
    }else if($_POST['password'] != $userPassword){
        $errors['password'] = "Mật không cũ không đúng";
    }
    if (!isset($_POST['newPassword']) || empty($_POST['newPassword'])) {
        $errors['newPassword'] = "Mật khẩu mới là bắt buộc";
    } else if (strlen($_POST['newPassword']) < 6) {
      $errors['newPassword'] = "Mật khẩu mới phải lớn hơn 6 kí tự";
    }
    if (!isset($_POST['passConfirm-forgot']) || empty($_POST['passConfirm-forgot'])) {
        $errors['passConfirm-forgot'] = "Xác nhận mật khẩu là bắt buộc";
    } elseif($_POST['passConfirm-forgot'] != $_POST['newPassword']){
        $errors['passConfirm-forgot'] = "Xác nhận mật khẩu không đúng";
    }elseif (strlen($_POST['passConfirm-forgot']) < 6) {
        $errors['passConfirm-forgot'] = "Xác nhận mật khẩu phải lớn hơn 6 kí tự";
    }

    if (empty($errors)) {
        $newPassword = $_POST['newPassword'];
        $updatePassword = $dbHelper->update('users', array('password' => $newPassword), "idUser = '$user_id'");
        if ($updatePassword) {
            echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Đổi mật khẩu thành công!',
                            text: 'Bạn có thể tiếp tục sử dụng tài khoản của mình.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#69BA31' // Màu sắc nút OK
                        }).then(() => {
                            window.location.href = './detail_user.php?id=$user_id';
                        });
                    });
                </script>
            ";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<body>
    <?php include "./includes/header.php" ?>

    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến
                với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="d-flex   align-items-center">
                <p class=" m-1 fs-5 fw-bold">Trang chủ/ thông tin người dùng</p>
            </div>
        </div>
        <div class="container form">
            <div class="row">
                <div class="col-md-3 mt-1">
                    <div class="aside-account mt-4">
                        <h2 class="fw-bold fs-3">TÀI KHOẢN</h2>
                        <ul class="mx-3 aside-list">
                            <li class="nav-link">
                                <a href="#" class="text-decoration-none aside-account-list focus-in">Thông tin tài
                                    khoản</a>
                            </li>
                            <li class="nav-link">
                                <a href="#" class="text-decoration-none aside-account-list">Địa chỉ</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <section id="forgotPassword">
                        <div class="container">
                            <div class="row">
                                <div class="d-flex justify-content-center forgot-pass mb-4">
                                    <div class="forgot-main p-4" style="width: 400px">
                                        <form action="" method="POST" class="">
                                            <h2 class="text-center fw-bold mb-4">Đổi mật khẩu</h2>
                                            <div>
                                                <label for="" class="text-small">Nhập mật khẩu cũ<span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="password" id="password"
                                                    class="d-block w-100 value-forgot mt-1">
                                                <i class="fa-regular fa-eye show-password d-none"
                                                    onmousedown="showPassword(this)" onmouseup="endPass(this)"
                                                    onmouseleave="endPass(this)"></i>
                                                    <?php
                                                 if (isset($errors['password'])) {
                                                    echo "<span class='errors text-danger'>{$errors['password']}</span>";
                                                }
                                            ?>
                                            </div>
                                            <div class="mt-3">
                                                <label for="" class="text-small">Mật khẩu mới <span
                                                        class="text-danger">*</span></label>
                                                <input type="Password" name="newPassword" id="newPassword"
                                                    class="d-block w-100 value-forgot mt-1">
                                                <i class="fa-regular fa-eye show-password d-none"
                                                    onmousedown="showPassword(this)" onmouseup="endPass(this)"
                                                    onmouseleave="endPass(this)"></i>
                                                    <?php
                                                 if (isset($errors['newPassword'])) {
                                                    echo "<span class='errors text-danger'>{$errors['newPassword']}</span>";
                                                }
                                            ?>
                                            </div>
                                            <div class="mt-3">
                                                <label for="" class="text-small">Nhập lại mật khẩu mới <span
                                                        class="text-danger">*</span></label>
                                                <input type="password" name="passConfirm-forgot" id="passConfirm-forgot"
                                                    oninput="change(this)" class="d-block w-100 value-forgot mt-1">
                                                <i class="fa-regular fa-eye show-password d-none"
                                                    onmousedown="showPassword(this)" onmouseup="endPass(this)"
                                                    onmouseleave="endPass(this)"></i>
                                                    <?php
                                                 if (isset($errors['passConfirm-forgot'])) {
                                                    echo "<span class='errors text-danger'>{$errors['passConfirm-forgot']}</span>";
                                                }
                                            ?>
                                            </div>
                                            <button
                                                style="margin-top: 20px; background-color: var(--color); color: var(--color-main);"
                                                type="submit" name="action" value="reset"
                                                class="btn form-control btn-login">Đổi lại mật khẩu</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </div>
    </main>

    <?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>