<?php
require './connnect.php';

$province_id = $_GET['province_id']; // Lấy ID tỉnh từ yêu cầu AJAX

// Lấy danh sách huyện từ cơ sở dữ liệu
$sql = "SELECT * FROM `district` WHERE `province_id` = {$province_id}";
$result = mysqli_query($conn, $sql);

$data[] = [
    'id' => null,
    'name' => 'Chọn một Quận/huyện'
];

// Duyệt qua các huyện và đưa vào mảng dữ liệu
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $row['district_id'],
        'name' => $row['name']
    ];
}

// Trả về dữ liệu dưới dạng JSON
echo json_encode($data);
?>
