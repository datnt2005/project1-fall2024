<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
require './connnect.php';
var_dump($_SESSION['idUser']) ?? null;
$login_success = false;
// echo ($_SESSION['id']);
if (isset($_SESSION['success'])) {
    $login_success = true;
}

// // Xử lý form khi người dùng nhấn "Thêm địa chỉ"
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $phone = $_POST['phone'];
//     $village = $_POST['village'];
//     $district = $_POST['district'];
//     $province = $_POST['province'];
//     $detail_address = $_POST['detail_address'];

//     // Kiểm tra dữ liệu và thêm địa chỉ vào cơ sở dữ liệu
//     if ($phone && $village && $district && $province && $detail_address) {
//         $sql = "INSERT INTO address (user_id, phone, village, district, province, detailed_address) 
//                 VALUES ('$idUser', '$phone', '$village', '$district', '$province', '$detail_address')";
//         if (mysqli_query($conn, $sql)) {
//             header("Location: checkout.php?id=$idUser"); // Sau khi thêm, chuyển về trang thanh toán
//         } else {
//             $error = "Đã xảy ra lỗi khi lưu địa chỉ!";
//         }
//     } else {
//         $error = "Vui lòng điền đầy đủ thông tin!";
//     }
// }

$sql = "SELECT * FROM province";
$result = mysqli_query($conn, $sql);

if (isset($_POST['add_sale'])) {
    echo "<pre>";
    print_r($_POST);
    die();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $village = $_POST['village'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $wards = $_POST['wards'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    $user_id = $_SESSION['idUser'] ?? null;
    if (!$user_id || empty($name) || empty($phone) || empty($email) || empty($village) || empty($province) || empty($district) || empty($wards)) {
        echo "<script>alert('Vui lòng điền đầy đủ thông tin!')</script>";
    } else {
        if ($is_default) {
            $resetDefault = "UPDATE detail_address SET is_default = 0 WHERE user_id = '$user_id'";
            mysqli_query($conn, $resetDefault);
        }

        $sql = "INSERT INTO detail_address (user_id, name, phone, email, village, province_id, district_id, ward_id, is_default)
                VALUES ('$user_id', '$name', '$phone', '$email', '$village', '$province', '$district', '$wards', '$is_default')";

        if (mysqli_query($conn, $sql)) {
            header("Location: listAddress.php?id=$user_id");
            exit();
        } else {
            echo"<script>alert('Xay ra loi khi luu thông tin!')</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<?php include "./includes/head.php" ?>

<body>
    <?php include "./includes/header.php" ?>
    <div class="container form">
        <div class="row">

            <div class="col-md-12 mt-5">
                <section id="forgotPassword">
                    <div class="container">
                        <div class="row">
                            <div class="d-flex justify-content-center forgot-pass mb-4">
                                <div class="forgot-main p-4">
                                    <form action="" method="POST" class="" enctype="multipart/form-data" style="width: 500px;">
                                        <h2 class="text-center fw-bold mb-4">Thông tin địa chỉ</h2>
                                        <div class="mt-3">
                                            <label for="name">Họ và tên</label>
                                            <input type="text" name="name" class="form-control" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="phone">Số điện thoại</label>
                                            <input type="text" name="phone" class="form-control" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="email">Email</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="village">Địa chỉ cụ thể</label>
                                            <input type="text" name="village" class="form-control" required>
                                        </div>
                                        <div class="mt-3">
                                            <label for="province">Tỉnh/Thành phố</label>
                                            <select id="province" name="province" class="form-control" required>
                                                <option value="">Chọn một tỉnh</option>
                                                <?php
                                                $sql = "SELECT * FROM province";
                                                $result = mysqli_query($conn, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                    echo '<option value="' . $row['province_id'] . '">' . $row['name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <label for="district">Quận/Huyện</label>
                                            <select id="district" name="district" class="form-control" required>
                                                <option value="">Chọn một quận/huyện</option>
                                            </select>
                                        </div>
                                        <div class="mt-3">
                                            <label for="wards">Phường/Xã</label>
                                            <select id="wards" name="wards" class="form-control" required>
                                                <option value="">Chọn một xã/phường</option>
                                            </select>
                                        </div>
                                        <div class="mt-3 form-check">
                                            <input class="form-check-input" type="radio" name="is_default" id="is_default" value="1">
                                            <label class="form-check-label" for="is_default">
                                                Đặt làm địa chỉ mặc định
                                            </label>
                                        </div>
                                        <button style="margin-top: 20px; background-color: var(--color); color: var(--color-main);" type="submit" class="btn form-control btn-login">Lưu địa chỉ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

        </div>
    </div>
    <style>

    </style>
    <?php include "./includes/footer.php" ?>
</body>

</html>