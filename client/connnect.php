<?php 

// Replace with your MySQL server settings
$host = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "project1-fall2024"; 

// Create connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// echo "Connected successfully!";