<?php
session_start();
include_once("./DBUntil.php");
$dbHelper = new DBUntil();
$user_id = $_GET['id'];
$query = $dbHelper->select("SELECT * FROM users WHERE idUser = ?", [$user_id]);
$user = $query[0];
$errors = [];
function ischeckmail($email, $user_id) {
    global $dbHelper;
    $result = $dbHelper->select("SELECT email FROM users WHERE email = ? AND idUser != ?", [$email, $user_id]);
    return count($result) > 0;
}

// Function to validate Vietnamese phone numbers
function isVietnamesePhoneNumber($number) {
    return preg_match('/^(03|05|07|08|09|01[2689])[0-9]{8}$/', $number) === 1;
}

// Function to check if username exists in the database for a different user
function ischeckUsername($username, $user_id) {
    global $dbHelper;
    $result = $dbHelper->select("SELECT username FROM users WHERE username = ? AND idUser != ?", [$username, $user_id]);
    return count($result) > 0;
}

// Function to check if phone number exists in the database for a different user
function ischeckPhone($phone, $user_id) {
    global $dbHelper;
    $result = $dbHelper->select("SELECT phone FROM users WHERE phone = ? AND idUser != ?", [$phone, $user_id]);
    return count($result) > 0;
} 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $image = $user['image'];
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = "Tên là bắt buộc";
    } else {
        $name = $_POST['name'];
    }
    if (!isset($_POST['username']) || empty($_POST['username'])) {
        $errors['username'] = "Tên đăng nhập là bắt buộc";
    } else {
        if (ischeckUsername($_POST["username"], $user_id)) {
            $errors['username'] = "Tên đăng nhập đã tồn tại";
        } else {
            $username = $_POST['username'];
        }
    }

    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = "Email là bắt buộc";
    } else {
        if (ischeckmail($_POST["email"], $user_id)) {
            $errors['email'] = "Email đã tồn tại";
        } else {
            $email = $_POST['email'];
        }
    }
    if (!isset($_POST['phone']) || empty($_POST['phone'])) {
        $errors['phone'] = "Số điện thoại là bắt buộc";
    } else {
        if (!isVietnamesePhoneNumber($_POST['phone'])) {
            $errors['phone'] = "Số điện thoại không được định dạng chính xác";
        } else {
            if (ischeckPhone($_POST["phone"], $user_id)) {
                $errors['phone'] = "Số điện thoại đã tồn tại";
            } else {
                $phone = $_POST['phone'];
            }
        }
    }
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "C:\\xampp\htdocs\Project1-fall2024\admin\users\image\\";
        $target_file = $target_dir . basename($_FILES["profile_image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $IMAGE_TYPES = ['jpg', 'jpeg', 'png'];
// var_dump($target_file);
        if (!in_array($imageFileType, $IMAGE_TYPES)) {
            $errors['image'] = "Image type must be JPG, JPEG, or PNG.";
        }

        if ($_FILES['profile_image']["size"] > 1000000) {
            $errors['image'] = "Image file size is too large.";
        }
        

        // If no errors, proceed with file upload
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file)) {
                $image = htmlspecialchars(basename($_FILES["profile_image"]["name"]));
            } else {
                $errors['image'] = "Sorry, there was an error uploading your file.";
            }
        }
    }
    if(empty($errors)){
        $data = [
        'name' => $name,
        'username' => $username,
        'email' => $email,
        'phone' => $phone,
        'image' => $image
    ];
        $condition = "idUser = :idUser";
        $params = ['idUser' => $user_id];

        $update_query = $dbHelper->update('users', $data, $condition, $params);
        if ($update_query) {
            $_SESSION['success'] = true;
            header('Location: detail_user.php?id=' . $user_id);
            exit();
        }
    }
    
}
$login_success = false;

if (isset($_SESSION['success']) && $_SESSION['success'] === true) {
    $login_success = true;
    unset($_SESSION['success']); // Unset the session variable to avoid repeated alerts
}
?>
<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<body>

    <?php include "./includes/header.php" ?>

    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến
                với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="container align-items-center">
                <div class="d-flex pt-2">
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ thông tin người dùng</p>
                </div>
            </div>
        </div>
        <div class="container form">
            <div class="row">
                <div class="col-md-3 mt-1">
                    <div class="aside-account mt-4">
                        <h2 class="fw-bold fs-3">TÀI KHOẢN</h2>
                        <ul class="mx-3 aside-list">
                            <li class="nav-link">
                                <a href="#" class="text-decoration-none aside-account-list focus-in">Thông tin tài khoản</a>
                            </li>
                            <li class="nav-link">
                                <a href="#" class="text-decoration-none aside-account-list">Địa chỉ</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 mt-5">
                    <section id="forgotPassword">
                        <div class="container">
                            <div class="row">
                                <div class="d-flex justify-content-center forgot-pass mb-4">
                                    <div class="forgot-main p-4">
                                            <form action="" method="POST" class="" enctype="multipart/form-data">
                                                <h2 class="text-center fw-bold mb-4">Thông tin người dùng</h2>
                                                <div class="name-value d-flex flex-column align-items-center">
                                                    <img src="../admin/users/image/<?php echo htmlspecialchars($user['image']); ?>"  alt="Profile Image" style="border-radius:50%; width: 150px; height: 150px;">
                                                    <input type="file" name="profile_image" id="profile_image" accept="./image/" style="margin-left: 90px;">
                                                </div>
                                                <div>
                                                    <label for="" class="text-small">Họ và tên:</label>
                                                    <input type="text" name="name" id="name" class="value-forgot d-block w-100" value="<?php echo htmlspecialchars($user['name']); ?>">
                                                </div>
                                                <div class="mt-3">
                                                    <label for="" class="text-small">Tên đăng nhập: </label>
                                                    <input type="text" name="username" id="username" class="value-forgot d-block w-100" value="<?php echo htmlspecialchars($user['username']); ?>">
                                                </div>
                                                <div class="mt-3">
                                                    <label for="" class="text-small">Email: </label>
                                                    <input type="email" name="email" id="email" class="value-forgot d-block w-100" value="<?php echo htmlspecialchars($user['email']); ?>">
                                                </div>
                                                <div class="mt-3">
                                                    <label for="" class="text-small">Số điện thoại: </label>
                                                    <input type="text" name="phone" id="phone" class="value-forgot d-block w-100" value="<?php echo htmlspecialchars($user['phone']); ?>">
                                                </div>
                                                <a href="resetPassword.php?id=<?php echo $user_id ?>" class="change-password  text-decoration-none text-warning mt-4 d-block">Đặt lại mật khẩu ? </a>
                                                <button style="margin-top: 20px; background-color: var(--color); color: var(--color-main);" type="submit" class="btn form-control btn-login">Cập nhật</button>
                                            </form>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </div>
        <?php include "./includes/footer.php"?>
    </main>
    <style src="./css/style.css"></style>
    
    <script src="./js/script.js"></script>
</body>

</html>