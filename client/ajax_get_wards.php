<?php
require './connnect.php';

if (!isset($_GET['district_id'])) {
    echo json_encode(["error" => "Missing district_id"]);
    exit();
}

$district_id = intval($_GET['district_id']); // Chuyển đổi thành số nguyên

// Lấy danh sách xã theo `district_id`
$sql = "SELECT * FROM `wards` WHERE `district_id` = $district_id";
$result = mysqli_query($conn, $sql);

$data = [];

if (!$result) {
    echo json_encode(["error" => "Query failed"]);
    exit();
}

// Mặc định thêm lựa chọn "Chọn một xã/phường"
$data[] = [
    'id' => '',
    'name' => 'Chọn một xã/phường'
];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = [
        'id' => $row['wards_id'],
        'name' => $row['name']
    ];
}

// Trả về dữ liệu JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
