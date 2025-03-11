<?php
require './connnect.php';

if (!isset($_GET['province_id'])) {
    echo json_encode(["error" => "Missing province_id"]);
    exit();
}

$province_id = intval($_GET['province_id']); // Chuyển đổi thành số nguyên

// Lấy danh sách huyện theo `province_id`
$sql = "SELECT * FROM `district` WHERE `province_id` = $province_id";
$result = mysqli_query($conn, $sql);

$data = [];

if (!$result) {
    echo json_encode(["error" => "Query failed"]);
    exit();
}

// Mặc định thêm lựa chọn "Chọn một Quận/huyện"
$data[] = [
    'id' => '',
    'name' => 'Chọn một Quận/huyện'
];

// Lấy dữ liệu từ database
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $row['district_id'],
        'name' => $row['name']
    ];
}

// Trả về dữ liệu JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
