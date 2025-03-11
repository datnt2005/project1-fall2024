<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
require './connnect.php';
$login_success = false;
// echo ($_SESSION['id']);
if (isset($_SESSION['success'])) {
    $login_success = true;
}

$user_id = $_SESSION['idUser'] ?? null;

if (!$user_id) {
    echo "<script>alert('Bạn cần đăng nhập trước!'); window.location.href = 'login.php';</script>";
    exit();
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


$sql = "SELECT da.*, p.name AS province_name, d.name AS district_name, w.name AS ward_name
        FROM detail_address da
        JOIN province p ON da.province_id = p.province_id
        JOIN district d ON da.district_id = d.district_id
        JOIN wards w ON da.ward_id = w.wards_id
        WHERE da.user_id = '$user_id' ";

$result = mysqli_query($conn, $sql);
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
                                    <form action="checkout.php" method="POST" class="" enctype="multipart/form-data" style="width: 500px;">
                                        <h2 class="text-center fw-bold mb-4">Địa chỉ</h2>
                                        <a href="./addAddress.php" class="btn" style="margin-top: 20px; background-color: var(--color); color: var(--color-main); text-decoration: none;">
                                            Thêm địa chỉ
                                        </a>
                                        <?php if (mysqli_num_rows($result) > 0): ?>
                                            <div class="address-list mt-4">


                                                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                    <div class="address-box border rounded p-3 mb-3 d-flex align-items-center">
                                                    <div class="form-check">
                                                         <input class="form-check-input" type="radio" name="selected_address" value="<?= $row['detail_id'] ?>" required>
                                                     </div>
                                                        <div class="address-info flex-grow-1">
                                                            <h5 class="mb-2"><?php echo htmlspecialchars($row['name']); ?></h5>
                                                            <p><strong>SĐT:</strong> <?php echo htmlspecialchars($row['phone']); ?> | <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?></p>
                                                            <p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($row['village']); ?>, <?php echo htmlspecialchars($row['district_name']); ?>, <?php echo htmlspecialchars($row['province_name']); ?>, <?php echo htmlspecialchars($row['ward_name']); ?></p>
                                                            <p class="text-danger"><strong></strong> <?php echo $row['is_default'] ? "Mặc định" : ""; ?></p>
                                                        </div>
                                                        <div class="action-address ms-3">
                                                        <a href="editAddress.php?detail_id=<?= $row['detail_id'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                                                        <a href="deleteAddress.php?detail_id=<?= $row['detail_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?')">Xóa</a>
                                                        </div>
                                                    </div>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php else: ?>
                                            <p class="mt-4">Bạn chưa có địa chỉ nào. Vui lòng thêm một địa chỉ mới!</p>
                                        <?php endif; ?>
                                        <button style="margin-top: 20px; background-color: var(--color); color: var(--color-main);" type="submit" class="btn form-control btn-login">Chọn</button>
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
        .address-box {
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px;
            display: flex;
            align-items: center;
        }

        .address-box h5 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .address-box p {
            font-size: 14px;
            margin: 3px 0;
        }

        .address-info {
            flex-grow: 1;
            padding-left: 10px;
        }

        .action-address {
            display: flex;
            gap: 10px;
        }


        .btn {
            padding: 6px 12px;
        }

        .btn-warning {
            background-color: #ffc107;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-primary {
            background-color: #007bff;
        }
    </style>
    <?php include "./includes/footer.php" ?>
</body>

</html>