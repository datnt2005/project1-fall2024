<?php
session_start();
ini_set('display_errors', '1');
include_once("../DBUntil.php");


use MailService\MailService as MailService;

require_once('../mail/MailService.php');
$dbHelper = new DBUntil();

function forgotPassword()
{
    global $dbHelper;
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = $_POST['email'];
        $users = $dbHelper->select("SELECT * FROM users WHERE email = :email", ['email' => $email]);
        // var_dump($users);

        if ($users && count($users) > 0) {
            $six_digit_random_number = random_int(100000, 999999);
            // update code database
            $data = [
                'otp' => $six_digit_random_number,
                'otpCreated' => date('Y-m-d H:i:s', strtotime('+1 hour')),
            ];
            $updateUser = $dbHelper->update('users', $data, "email = '$email'");
            $_SESSION['email'] = $email;
            header('Location: updatePassword.php');
            // var_dump(is_bool($updateUser));
            if ($updateUser) {
                try {
                    $result = MailService::send(
                        // send email
                        'vuahatdinhduongngon@gmail.com',
                        $email,
                        'Forgot Password',
                        "
                        <a href='http://localhost/project1-fall2024/client/forgotPassword/updatePassword.php?email=$email'>Reset Password</a>
                        Your token is: <b>$six_digit_random_number</b>"
                    );
                    // var_dump($result);
                } catch (Exception $e) {
                    var_dump($e->getMessage());
                }
            } else {
                echo "Failed to update the user with OTP.";
            }
        } else {
            $errors['email'] = "Email does not exist";
        }
    }
}

function resetPassword()
{
    global $dbHelper;
    global $errors;

    if (
        isset($_POST['otp']) && !empty($_POST['otp']) &&
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['passConfirm-forgot']) && !empty($_POST['passConfirm-forgot'])
    ) {
        $email = $_SESSION['email'];
        $otp = $_POST['otp'];
        $password = $_POST['password'];
        $passConfirm = $_POST['passConfirm-forgot'];

        // Kiểm tra độ dài mật khẩu
        if (strlen($password) < 6) {
            $errors['password'] = "Password phải lớn hơn 6 kí tự.";
        }
        if (strlen($passConfirm) < 6) {
            $errors['passConfirm-forgot'] = "Xác nhận mật khẩu phải lớn hơn 6 kí tự.";
        }
        if ($passConfirm !== $password) {
            $errors['passConfirm-forgot'] = "Xác nhận mật khẩu không đúng";
            return;
        }

        if (!isset($errors) || count($errors) == 0) {
            $isCheck = $dbHelper->select("SELECT * FROM users WHERE email = :email AND otp = :otp AND otpCreated >= :current", [
                'email' => $email,
                'otp' => $otp,
                'current' => date('Y-m-d H:i:s')
            ]);

            if ($isCheck && count($isCheck) > 0) {
                // Cập nhật mật khẩu
                $isReset = $dbHelper->update('users', ['password' => $password], "email = '$email'");

                if ($isReset) {
                    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Đổi mật khẩu thành công!',
                text: 'Bạn có thể đăng nhập với mật khẩu mới.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#69BA31' // Màu sắc cho nút OK
            }).then(() => {
                window.location.href = 'http://localhost/project1-fall2024/client/login.php';
            });
        });
    </script>
";

                    exit(); // Ngừng thực thi thêm để đảm bảo không xử lý gì sau đó.
                } else {
                    $errors['database'] = "Không thể cập nhật mật khẩu.";
                }
            } else {
                $errors['otp'] = "Email hoặc OTP không hợp lệ hoặc đã hết hạn.";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
        case "forgot":
            forgotPassword();
            break;
        case "reset":
            resetPassword();
            break;
    }
}
