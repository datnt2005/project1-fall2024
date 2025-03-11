<?php
require_once 'config.php';
include_once ("../DBUntil.php");
session_start();
$dbHelper = new DBUntil();

// authenticate code from Google OAuth Flow
// Nếu người dùng chọn Allow để cấp quyền cho trang truy cập thông tin, page sẽ nhận được code
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();

    $userinfo = [
        'email' => $google_account_info['email'],
        'name' => $google_account_info['name'],
        'role' => 'user',
        'status' => "Đang hoạt động",
        'token' => $google_account_info['id'],
    ];

    // Kiểm tra xem người dùng đã tồn tại trong cơ sở dữ liệu chưa
    $sql = "SELECT * FROM users WHERE email = :email";
    $params = ['email' => $userinfo['email']];
    $result = $dbHelper->select($sql, $params);

    if (count($result) > 0) {
        // Người dùng đã tồn tại, có thể cập nhật thông tin hoặc chuyển hướng đến trang khác
        $_SESSION['success'] = true;
        header("Location: http://localhost/project1-fall2024/client/index.php");
        $_SESSION['idUser'] = $result[0]['idUser'];
    } else {
        // Người dùng chưa tồn tại, thêm mới vào cơ sở dữ liệu
        $isCreate = $dbHelper->insert('users', $userinfo);
        if ($isCreate) {
            // Lấy ID của người dùng mới tạo và lưu vào session
            $_SESSION['idUser'] = $dbHelper->lastInsertId();
            // Redirect to the same page to see the new record in the table
            header("Location: http://localhost/project1-fall2024/client/index.php");
            echo "<script>alert('Đăng ký tài khoản thành công!');</script>";
            exit();
        } else {
            $errors['database'] = "Failed to create new user";
        }
    }
}
?>