<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hạt Ngon</title>
    <link rel="icon" type="image/png" href="./images/logo_du_an_1 2.png">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/1d3d4a43fd.js" crossorigin="anonymous"></script>
</head>

<body>
    <header class="header ">
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
                            <a class="nav-link" aria-current="page" href="#">Sản Phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="#">Chúng tôi là ai?</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " aria-current="page" href="#">Liên Hệ</a>
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
                            <a href="login.php" class>
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-user-round">
                                    <circle cx="12" cy="8" r="5" />
                                    <path d="M20 21a8 8 0 0 0-16 0" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến
                với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="d-flex header-outstanding align-items-center">
                <p class=" m-1 fs-5 fw-bold">Trang chủ/ thông tin đơn hàng</p>
            </div>
        </div>

        <div class="container text-center my-5">
            <div class="card shadow-lg p-4 rounded-4">
                <div class="card-body">
                    <i class="fa-solid fa-face-laugh-wink" style="font-size: 100px; color: #69BA31;"></i>
                    <h2 class="card-title  mb-4" style="color: #69BA31;">Cảm ơn bạn đã đặt hàng!</h2>
                    <p class="lead">Đơn hàng của bạn đã được xác nhận và chúng tôi đang xử lý. Bạn sẽ sớm nhận được thông tin chi tiết qua email.</p>
                    
                    <!-- Chi tiết đơn hàng -->
                    <div class="mt-4">
                        <h5 class="mb-3">Chi tiết đơn hàng</h5>
                        <table class="table table-borderless">
                            <tr>
                                <th>Mã Đơn Hàng:</th>
                                <td>DH001</td>
                            </tr>
                            <tr>
                                <th>Ngày Đặt Hàng:</th>
                                <td>01/11/2024</td>
                            </tr>
                            <tr>
                                <th>Tổng Tiền:</th>
                                <td>200,000 VND</td>
                            </tr>
                        </table>
                    </div>
        
                    <!-- Nút trở về trang chủ -->
                    <a href="index.html" class="btn mt-4" style="background-color: #69BA31; color: white;">Quay về trang chủ</a>
                </div>
            </div>
        </div>


    </main>

    <footer id="footer" class="pt-5 mt-5">
        <div class="footer container">
            <div class="row">
                <div class="col-md-3">
                    <h4 class="">Thông Tin</h4>
                    <ul class=" list-unstyled">
                        <li><a href="#">Vua Hạt</a></li>
                        <li><a href="#">Thông Tin Liên Hệ</a></li>
                        <li><a href="#">Cam Kết Của Shop</a></li>
                        <li><a href="#">Chính Sách Bảo Vệ</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4 class="">Tài Khoản Của Bạn</h4>
                    <ul class=" list-unstyled">
                        <li><a href="#">Tài Khoản</a></li>
                        <li><a href="#">Ưu Thích</a></li>
                        <li><a href="#">Giỏ Hàng</a></li>
                        <li><a href="#">Thanh Toán</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4 class="">Hướng Dẫn</h4>
                    <ul class=" list-unstyled">
                        <li><a href="#">Chính Sách Vận Chuyển</a></li>
                        <li><a href="#">Hướng Dẫn Mua Hàng</a></li>
                        <li><a href="#">Chính Sách Đổi Trả</a></li>
                        <li><a href="#">Hướng Dẫn Thanh Toán</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h4 class="">Liên Hệ VUA HẠT</h4>
                    <ul class=" list-unstyled">
                        <li>Hotline : 09xxxxxxx</li>
                        <li>Cửa Hàng : Phan Chu Trinh , TP Buôn
                            Mê Thuột , Đăk Lăk</li>
                        <li>Email : vuahat@gmaiil.com</a></li>
                        <li>Mở cửa: 8h-17h ( Thứ 2 - Chủ nhật )</li>
                    </ul>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <img src="./images/logo-da-thong-bao-bo-cong-thuong-mau-xanh 1.png" alt="logo"
                        class="logo-footer d-block mx-auto ">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 logo">
                    <img src="./images/logo_du_an_1 2.png" alt="logo" class="mt-2 " width="150px">
                </div>
                <div class="col-md-4 text-center mt-3">
                    <p class="mt-3">Công Ty TNHH Sản Xuất Thương Mại Vua Hạt</p>
                    <p class="mt-3">Mã số doanh nghiệp: 000001 - Cấp ngày: 30/10/2024</p>
                </div>
                <div class="col-md-4 text-center mt-3">
                    <div class="internet mt-3">
                        <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#"><i class="fa-brands fa-youtube"></i></a>
                        <a href="#"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="./js/script.js"></script>
</body>

</html>