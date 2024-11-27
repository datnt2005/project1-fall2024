<?php
    include "../../client/DBUntil.php";
    session_start();
    $dbHelper = new DBUntil();
    // $users = $dbHelper->select("SELECT * FROM users");
    function isVietnamesePhoneNumber($number){
        return preg_match('/^(03|05|07|08|09|01[2689])[0-9]{8}$/', $number) === 1;
    }
    function ischeckmail($email){
        $dbHelper = new DBUntil();
        $emailExists = $dbHelper->select("SELECT email FROM users WHERE email = ?", [$email]);
        return count($emailExists) > 0;
    }
    function ischeckUsername($username){
        $dbHelper = new DBUntil();
        $UsernameExists = $dbHelper->select("SELECT username FROM users WHERE username = ?", [$username]);
        return count($UsernameExists) > 0;
    }
    function ischeckPhone($phone){
        $dbHelper = new DBUntil();
        $PhoneExists = $dbHelper->select("SELECT phone FROM users WHERE phone = ?", [$phone]);
        return count($PhoneExists) > 0;
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
        }else {
            if (ischeckUsername($_POST["username"])) {
                $errors['username'] = "Tên đăng nhập đã tồn tại";
            } else {
                $username = $_POST['username'];
            }
        }
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $errors['email'] = "Email là bắt buộc";
        } else {
            if (ischeckmail($_POST["email"])) {
                $errors['email'] = "Email đã tồn tại";
            } else {
                $email = $_POST['email'];
            }
        }
        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $errors['password'] = "Mật khẩu là bắt buộc";
        } elseif (strlen($_POST['password']) < 6) {
            $errors['password'] = "Mật khẩu phải có độ dài ít nhất 6 ký tự.";
        } else {
            $password = $_POST['password'];
        }
        if (!isset($_POST['phone']) || empty($_POST['phone'])) {
            $errors['phone'] = "Số điện thoại là bắt buộc";
        } else {
            if (!isVietnamesePhoneNumber($_POST['phone'])) {
                $errors['phone'] = "Số điện thoại không được định dạng chính xác";
            }else {
                if (ischeckPhone($_POST["phone"])) {
                    $errors['phone'] = "Số điện thoại đã tồn tại"; 
                }else {
                $phone = $_POST['phone'];
                }
            }
        }
        if (!isset($_POST['role']) || empty($_POST['role'])) {
            $errors['role'] = "Vai trò là bắt buộc";
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
        } 
       
        // If no errors, insert data into the database
    if (empty($errors)) {
        $data = [
            'name' => $name,
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'image' => $image,
            'role' => $role,
            'status' => $status,
        ];
        
        $isCreate = $dbHelper->insert('users', $data);

        if ($isCreate) {
            // Redirect to the same page to see the new record in the table
            $_SESSION['success'] = "Thêm người dùng thành công";
            header("Location: list.php");
            exit();
        } else {
            $errors['database'] = "Failed to create new user";
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
                                <h3 class="card-title">Users</h3>

                            </div>
                            <div class="card-body p-4 bg-light">
                                <div class="row ">
                                    <div class="col-md-3"></div>
                                    <div class="col-md-6">
                                        <h1 class="mt-4">Thêm người dùng</h1>
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <div class="name mb-3">
                                                <label for="">Tên</label>
                                                <input type="text" name="name" id="name" class="form-control">
                                                <?php
                                            if(isset($errors['name'])) {
                                                echo "<span class='text-danger'>$errors[name] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="username mb-3">
                                                <label for="">Tên đăng nhập</label>
                                                <input type="text" name="username" id="username" class="form-control">
                                                <?php
                                            if(isset($errors['username'])) {
                                                echo "<span class='text-danger'>$errors[username] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="email mb-3">
                                                <label for="">Email</label>
                                                <input type="email" name="email" id="email" class="form-control">
                                                <?php
                                            if(isset($errors['email'])) {
                                                echo "<span class='text-danger'>$errors[email] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="password mb-3">
                                                <label for="">Mật khẩu</label>
                                                <input type="password" name="password" id="password"
                                                    class="form-control">
                                                <?php
                                            if(isset($errors['password'])) {
                                                echo "<span class='text-danger'>$errors[password] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="phone mb-3">
                                                <label for="">Số điện thoại</label>
                                                <input type="text" name="phone" id="phone" class="form-control">
                                                <?php
                                            if(isset($errors['phone'])) {
                                                echo "<span class='text-danger'>$errors[phone] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="image mb-3">
                                                <label for="">Hình ảnh</label>
                                                <input type="file" id="image" name="image" class="form-control"
                                                    multiple>
                                                <?php
                                            if(isset($errors['image'])) {
                                                echo "<span class='text-danger'>$errors[image] </span>";
                                            }
                                        ?>
                                            </div>
                                            <div class="role mb-3">
                                                <label for="">Vai trò</label>
                                                <select name="role" id="role" class="form-control">
                                                    <option value="">--Thêm vai trò--</option>
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
                                                <input type="radio" name="status" id="" value="Đang hoạt động"> Hoạt
                                                động<br>
                                                <input type="radio" name="status" id="" value="Ngừng hoạt động"> Ngừng
                                                hoạt động <br>
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

                                                <button type="submit" class="btn btn-dark bg-gradient text-white">Thêm
                                                    người
                                                    dùng</button>
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