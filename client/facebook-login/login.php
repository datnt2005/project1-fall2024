<?php
// require_once 'fbconfig.php';

// $permissions = ['email']; // Quyền truy cập
// $loginUrl = $helper->getLoginUrl('https://localhost/project1-fall2024/client/facebook-login/fb-callback.php', $permissions);

echo '<a class="fb-login-btn" href="">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/51/Facebook_f_logo_%282019%29.svg" alt="Facebook logo">
        Đăng nhập bằng Facebook
      </a>';
?>
<style>
        .fb-login-btn {
            margin-left: 50px;
            display: inline-block;
            background-color: white;
            color: black;
            padding: 7px 20px;
            border-radius:  20px;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
            height: 40px;
        }

       
        .fb-login-btn img {
            vertical-align: middle;
            margin-right: 45px;
            width: 20px;
            height: 20px;
        }
    </style>