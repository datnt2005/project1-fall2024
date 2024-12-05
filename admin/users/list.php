<?php
    include "../../client/DBUntil.php";
    session_start();
    $dbHelper = new DBUntil();

    // Kiểm tra xem người dùng đã tìm kiếm chưa
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
    $listUser = [];

    // Thực hiện tìm kiếm nếu có từ khóa, nếu không thì lấy tất cả danh mục
    if (!empty($searchTerm)) {
        $listUser = $dbHelper->select("SELECT * FROM users WHERE name LIKE ?", array('%' . $searchTerm . '%'));
    } else {
        $listUser = $dbHelper->select("SELECT * FROM users");
    }            
// $login_success = false;
// if (isset($_SESSION['success']) && $_SESSION['success'] === true) {
//     $login_success = true;
//     unset($_SESSION['success']); // Unset the session variable to avoid repeated alerts
// }                  
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
    <?php
if (isset($_SESSION['success'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{$_SESSION['success']}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>";
    unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
}
?>
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
                                <h3 class="card-title">Người dùng</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mt-4">
                                    <div class="search-items">
                                        <form method="GET">
                                            <input class="input-search mb-3" type="search" name="search" id="search"
                                                placeholder="Tìm kiếm" style="height: 35px;">
                                            <button type="submit" class="btn btn-dark bg-gradient text-white"
                                                style="height: 35px">Search</button>
                                        </form>
                                    </div>
                                    <div class="add-category">
                                        <a href="./add.php" class="btn btn-primary px-4 mb-3 mx-5 py-2">Thêm người
                                            dùng</a>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên</th>
                                            <th>Tên đăng nhập</th>
                                            <th>Mật khẩu</th>
                                            <th>Email</th>
                                            <th>Sđt</th>
                                            <th>Vai trò</th>
                                            <th>Avatar</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listUser as $users) { ?>
                                        <tr>
                                            <td><?php echo $users['idUser']?></td>
                                            <td><?php echo $users['name']?></td>
                                            <td><?php echo $users['username']?></td>
                                            <td><?php echo $users['password']?></td>
                                            <td><?php echo $users['email']?></td>
                                            <td><?php echo $users['phone']?></td>
                                            <td><?php echo $users['role']?></td>
                                            <td><img style="width: 50px; height: 50px;"
                                                    src="image/<?php echo $users['image']?>"></img></td>
                                            <td>
                                                <button style="margin-right: 5px; border: none; width: 10px; height: 10px; border-radius: 100%; background-color: 
                                    <?php 
                                        if($users['status'] == "Đang hoạt động"){
                                            echo 'green';
                                        }
                                        else if($users['status'] == "Ngừng hoạt động"){
                                            echo 'red';
                                        }else{
                                            echo 'white';
                                        }
                                            ?>">
                                                </button>
                                                <?php echo $users['status'];?>
                                            </td>
                                            <td>
                                                <div class="action">
                                                    <a href="update.php?id=<?php echo $users['idUser']; ?>"
                                                        class="update_product text-decoration-none fw-bold mx-2"><i class="fs-5 fa-solid fa-pen-nib"></i></a>
                                                    <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="confirmDelete('<?php echo $users['idUser'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>

                                                </div>
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</body>
<script src="../js/script.js"></script>
</html>