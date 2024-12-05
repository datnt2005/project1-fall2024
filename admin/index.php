<?php
include "../client/DBUntil.php";
session_start();
$idUser = $_SESSION['idUser'] ?? null;
$role = $_SESSION['role'] ?? null;
    //phân quyền trang web
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../client/login.php"); // Chuyển hướng đến trang đăng nhập
        exit;
    }

$dbHelper = new DBUntil();
function formatCurrencyVND($number) {
    return number_format($number, 0, ',', '.') . 'đ';
}

// Truy vấn doanh thu tổng hợp
$totalQuery = "
    SELECT DATE_FORMAT(dateOrder, '%Y-%m') AS month, SUM(totalPrice) AS revenue 
    FROM orders 
    GROUP BY month 
    ORDER BY month;
";
$totalRevenueData = $dbHelper->select($totalQuery);
// Truy vấn doanh thu hàng tháng và theo ngày
$monthlyRevenueQuery = "
    SELECT DATE_FORMAT(dateOrder, '%Y-%m') AS month, SUM(totalPrice) AS revenue 
    FROM orders 
    WHERE statusOrder = 5
    GROUP BY month 
    ORDER BY month;
";
$monthlyRevenueData = $dbHelper->select($monthlyRevenueQuery);

$dailySalesQuery = "
    SELECT DATE(dateOrder) AS order_date, SUM(totalPrice) AS daily_revenue 
    FROM orders 
    WHERE statusOrder = 5
    GROUP BY order_date 
    ORDER BY order_date;
";
$dailySalesData = $dbHelper->select($dailySalesQuery);

$months = array_column($monthlyRevenueData, 'month');
$monthlyRevenues = array_column($monthlyRevenueData, 'revenue');
$totalRevenues = array_column($totalRevenueData, 'revenue');
$orderDates = array_column($dailySalesData, 'order_date');
$dailyRevenues = array_column($dailySalesData, 'daily_revenue');

$totalRevenue = array_sum($totalRevenues);

// Truy vấn tồn kho
$stockQuery = "
    SELECT p.nameProduct, SUM(psc.quantityProduct) AS total_quantity 
    FROM products p 
    JOIN product_size psc ON p.idProduct = psc.idProduct 
    GROUP BY p.nameProduct;
";
$stockData = $dbHelper->select($stockQuery);

// Tạo mảng chứa tên sản phẩm và tồn kho
$productNames = [];
$stockCounts = [];
foreach ($stockData as $row) {
    $productNames[] = $row['nameProduct'];
    $stockCounts[] = $row['total_quantity'];
}
$totalStock = array_sum($stockCounts);


// Tổng số orders
$totalOrdersQuery = "
    SELECT COUNT(*) AS total_orders
    FROM orders WHERE statusOrder = 5;
";
$totalOrdersData = $dbHelper->select($totalOrdersQuery);
$totalOrders = $totalOrdersData[0]['total_orders'] ?? 0;

// Lấy idOrder của các đơn hàng đã bán thành công
$idOrderSuccess = $dbHelper->select("SELECT idOrder FROM orders WHERE statusOrder = 5");
//Tổng tiền doanh thu khi đơn hàng hoàn thành
$listTotalRevenueSuccess = $dbHelper->select("SELECT SUM(totalPrice) AS total_revenue FROM orders WHERE statusOrder = 5");
$totalRevenueSuccess = $listTotalRevenueSuccess[0]['total_revenue'] ?? 0;

// Tổng số sản phẩm đã bán
$totalSoldProducts = 0;
$soldProductQuery = "SELECT SUM(quantityOrder) AS sold_products FROM detailorder WHERE idOrder = ?";
foreach ($idOrderSuccess as $order) {
    $soldProductData = $dbHelper->select($soldProductQuery, [$order['idOrder']]);
    $totalSoldProducts += $soldProductData[0]['sold_products'] ?? 0;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "./includes/head.php"; ?>
    <link rel="stylesheet" href="./css/style.css">
    <script type="text/javascript" src="vendor/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="vendor/chart.js/Chart.min.js"></script>
</head>
<style>
.card-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 20px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    flex: 1;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card h2 {
    font-size: 18px;
    margin: 0;
    color: #666;
}

.card p {
    font-size: 24px;
    font-weight: bold;
    color: #333;
}
</style>

<body>
    <div id="wrapper">
        <!-- Sidebar -->
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
                        <i class="fas fa-tachometer-alt me-2"></i> Trang chủ
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./categories/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th-list me-2"></i> Danh mục
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./sub_categories/list.php"
                        class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-th me-2"></i> Danh mục con
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./products/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-boxes me-2"></i> Sản phẩm
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./orders/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-shopping-cart me-2"></i> Đơn hàng
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./users/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-users me-2"></i> Người dùng
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./comments/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-comments me-2"></i> Bình luận
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="./coupons/list.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-tags me-2"></i> Khuyến mãi
                    </a>
                </li>
                <li class="sidebar-nav-item">
                    <a href="settings.php" class="text-white text-decoration-none d-flex align-items-center">
                        <i class="fas fa-cogs me-2"></i> Cài đặt
                    </a>
                </li>
            </ul>
        </div>

        <!-- Nội dung trang -->
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
                            <a class="nav-link" href="../client/index.php" data-toggle="modal"
                                data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw fs-5 mr-2 text-gray-400"></i>
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="card-container">
                        <div class="card">
                            <h2>Tổng Đơn Hàng</h2>
                            <p id="totalRevenue"><?php echo formatCurrencyVND($totalRevenue); ?></p>
                        </div>
                        <div class="card">
                            <h2>Doanh thu thực tế</h2>
                            <p id="totalRevenueSuccess"><?php echo formatCurrencyVND($totalRevenueSuccess); ?></p>
                        </div>
                        <div class="card">
                            <h2>Đơn hàng</h2>
                            <p id="totalOrders"><?php echo $totalOrders ?></p>
                        </div>
                        <div class="card">
                            <h2>Sản phẩm đã bán</h2>
                            <p id="totalSoldProducts"><?php echo $totalSoldProducts ?></p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <!-- Biểu đồ doanh thu hàng tháng -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Doanh thu</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="graph"></canvas>
                                <h5 class="mt-3">Tổng doanh thu: <span id="total-revenue-monthly"
                                        class="fw-bold"><?php echo formatCurrencyVND($totalRevenueSuccess); ?></span>
                                </h5>
                            </div>
                        </div>
                    </div>
                    <!-- Biểu đồ tồn kho -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Tồn kho sản phẩm</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="stockChart"></canvas>
                                <h5 class="mt-3">Tổng số lượng sản phẩm tồn kho: <span id="total-stock"
                                        class="fw-bold"><?php echo $totalStock; ?></span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Dữ liệu doanh thu hàng tháng và theo ngày
    const months = <?php echo json_encode($months); ?>;
    const monthlyRevenues = <?php echo json_encode($monthlyRevenues); ?>;
    const orderDates = <?php echo json_encode($orderDates); ?>;
    const dailyRevenues = <?php echo json_encode($dailyRevenues); ?>;

    // Dữ liệu tồn kho
    const productNames = <?php echo json_encode($productNames); ?>;
    const stockCounts = <?php echo json_encode($stockCounts); ?>;

    // Biểu đồ doanh thu hàng ngày và hàng tháng
    const ctxRevenue = document.getElementById('graph').getContext('2d');
    const revenueChart = new Chart(ctxRevenue, {
        type: 'line',
        data: {
            labels: orderDates,
            datasets: [{
                label: 'Doanh thu theo ngày',
                data: dailyRevenues,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
                fill: true,
                tension: 0.3
            }, {
                label: 'Doanh thu hàng tháng',
                data: monthlyRevenues,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
                fill: true,
                type: 'line',
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Doanh thu (VND)'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Thời gian'
                    }
                }
            }
        }
    });

    // Biểu đồ tồn kho
    const ctxStock = document.getElementById('stockChart').getContext('2d');
    const stockChart = new Chart(ctxStock, {
        type: 'bar',
        data: {
            labels: productNames,
            datasets: [{
                label: 'Tồn kho',
                data: stockCounts,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>
</body>

</html>