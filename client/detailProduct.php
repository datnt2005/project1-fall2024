<?php
include "./DBUntil.php";
session_start();

// Ensure user is authenticated
$role = $_SESSION['role'] ?? null;
// Uncomment the next lines if you want to restrict access
// if (!isset($role) || $role !== 'admin') {
//     header("Location: ../../../client/login.php"); 
//     exit;
// }

$dbHelper = new DBUntil();

// Hardcode the user ID (for testing) or use $_SESSION['idUser'] if the user is logged in
$idUser = 1; // $_SESSION['idUser'] ?? 1;

// Function to format numbers as currency (VND)
function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';
}
$listProducts = $dbHelper->select("
    SELECT PR.*,
        SUM(PS.quantityProduct) AS total_quantity, 
        PS.price AS price,
        (SELECT PI.namePicProduct
         FROM picproduct PI
         WHERE PI.idProduct = PR.idProduct
         ORDER BY PI.idPicProduct
         LIMIT 1) AS namePicProduct
    FROM products PR
    INNER JOIN product_size PS ON PR.idProduct = PS.idProduct
    GROUP BY PR.idProduct
    LIMIT 8
");

// Fetch user data
$users = $dbHelper->select("SELECT * FROM users WHERE idUser = ?", array($idUser));

if (empty($users)) {
    echo "User not found.";
    exit;
}

$image = $users[0]['image'];
$name = $users[0]['name'];

// Get the product ID from the URL and check if it's set
$idProduct = isset($_GET['id']) ? $_GET['id'] : null;

if (!$idProduct) {
    echo "Product ID not found.";
    exit;
}

$_SESSION['idProduct'] = $idProduct;

// Optionally handle search term (though it's not used in the current query)
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";

// Fetch product data with related sizes
$listProducts = $dbHelper->select(
    "SELECT PR.*, prs.*, sz.*, 
    (SELECT namePicProduct FROM picproduct WHERE idProduct = PR.idProduct LIMIT 1) AS namePicProduct
    FROM products PR
    INNER JOIN product_size prs ON PR.idProduct = prs.idProduct
    INNER JOIN sizes sz ON sz.idSize = prs.idSize
    WHERE PR.idProduct = ?",
    array($idProduct)
);

if (empty($listProducts)) {
    echo "No products found for the given ID.";
    exit;
}

// Debugging output (optional)
var_dump($listProducts);

// Further processing or HTML output can go here

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hạt Ngon</title>
    <link rel="icon" type="image/png" href="./images/logo_du_an_1 2.png">
    <link rel="stylesheet" href="./css/style.css">
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/1d3d4a43fd.js"
        crossorigin="anonymous"></script>
</head>

<body>
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

    <main class="container my-4">

        <div class="row">
            <div class="col-md-6">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <?php
                        $firstItem = true; // To mark the first carousel item as active
                        foreach ($listProducts as $product) {
                            $productImage = '../admin/products/image/' . $product['namePicProduct']; // Assuming the product image is stored in the 'namePicProduct' field.
                        ?>
                            <div class="carousel-item <?php echo $firstItem ? 'active' : ''; ?>">
                                <img src="<?php echo $productImage; ?>" class="d-block w-100" alt="Sản phẩm">
                            </div>
                        <?php
                            $firstItem = false; // Set the first item as non-active after it's rendered
                        }
                        ?>

                        <!-- Add two additional images manually -->
                        <div class="carousel-item">
                            <img src="../admin/products/image/your_image_1.jpg" class="d-block w-100" alt="Additional Image 1">
                        </div>
        
                </div>


                <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Trở lại</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Tiếp theo</span>
                </button>
            </div>
        </div>

        <div class="col-md-6">
            <?php foreach ($listProducts as $product) { ?>
                <div class="product-item">
                    <h2><?php echo $product['nameProduct']; ?></h2>
                    <p class="text-danger" style="font-size: 24px;">
                        Giá: <?php echo formatCurrencyVND($product['price']); ?>
                    </p>
                    <div class="col-my-3">
                        <h4>Mô tả sản phẩm</h4>
                        <p><?php echo $product['description']; ?></p>
                    </div>
                </div>
            <?php } ?>

            <div class="col-md-3">
                <label for="size" class="form-label">Phân Loại (Size)</label>

                <select id="size" class="form-select" style="width: 120px;">
                    <?php foreach ($listProducts as $productsize) { ?>
                        <option value="<?php echo $productsize['nameSize']; ?>"><?php echo $productsize['nameSize']; ?></option>
                    <?php } ?>
                </select>


            </div>

            <div class="col-md-3">
                <label for="quantity" class="form-label">Số lượng</label>
                <div class="input-group" style="width: 120px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantity" class="form-control text-center" value="1" min="1" readonly style="width: 50px;">
                    <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">+</button>
                </div>
            </div>
            <br>
            <button class="btn btn-primary">Thêm vào giỏ hàng</button>
        </div>

        </div>
        <hr>
        <div class="my-4" style="width: 50%;">
            <h3>Thống Kê Đánh Giá</h3>
            <div class="rating-statistics">
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">5 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 70%;" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">70%</div>
                    </div>
                    <span class="ms-2">70%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">4 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%;" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">20%</div>
                    </div>
                    <span class="ms-2">20%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">3 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 5%;" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100">5%</div>
                    </div>
                    <span class="ms-2">5%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">2 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 3%;" aria-valuenow="3" aria-valuemin="0" aria-valuemax="100">3%</div>
                    </div>
                    <span class="ms-2">3%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">1 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-dark" role="progressbar" style="width: 2%;" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100">2%</div>
                    </div>
                    <span class="ms-2">2%</span>
                </div>
            </div>
        </div>
        <div class="my-4">
            <h3>Đánh Giá Sản Phẩm</h3>

            <!-- Phần đánh giá sao -->
            <div class="rating mb-3">
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span>(4/5)</span>
            </div>

            <!-- Khu vực nhập đánh giá -->
            <textarea class="form-control" rows="3" placeholder="Viết đánh giá của bạn..."></textarea>
            <button class="btn btn-success my-2" onclick="addReview()">Gửi Đánh Giá</button>

            <!-- Hiển thị các đánh giá đã gửi -->
            <div id="review-list" class="my-4">
                <h4>Nhận Xét Từ Khách Hàng</h4>

                <!-- Nhận xét mẫu -->
                <div class="card mb-3 border">
                    <div class="card-body">
                        <div class="rating mb-1">
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star text-warning"></span>
                        </div>
                        <p class="card-text">“Sản phẩm rất tốt, chất lượng tuyệt vời. Rất hài lòng với lần mua này!”</p>
                    </div>
                </div>

                <div class="card mb-3 border">
                    <div class="card-body">
                        <div class="rating mb-1">
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                        </div>
                        <p class="card-text">“Dịch vụ tốt, giao hàng nhanh, sản phẩm đúng mô tả. Rất đáng tiền!”</p>
                    </div>
                </div>

                <div class="card mb-3 border">
                    <div class="card-body">
                        <div class="rating mb-1">
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star checked text-warning"></span>
                            <span class="fa fa-star text-warning"></span>
                            <span class="fa fa-star text-warning"></span>
                        </div>
                        <p class="card-text">“Sản phẩm khá ổn, nhưng giao hàng hơi chậm. Hy vọng lần tới sẽ nhanh hơn.”</p>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="my-4">
            <h3>Sản Phẩm Liên Quan</h3>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="card-img-top" alt="Sản phẩm liên quan">
                        <div class="card-body">
                            <h5 class="card-title">Sản Phẩm 1</h5>
                            <p class="card-text">Giá: 80,000 VNĐ</p>
                            <a href="#" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="card-img-top" alt="Sản phẩm liên quan">
                        <div class="card-body">
                            <h5 class="card-title">Sản Phẩm 2</h5>
                            <p class="card-text">Giá: 90,000 VNĐ</p>
                            <a href="#" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="card-img-top" alt="Sản phẩm liên quan">
                        <div class="card-body">
                            <h5 class="card-title">Sản Phẩm 3</h5>
                            <p class="card-text">Giá: 120,000 VNĐ</p>
                            <a href="#" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </main>

    <footer id="footer" class="pt-5 mt-3 ">
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
                        class="logo-footer d-block mx-auto  ">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 logo">
                    <img src="./images/logo_du_an_1 2.png" alt="logo" class="mt-2" width="150px">
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
<script>
    function increaseQuantity() {
        const quantityInput = document.getElementById("quantity");
        let quantity = parseInt(quantityInput.value);
        quantityInput.value = quantity + 1;
    }

    function decreaseQuantity() {
        const quantityInput = document.getElementById("quantity");
        let quantity = parseInt(quantityInput.value);
        if (quantity > 1) {
            quantityInput.value = quantity - 1;
        }
    }
</script>
<script>
    function addReview() {
        const reviewList = document.getElementById('review-list');
        const reviewText = document.querySelector('textarea').value;

        const starRating = 4;

        if (reviewText) {
            const reviewItem = document.createElement('div');
            reviewItem.classList.add('card', 'mb-3', 'border');

            let starHTML = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= starRating) {
                    starHTML += '<span class="fa fa-star checked text-warning"></span>';
                } else {
                    starHTML += '<span class="fa fa-star"></span>';
                }
            }

            reviewItem.innerHTML = `
                <div class="card-body">
                    <div class="rating mb-1">${starHTML}</div>
                    <p class="card-text">${reviewText}</p>
                </div>
            `;

            reviewList.appendChild(reviewItem);
            document.querySelector('textarea').value = '';
        }
    }
</script>

<style>
    .rating .fa {
        font-size: 1.2em;
    }

    .checked {
        color: gold;
    }
</style>
</body>

</html>