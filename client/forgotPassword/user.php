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
            $updateUser = $dbHelper->update('users', $data  ,"email = '$email'");
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
    if (
        isset($_POST['otp']) && !empty($_POST['otp']) &&
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['passConfirm-forgot']) && !empty($_POST['passConfirm-forgot'])
    ) {
        $email = $_SESSION['email'];
        $otp = $_POST['otp'];
        $password = $_POST['password'];
        $passConfirm = $_POST['passConfirm-forgot'];
        // Check if the passwords match
        if(strlen($password) < 6){
            $errors['password'] = "Password phải lớn hơn 6 kí tự.";
        }
        if(strlen($passConfirm) < 6){
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
               // Perform password reset logic here
               $isReset = $dbHelper->update('users', array('password'=>$password), "email = '$email'");
               $_SESSION['success'] = true;
               header('Location: http://localhost/project1-fall2024/client/login.php');
            }  else {
                 $errors['otp'] = "Email or OTP is incorrect or expired.";
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
?>