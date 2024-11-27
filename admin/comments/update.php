<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();

$id = $_GET['id'];
$user = $dbHelper->select("SELECT * FROM users WHERE idUser = ?", [$id])[0];

// Function to check if email exists in the database for a different user
function ischeckmail($email, $id) {
    global $dbHelper;
    $result = $dbHelper->select("SELECT email FROM users WHERE email = ? AND idUser != ?", [$email, $id]);
    return count($result) > 0;
}

// Function to validate Vietnamese phone numbers
function isVietnamesePhoneNumber($number) {
    return preg_match('/^(03|05|07|08|09|01[2689])[0-9]{8}$/', $number) === 1;
}

// Function to check if username exists in the database for a different user
function ischeckUsername($username, $id) {
    global $dbHelper;
    $result = $dbHelper->select("SELECT username FROM users WHERE username = ? AND idUser != ?", [$username, $id]);
    return count($result) > 0;
}

// Function to check if phone number exists in the database for a different user
function ischeckPhone($phone, $id) {
    global $dbHelper;
    $result = $dbHelper->select("SELECT phone FROM users WHERE phone = ? AND idUser != ?", [$phone, $id]);
    return count($result) > 0;
}


$errors = [];
$email = "";
$username = "";
$password = "";
$name = "";
$phone = "";
$image = "";
$role = "";
$status = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['name']) || empty($_POST['name'])) {
        $errors['name'] = "Tên là bắt buộc";
    } else {
        $name = $_POST['name'];
    }

    if (!isset($_POST['username']) || empty($_POST['username'])) {
        $errors['username'] = "Tên đăng nhập là bắt buộc";
    } else {
        if (ischeckUsername($_POST["username"], $id)) {
            $errors['username'] = "Tên đăng nhập đã tồn tại";
        } else {
            $username = $_POST['username'];
        }
    }

    if (!isset($_POST['email']) || empty($_POST['email'])) {
        $errors['email'] = "Email là bắt buộc";
    } else {
        if (ischeckmail($_POST["email"], $id)) {
            $errors['email'] = "Email đã tồn tại";
        } else {
            $email = $_POST['email'];
        }
    }

    if (!isset($_POST['password']) || empty($_POST['password'])) {
        $errors['password'] = "Mật khẩu là bắt buộc";
    } else  if (strlen($_POST['password']) < 6) {
        $errors['password'] = "Mật khẩu phải có độ dài ít nhất 6 ký tự.";
    } else {
        $password = $_POST['password'];
    }

    if (!isset($_POST['phone']) || empty($_POST['phone'])) {
        $errors['phone'] = "Số điện thoại là bắt buộc";
    } else {
        if (!isVietnamesePhoneNumber($_POST['phone'])) {
            $errors['phone'] = "Số điện thoại không được định dạng chính xác";
        } else {
            if (ischeckPhone($_POST["phone"], $id)) {
                $errors['phone'] = "Số điện thoại đã tồn tại";
            } else {
                $phone = $_POST['phone'];
            }
        }
    }

    if (!isset($_POST['role']) || empty($_POST['role'])) {
        // $errors['role'] = "Vai trò là bắt buộc";
        $role = $user['role'];
    } else {
        $role = $_POST['role'];
    }

    if (!isset($_POST['status']) || empty($_POST['status'])) {
        $errors['status'] = "Trạng thái là bắt buộc";
    } else {
        $status = $_POST['status'];
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = __DIR__ . "/image/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $IMAGE_TYPES = array('jpg', 'jpeg', 'png');
        
        if (!in_array($imageFileType, $IMAGE_TYPES)) {
            $errors['image'] = "Image type must be JPG, JPEG, or PNG.";
        }

        if ($_FILES['image']["size"] > 1000000) {
            $errors['image'] = "Image file size is too large.";
        }

        // If no errors, proceed with file upload
        if (empty($errors)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = htmlspecialchars(basename($_FILES["image"]["name"]));
            } else {
                $errors['image'] = "Sorry, there was an error uploading your file.";
            }
        }
    }else{
        $image = $user['image'];
    }
            
    // If no errors, update data in      the database
    
    if (empty($errors)) {
        $updateData = [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'image' => $image,
            'role' => $role,
            'status' => $status,
        ];
            $isUpdate = $dbHelper->update("users", $updateData, "idUser = $id");   
        if ($isUpdate) {
            $_SESSION['success'] = true;
            header("Location: list.php");
            exit();
        } 
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
    <div id="wrapper">
        <?php include "../includes/sidebar.php" ?>
        <!-- Page Content -->
        <div id="content">
            <?php include "../includes/nav.php" ?>
            <!-- Main Content -->
            <div class="container-fluid">
                <!-- Place your content here -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">User</h3> 
                            </div>
                            <div class="card-body">
                                <div class="row mt-4 bg-light">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Cập nhật người dùng</h1>
                                        <span name="database" class="text-danger fs-5">
                                            <?php
                                    if(isset($errors['database'])) {
                                        echo $errors['database'];
                                    }
                                ?>
                                        </span>
                                        <form method="POST" action="" enctype="multipart/form-data">
                                            <input type="hidden" name="idUser"
                                                value="<?php echo htmlspecialchars($user['idUser']); ?>">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Tên</label>
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Tên người dùng"
                                                    value="<?php echo htmlspecialchars($user['name']); ?>">
                                                <?php
                                            if(isset($errors['name'])) {
                                                echo "<span class='text-danger'>$errors[name] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="username" class="form-label">Tên đăng nhập</label>
                                                <input type="text" name="username" class="form-control"
                                                    placeholder="username"
                                                    value="<?php echo htmlspecialchars($user['username']); ?>">
                                                <?php
                                            if(isset($errors['username'])) {
                                                echo "<span class='text-danger'>$errors[username] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">Mật khẩu</label>
                                                <input type="text" name="password" class="form-control"
                                                    placeholder="Mật khẩu (để trống nếu không đổi)"
                                                    value="<?php echo htmlspecialchars($user['password']); ?>">
                                                <?php
                                            if(isset($errors['password'])) {
                                                echo "<span class='text-danger'>$errors[password] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control"
                                                    placeholder="email"
                                                    value="<?php echo htmlspecialchars($user['email']); ?>">
                                                <?php
                                            if(isset($errors['email'])) {
                                                echo "<span class='text-danger'>$errors[email] </span>";
                                            }
                                        ?>
                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Số điện thoại</label>
                                                    <input type="text" name="phone" class="form-control"
                                                        placeholder="Số điện thoại"
                                                        value="<?php echo htmlspecialchars($user['phone']); ?>">
                                                    <?php
                                            if(isset($errors['phone'])) {
                                                echo "<span class='text-danger'>$errors[phone] </span>";
                                            }
                                        ?>
                                                </div>
                                                <div class="image mb-3">
                                                    <label for="image">Hình ảnh</label>
                                                    <input type="file" name="image" id="image" class="form-control">
                                                    <?php
                                            if(isset($errors['image'])) {
                                                echo "<span class='text-danger'>$errors[image] </span>";
                                            }
                                        ?>
                                                    <img class="mt-3" style="width: 100px; height: 100px;"
                                                        src="image/<?php echo htmlspecialchars($user['image']); ?>"
                                                        alt="">
                                                </div>
                                                <div class="role mb-3">
                                                    <label for="">Vai trò</label>
                                                    <select name="role" id="role" class="form-control">
                                                        <option value="">
                                                            -<?php echo htmlspecialchars($user['role']); ?>-</option>
                                                        <option value="admin">admin</option>
                                                        <option value="user">user</option>
                                                    </select>
                                                    <?php
                                            if(isset($errors['role'])) {
                                                echo "<span class='text-danger'>$errors[role] </span>";
                                            }
                                        ?>
                                                </div>
                                                <div class="status mb-3">
                                                    <label for="">Trạng thái hoạt động</label><br>
                                                    <input type="radio" name="status" id="" value="Đang hoạt động"
                                                        checked> Hoạt động<br>
                                                    <input type="radio" name="status" id="" value="Ngừng hoạt động">
                                                    Ngừng hoạt động <br>
                                                    <?php
                                            if(isset($errors['status'])) {
                                                echo "<span class='text-danger'>$errors[status] </span>";
                                            }
                                        ?>
                                                </div>
                                                <div class="d-flex justify-content-between mt-3">
                                                    <a href="list.php" class="return btn text-white btn-dark bg-gradient">
                                                        <i class="fa-solid fa-right-from-bracket deg-180"></i>
                                                        Quay lại
                                                    </a>
                                                    <button type="submit" class="btn btn-dark bg-gradient text-white">Cập
                                                        nhật</button>
                                                </div>
                                        </form>
                                    </div>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</body>

</html>