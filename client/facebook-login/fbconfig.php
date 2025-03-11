<?php
require_once 'autoload.php';
session_start();
$fb = new Facebook\Facebook([
    'app_id' => '870464665252489', // Replace {app-id} with your App ID
    'app_secret' => 'da128f72b682211e64f1784bce1f3844',
    'default_graph_version' => 'v2.5',
]);
$helper = $fb->getRedirectLoginHelper();
$permissions = ['email']; // optional
$login_Url = $helper->getLoginUrl('http://localhost/project1-fall2024/client/facebook-login/fb-callback.php', $permissions);
?>