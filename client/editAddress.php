<?php
session_start();
include './DBUntil.php';
require './connnect.php';

$user_id = $_SESSION['idUser'] ?? null;

if (!$user_id) {
    echo "<script>alert('Bạn cần đăng nhập trước!'); window.location.href = 'login.php';</script>";
    exit();
}

// Lấy thông tin địa chỉ từ cơ sở dữ liệu
$detail_id = $_GET['detail_id'] ?? null;

if (!$detail_id) {
    echo "<script>alert('Địa chỉ không tồn tại!'); window.location.href = 'address.php';</script>";
    exit();
}

$sql = "SELECT * FROM detail_address WHERE detail_id = '$detail_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $sql);
$address = mysqli_fetch_assoc($result);

if (!$address) {
    echo "<script>alert('Địa chỉ không tồn tại hoặc không thuộc về bạn!'); window.location.href = 'address.php';</script>";
    exit();
}

// Xử lý form cập nhật địa chỉ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $village = $_POST['village'];
    $district_id = $_POST['district'];
    $province_id = $_POST['province'];
    $ward_id = $_POST['ward'];
    $is_default = isset($_POST['is_default']) ? 1 : 0;

    if ($name && $email && $phone && $village && $district_id && $province_id && $ward_id) {
        if ($is_default) {
            $unset_default_sql = "UPDATE detail_address SET is_default = 0 WHERE user_id = '$user_id'";
            mysqli_query($conn, $unset_default_sql);
        }
        // Cập nhật địa chỉ
        $update_sql = "UPDATE detail_address 
                       SET name = '$name', email = '$email', phone = '$phone', village = '$village', district_id = '$district_id', 
                           province_id = '$province_id', ward_id = '$ward_id', is_default = '$is_default'
                       WHERE detail_id = '$detail_id' AND user_id = '$user_id'";

        if (mysqli_query($conn, $update_sql)) {
            echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Cập nhật địa chỉ thành công!',
                    text: 'Địa chỉ đã được cập nhật.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#69BA31'
                }).then(() => {
                    window.location.href = 'listAddress.php';
                });
            });
        </script>
        ";
        exit;
        } else {
            $error = "Đã xảy ra lỗi khi cập nhật địa chỉ!";
        }
    } else {
        $error = "Vui lòng điền đầy đủ thông tin!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "./includes/head.php"; ?>

<body>
    <?php include "./includes/header.php"; ?>
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
                                    <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($address['name']); ?>" required>
                                </div>
                                <div class="mt-3">
                                    <label for="phone">Số điện thoại</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($address['phone']); ?>" required>
                                </div>
                                <div class="mt-3">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($address['email']); ?>" required>
                                </div>
                                <div class="mt-3">
                                    <label for="village">Địa chỉ cụ thể</label>
                                    <input type="text" class="form-control" id="village" name="village" value="<?= htmlspecialchars($address['village']); ?>" required>
                                </div>
                                <div class="mt-3">
                                    <label for="province">Tỉnh/Thành phố</label>
                                    <select class="form-control" id="province" name="province" required>
                                        <?php
                                        $province_sql = "SELECT * FROM province";
                                        $province_result = mysqli_query($conn, $province_sql);
                                        while ($province = mysqli_fetch_assoc($province_result)) {
                                            $selected = $province['province_id'] == $address['province_id'] ? 'selected' : '';
                                            echo "<option value='{$province['province_id']}' $selected>{$province['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="district">Quận/Huyện</label>
                                    <select class="form-control" id="district" name="district" required>
                                        <?php
                                        $district_sql = "SELECT * FROM district WHERE province_id = '{$address['province_id']}'";
                                        $district_result = mysqli_query($conn, $district_sql);
                                        while ($district = mysqli_fetch_assoc($district_result)) {
                                            $selected = $district['district_id'] == $address['district_id'] ? 'selected' : '';
                                            echo "<option value='{$district['district_id']}' $selected>{$district['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mt-3">
                                    <label for="wards">Phường/Xã</label>
                                    <select class="form-control" id="ward" name="ward" required>
                                        <?php
                                        $ward_sql = "SELECT * FROM wards WHERE district_id = '{$address['district_id']}'";
                                        $ward_result = mysqli_query($conn, $ward_sql);
                                        while ($ward = mysqli_fetch_assoc($ward_result)) {
                                            $selected = $ward['wards_id'] == $address['ward_id'] ? 'selected' : '';
                                            echo "<option value='{$ward['wards_id']}' $selected>{$ward['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mt-3 form-check">
                                    <input type="checkbox" id="is_default" name="is_default" <?= $address['is_default'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="is_default">
                                        Đặt làm địa chỉ mặc định
                                    </label>
                                </div>
                                <?php if (isset($error)): ?>
                                    <p class="text-danger"><?= $error; ?></p>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include "./includes/footer.php"; ?>
</body>

</html>