<?php
require_once 'vendor/autoload.php';
// init configuration
$clientID = '386998533472-mcsvj2g5oj0um90rh05vipr0051ap2e8.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/project1-fall2024/client/php-google-login/google-login-info.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// // Connect to database
//     $hostname = "localhost";
//     $username = "root";
//     $password = "";
//     $database = "project-sum2024";

//     $conn = mysqli_connect($hostname, $username, $password, $database);
//     // if (!$conn) {
//     //     die("Connection failed: " . mysqli_connect_error());
//     // }
//     // echo "Connected successfully";
    

?>