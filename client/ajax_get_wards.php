<?php
require './connnect.php';

$district_id = $_GET['district_id']; // Lấy ID huyện từ yêu cầu AJAX

// Lấy danh sách xã/phường từ cơ sở dữ liệu
$sql = "SELECT * FROM `wards` WHERE `district_id` = {$district_id}";
$result = mysqli_query($conn, $sql);

$data[] = [
    'id' => null,
    'name' => 'Chọn một xã/phường'
];

// Duyệt qua các xã/phường và đưa vào mảng dữ liệu
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $row['wards_id'],
        'name' => $row['name']
    ];
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($data);
?>
