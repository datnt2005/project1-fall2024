<?php
include "./DBUntil.php";
session_start();
$dbHelper = new DBUntil();
var_dump($_SESSION['idUser']) ?? null;
$login_success = false;
// echo ($_SESSION['id']);
if (isset($_SESSION['success'])) {
    $login_success = true;
}


// Function to format numbers as currency (VND)
function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';
}



// Get the product ID from the URL and check if it's set
$idProduct = $_GET['id'] ?? null;
if (!$idProduct) {
    echo "Product ID not found.";
    exit;
}

// Fetch product data with related sizes (without LIMIT for namePicProduct)
$listProducts = $dbHelper->select(
    "SELECT PR.*, prs.*, sz.*, 
    (SELECT GROUP_CONCAT(namePicProduct) FROM picproduct WHERE idProduct = PR.idProduct) AS namePicProduct,
    prs.quantityProduct AS total_quantity
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

// HTML output for size selection
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
<style>
    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(100%);
        /* Đổi biểu tượng thành màu đen */
    }

    .rating .fa {
        font-size: 1.2em;
    }

    .checked {
        color: gold;
    }
</style>

<body>
    <?php include "./includes/header.php" ?>

    <main class="container my-4">

        <div class="row">
            <div class="col-md-6">
                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">

                    <!-- Carousel Indicators for Thumbnails -->
                    <div class="carousel-indicators">
                        <?php
                        $slideIndex = 0;
                        $images = explode(',', $listProducts[0]['namePicProduct']);
                        foreach ($images as $image) {
                            $thumbnailImage = '../admin/products/image/' . trim($image);
                        ?>
                            <img
                                src="<?php echo $thumbnailImage; ?>"
                                data-bs-target="#productCarousel"
                                data-bs-slide-to="<?php echo $slideIndex; ?>"
                                class="img-thumbnail <?php echo $slideIndex === 0 ? 'active' : ''; ?>"
                                style="width: 60px; height: 60px; cursor: pointer;"
                                alt="Thumbnail">
                        <?php
                            $slideIndex++;
                        }
                        ?>
                    </div>

                    <!-- Carousel Inner for Main Images -->
                    <div class="carousel-inner">
                        <?php
                        $firstItem = true;
                        foreach ($images as $image) {
                            $productImage = '../admin/products/image/' . trim($image);
                        ?>
                            <div class="carousel-item <?php echo $firstItem ? 'active' : ''; ?>">
                                <img src="<?php echo $productImage; ?>" class="d-block w-100" alt="Product Image">
                            </div>
                        <?php
                            $firstItem = false;
                        }
                        ?>
                    </div>

                    <!-- Carousel Controls -->
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
                <?php
                // Display only the first product
                $product = $listProducts[0];
                ?>
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

                <div class="col-md-3">
                    <label for="size" class="form-label">Phân Loại (Size)</label>
                    <select id="size" class="form-select" style="width: 120px;" onchange="updatePriceAndStock()">
                        <?php foreach ($listProducts as $productsize) { ?>
                            <option value="<?php echo htmlspecialchars($productsize['nameSize']); ?>"
                                data-price="<?php echo htmlspecialchars($productsize['price']); ?>"
                                data-stock="<?php echo htmlspecialchars($productsize['total_quantity']); ?>">
                                <?php echo htmlspecialchars($productsize['nameSize']); ?>
                            </option>
                        <?php } ?>
                    </select>

                </div>

                <div class="col-md-6">
                    <div class="col-md-3">
                        <label for="quantity" class="form-label">Số lượng</label>
                        <div class="input-group" style="width: 160px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity(<?php echo $product['idProduct'] ?>)">-</button>
                            <input type="number" id="quantity_<?php echo $product['idProduct'] ?>" class="form-control text-center" value="1" min="1" style="width: 70px; font-size: 16px; padding: 5px;" data-max-quantity="<?php echo $product['total_quantity'] ?>" onchange="validateQuantity(<?php echo $product['idProduct'] ?>)">
                            <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity(<?php echo $product['idProduct'] ?>)">+</button>
                        </div>
                    </div>
                    <div id="quantity-error-<?php echo $product['idProduct'] ?>" class="text-danger ms-2" style="font-size: 15px; display: none; white-space: nowrap;">Không thể chọn số lượng vượt quá số lượng trong kho.</div>
                </div>
                <br>
                <a href="cart.php?id=<?php echo $product['idProduct']; ?>&size=<?php echo $product['nameSize']; ?>&quantity=" id="add-to-cart-<?php echo $product['idProduct']; ?>" class="btn btn-primary">Thêm vào giỏ hàng</a>
            </div>


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

    <?php include "./includes/footer.php" ?>

    <script src="./js/script.js"></script>
</body>

</html>
<script>
    function updatePrice() {
        // Get the selected size option
        const sizeSelect = document.getElementById('size');
        const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];

        // Get the price from the selected option's data attribute
        const newPrice = selectedOption.getAttribute('data-price');

        // Update the price display
        const priceElement = document.querySelector('.product-item .text-danger');
        priceElement.innerHTML = 'Giá: ' + formatCurrencyVND(newPrice);
    }

    // Helper function to format the currency (VND)
    function formatCurrencyVND(number) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(number);
    }
</script>

<script>
    function updatePriceAndStock() {
        const sizeSelect = document.getElementById('size');
        const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];

        // Get the new price and stock for the selected size
        const newPrice = selectedOption.getAttribute('data-price');
        const stock = selectedOption.getAttribute('data-stock');

        // Update the price display
        const priceElement = document.querySelector('.product-item .text-danger');
        priceElement.innerHTML = 'Giá: ' + formatCurrencyVND(newPrice);

        // Update the maximum quantity based on selected size stock
        const quantityInput = document.getElementById('quantity_<?php echo $product['idProduct'] ?>');
        quantityInput.setAttribute('max', stock);

        // Reset the quantity to 1 if the new size stock is less than the current quantity
        if (parseInt(quantityInput.value) > stock) {
            quantityInput.value = 1;
        }

        // Hide error message if within stock limits
        document.getElementById('quantity-error-<?php echo $product['idProduct'] ?>').style.display = 'none';
    }

    // Ensure quantity doesn't exceed max when user types in the input field
    function validateQuantity(productId) {
    var quantityInput = document.getElementById('quantity_' + productId);
    var quantityError = document.getElementById('quantity-error-' + productId);
    var currentQuantity = parseInt(quantityInput.value);
    var sizeSelect = document.getElementById('size');
    var selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
    var maxQuantity = parseInt(selectedOption.getAttribute('data-stock'));

    // Nếu số lượng nhỏ hơn 1, đặt lại giá trị về 1
    if (currentQuantity < 1) {
        quantityInput.value = 1;
    }

    // Nếu số lượng vượt quá số lượng trong kho, thiết lập giá trị tối đa
    if (currentQuantity > maxQuantity) {
        quantityInput.value = maxQuantity; // Set to max stock
        quantityError.style.display = 'block'; // Hiển thị thông báo lỗi
    } else {
        quantityError.style.display = 'none'; // Ẩn thông báo lỗi nếu trong giới hạn
    }
}


function decreaseQuantity(productId) {
    var quantityInput = document.getElementById('quantity_' + productId);
    var currentQuantity = parseInt(quantityInput.value);

    // Nếu số lượng lớn hơn 1, giảm đi 1
    if (currentQuantity > 1) {
        quantityInput.value = currentQuantity - 1;
    }

    // Gọi hàm validateQuantity để kiểm tra lại số lượng
    validateQuantity(productId);

    // Ẩn thông báo lỗi khi điều chỉnh số lượng
    document.getElementById('quantity-error-' + productId).style.display = 'none';
}


function increaseQuantity(productId) {
    var quantityInput = document.getElementById('quantity_' + productId);
    var currentQuantity = parseInt(quantityInput.value);
    var sizeSelect = document.getElementById('size');
    var selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
    var maxQuantity = parseInt(selectedOption.getAttribute('data-stock'));

    // Nếu số lượng nhỏ hơn số lượng tối đa, tăng lên 1
    if (currentQuantity < maxQuantity) {
        quantityInput.value = currentQuantity + 1;
    }

    // Kiểm tra lại số lượng sau khi tăng
    toggleQuantityError(productId, currentQuantity + 1, maxQuantity);
}

    // Function to toggle error message display based on quantity input
    function toggleQuantityError(productId, currentQuantity, maxQuantity) {
        var quantityError = document.getElementById('quantity-error-' + productId);
        if (currentQuantity >= maxQuantity) {
            quantityError.style.display = 'block'; // Show error if over max
        } else {
            quantityError.style.display = 'none'; // Hide error if within bounds
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
</body>

</html>