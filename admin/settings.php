<?php
session_start();
include_once('../client/DBUntil.php');
$dbHelper = new DBUntil();
// $role = $_SESSION['role'] ?? null;
//     //phân quyền trang web
//     if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//         header("Location: ../client/login.php"); // Chuyển hướng đến trang đăng nhập
//         exit;
//     }
?>
<!DOCTYPE html>
<html lang="en">
<?php include "./includes/head.php" ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="./css/style.css">

<body>
    <div id="wrapper">
        <div id="sidebar-wrapper" class="bg-dark px-3">
            <ul class="sidebar-nav mt-3 mb-5">
                <li class="sidebar-brand d-flex align-items-center px-5">
                    <div class="logo_sidebar">
                        <a href="index.php">
                            <img src="../client/images/logo_du_an_1 2.png" width="80" alt="logo">
                        </a>
                    </div>
                </li>
                <li class="sidebar-nav-item mt-4">
                    <a href="index.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./categories/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th-list me-2"></i> Categories
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./sub_categories/list.php"
                        class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th me-2"></i> Category Products
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./products/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-boxes me-2"></i> Products
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./orders/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-shopping-cart me-2"></i> Orders
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./users/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-users me-2"></i> Users
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./comments/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-comments me-2"></i> Comments
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./coupons/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tags me-2"></i> Coupons
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="settings.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-cogs me-2"></i> Settings
                    </a>
                </li>
            </ul>
        </div>
        <!-- Page Content -->
        <div id="content">
            <?php
                // include_once "../client/DBUntil.php";
                $dbHelper = new DBUntil();
                // $idUser = $_SESSION['idUser'];
                $idUser = 1;
                $users = $dbHelper->select("SELECT * FROM users WHERE idUser = $idUser") ?? null;
                $image = $users[0]['image'] ?? null;
                $name = $users[0]['name'] ?? null;
            ?>
            <nav class="navbar">
                <div class="search-nav mx-5">
                    <form action>
                        <input type="search" name="search" id="search" placeholder="Search...">
                        <button>
                            <i class="fa-solid fa-search"></i>
                        </button>
                    </form>
                </div>
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - User Information -->
                    <li class="nav-item  no-arrow d-flex align-items-center mr-3">
                        <a class="nav-link" href="#" id="user" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small "><?php echo $name ?></span>
                            <i class="fa-regular fa-envelope text-gray-400 fs-5 mr-2 mx-2"></i>
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="logout">
                            <a class="nav-link" href="../client/index.php" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw fs-5 mr-2 text-gray-400"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>            <!-- Main Content -->
            <div class="container-fluid">
                <!-- Place your content here -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Settings</h3>
                            </div>
                            <div class="card-body">
                                <div class="container mt-5">
                                    <h2 class="text-center">Account Settings</h2>
                                    <div class="row mt-4">
                                        <!-- Sidebar -->
                                        <div class="col-md-3">
                                            <ul class="list-group">
                                                <li class="list-group-item active">Profile</li>
                                                <li class="list-group-item">Change Password</li>
                                                <li class="list-group-item">Notification Settings</li>
                                                <li class="list-group-item">Privacy Settings</li>
                                            </ul>
                                        </div>

                                        <!-- Main Content -->
                                        <div class="col-md-9">
                                            <!-- Profile Section -->
                                            <div id="profile-settings">
                                                <h4>Profile Information</h4>
                                                <form>
                                                    <div class="mb-3">
                                                        <label for="username" class="form-label">Username</label>
                                                        <input type="text" class="form-control" id="username"
                                                            placeholder="Enter your username">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email</label>
                                                        <input type="email" class="form-control" id="email"
                                                            placeholder="Enter your email">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="phone" class="form-label">Phone Number</label>
                                                        <input type="text" class="form-control" id="phone"
                                                            placeholder="Enter your phone number">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
                                            </div>

                                            <!-- Change Password Section -->
                                            <div id="change-password" class="mt-5">
                                                <h4>Change Password</h4>
                                                <form>
                                                    <div class="mb-3">
                                                        <label for="current-password" class="form-label">Current
                                                            Password</label>
                                                        <input type="password" class="form-control"
                                                            id="current-password" placeholder="Enter current password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="new-password" class="form-label">New
                                                            Password</label>
                                                        <input type="password" class="form-control" id="new-password"
                                                            placeholder="Enter new password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="confirm-password" class="form-label">Confirm New
                                                            Password</label>
                                                        <input type="password" class="form-control"
                                                            id="confirm-password" placeholder="Confirm new password">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Change
                                                        Password</button>
                                                </form>
                                            </div>

                                            <!-- Notification Settings Section -->
                                            <div id="notification-settings" class="mt-5">
                                                <h4>Notification Settings</h4>
                                                <form>
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="email-notifications">
                                                        <label class="form-check-label" for="email-notifications">Email
                                                            Notifications</label>
                                                    </div>
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="sms-notifications">
                                                        <label class="form-check-label" for="sms-notifications">SMS
                                                            Notifications</label>
                                                    </div>
                                                    <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="push-notifications">
                                                        <label class="form-check-label" for="push-notifications">Push
                                                            Notifications</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
                                            </div>

                                            <!-- Privacy Settings Section -->
                                            <div id="privacy-settings" class="mt-5">
                                                <h4>Privacy Settings</h4>
                                                <form>
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="public-profile">
                                                        <label class="form-check-label" for="public-profile">Make
                                                            Profile Public</label>
                                                    </div>
                                                    <div class="form-check mb-3">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="search-engine">
                                                        <label class="form-check-label" for="search-engine">Allow search
                                                            engines to index my profile</label>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </form>
                                            </div>
                                        </div>
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