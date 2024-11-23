<?php
include_once("./DBUntil.php");
$dbHelper = new DBUntil();
// session_start();
?>
<header class="header">
    <nav class="container navbar navbar-expand-lg ">
        <div class="container-fluid" width="1890">
            <a class="navbar-brand text-white" href="index.php">
                <img src="./images/logo_du_an_1 2.png" class="logo mx-3" alt="image">
            </a>
            <button class="navbar-toggler " type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon bg-white"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center w-300  " id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="shop.php">Sản Phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="contact.php">Chúng tôi là ai?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link " aria-current="page" href="">Liên Hệ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="#">Hỏi đáp và phản hồi</a>
                    </li>
                </ul>
            </div>
            <div class="header-search">
                <form action="../client/shop.php" method="GET">
                    <input type="search" name="search" id="search" placeholder="Bạn tìm sản phẩm gì...">
                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="header-cart mt-3">
                <ul class="d-flex ">
                    <li class="nav-link mx-2">
                        <a class href="cart.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-shopping-bag">
                                <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                                <path d="M3 6h18" />
                                <path d="M16 10a4 4 0 0 1-8 0" />
                            </svg>

                        </a>
                    </li>
                    <li class="nav-link mx-2">
                        <?php
                        if (!isset($_SESSION['idUser'])) {
                            echo '
                                <a href="login.php" class="text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-user-round">
                                    <circle cx="12" cy="8" r="5" />
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                </svg>
                            </a>
                            ';
                        } else {
                            $idUser = $_SESSION['idUser'];
                            $users = $dbHelper->select("SELECT * FROM users WHERE idUser = $idUser");
                            $image = $users[0]['image'];
                            $role = $users[0]['role'];
                            $name = $users[0]['name'];
                            $textRole = '';
                            if ($role == 'admin') {
                                $textRole = '
                                <li><a class="dropdown-item" href="../admin/index.php?id=' . $idUser . '">
                                      <i class="fa-solid fa-pencil mx-1"></i>
                                        Quản Trị
                                    </a></li>
                                ';
                            } else {
                                $textRole = '';
                            }
                            echo '
                                <div class="dropdown ">
                                    <button class="btn-dropdown p-0 border-0 " style="background-color: transparent;" onclick="showDropdown()">
                                        <img style="width: 35px; height: 35px; border-radius: 50%;" src="../admin/users/image/' . $image . '" alt>
                                    </button>
                                    <ul class="dropdown-menu mt-1">
                                        <li>
                                            <a class="dropdown-item" href="./detail_user.php?id=' . $idUser . '">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-user-round">
                                                    <circle cx="12" cy="8" r="5" />
                                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                                </svg>
                                                Thông tin tài khoản
                                            </a>
                                        </li>
                                        ' . $textRole . '
                                        <div id="order"></div>
                                        <li>
                                            <a class="dropdown-item" href="./order.php">
                                                <i class="fa-solid fa-clipboard-list mx-1"></i>
                                                Đơn mua
                                            </a>
                                        </li>
                                        <div id="logout"></div>
                                        <li>
                                            <a class="dropdown-item" href="logout.php" onclick="alertRemove(event, \'' . $name . '\')">
                                                <i class="fa-solid fa-right-from-bracket mx-1"></i>
                                                Đăng xuất
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            ';
                        } ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <style>
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 10%;
            background-color: white;

        }

        /* Hiển thị menu khi có class 'add' */
        .dropdown-menu.add {
            display: block;
        }

        .position_alert {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .bg-alert {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            max-width: 500px;
            width: 90%;
        }

        .content_alert h3 {
            font-size: 1.2rem;
            color: #333;
        }

        .icon-warning {
            color: #f44336;
            font-size: 3rem;
            margin: 10px 0;
        }

        .btn-option .btn {
            width: 100px;
            font-weight: bold;
            padding: 8px 0;
            margin: 0 90px; /* Tạo khoảng cách giữa hai nút */
        }

        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: #fff;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>

</header>