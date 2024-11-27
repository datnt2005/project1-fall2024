<?php
session_start();
include_once('./DBUntil.php');
$idUser = 10;  // Bạn có thể thay đổi để lấy từ session khi người dùng đã đăng nhập
$dbHelper = new DBUntil();

// Hàm format tiền tệ
function formatCurrencyVND($number) {
    return number_format($number, 0, ',', '.') . 'đ';
}

// Kiểm tra ID sản phẩm
$idProduct = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
if (!$idProduct || !filter_var($idProduct, FILTER_VALIDATE_INT)) {
    die("Invalid product ID.");
}

// Lấy thông tin sản phẩm và biến thể
$productQuery = "
    SELECT p.idProduct, p.nameProduct, p.description, psz.price, psz.quantityProduct, sz.nameSize
    FROM products p
    JOIN product_size psz ON p.idProduct = psz.idProduct
    JOIN sizes sz ON sz.idSize = psz.idSize
    WHERE p.idProduct = ?
    ORDER BY sz.idSize ASC";
$productResults = $dbHelper->select($productQuery, [$idProduct]);

// Lấy danh sách ảnh sản phẩm
$imageQuery = "SELECT namePicProduct FROM picproduct WHERE idProduct = ?";
$images = $dbHelper->select($imageQuery, [$idProduct]);

// Tổ chức dữ liệu sản phẩm
$products = [];
if ($productResults) {
    foreach ($productResults as $row) {
        $product_id = $row['idProduct'];
        if (!isset($products[$product_id])) {
            $products[$product_id] = [
                'nameProduct' => $row['nameProduct'],
                'description' => $row['description'],
                'price' => $row['price'],
                'variants' => [],
                'images' => [],
            ];
        }

        // Thêm thông tin biến thể
        $variantKey = $row['nameSize'];
        $products[$product_id]['variants'][$variantKey] = [
            'size' => $row['nameSize'],
            'price' => $row['price'],
            'quantity' => $row['quantityProduct']
        ];
    }
}

// Thêm ảnh sản phẩm vào dữ liệu
if ($images) {
    foreach ($images as $image) {
        $products[$idProduct]['images'][] = $image['namePicProduct'];
    }
}

// Xử lý thêm sản phẩm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $productId = $_POST['product_id'];
    $size = $_POST['size'];
    $quantity = $_POST['quantity'];

    // Kiểm tra giá trị hợp lệ
    if (!$productId || !$size || !$quantity) {
        echo "Invalid input.";
        die();
    }

    // Người dùng đã đăng nhập
    if (!empty($_SESSION['idUser'])) {
        $idUser = $_SESSION['idUser']; // Lấy ID người dùng từ session
        
        // Truy vấn lấy ID size từ tên size
        $sizeQuery = "SELECT idSize FROM sizes WHERE nameSize = ?";
        $sizeResult = $dbHelper->select($sizeQuery, [$size]);
        if (!$sizeResult) {
            echo "Size not found.";
            die();
        }
        $idSize = $sizeResult[0]['idSize'];

        // Truy vấn lấy giá sản phẩm theo size
        $priceQuery = "SELECT price FROM product_size WHERE idProduct = ? AND idSize = ?";
        $priceResult = $dbHelper->select($priceQuery, [$productId, $idSize]);
        if (!$priceResult) {
            echo "Price not found.";
            die();
        }
        $price = $priceResult[0]['price'];

        // Kiểm tra giỏ hàng của người dùng đã tồn tại chưa
        $checkCartQuery = "SELECT idCart FROM carts WHERE idUser = ?";
        $checkCartResult = $dbHelper->select($checkCartQuery, [$idUser]);

        if ($checkCartResult) {
            // Giỏ hàng đã tồn tại
            $idCart = $checkCartResult[0]['idCart'];
        } else {
            // Giỏ hàng chưa tồn tại, tạo mới giỏ hàng
            $dbHelper->insert("carts", ["idUser" => $idUser]);
            $idCart = $dbHelper->select("SELECT idCart FROM carts WHERE idUser = ?", [$idUser])[0]['idCart'];
        }

        // Kiểm tra sản phẩm đã có trong giỏ chưa
        $checkDetailCartQuery = "SELECT idDetailCart, quantityCart FROM detailcart WHERE idCart = ? AND idProduct = ? AND size = ?";
        $checkDetailCartResult = $dbHelper->select($checkDetailCartQuery, [$idCart, $productId, $size]);

        if ($checkDetailCartResult) {
            // Sản phẩm đã có trong giỏ, cập nhật số lượng
            $idDetailCart = $checkDetailCartResult[0]['idDetailCart'];
            $newQuantity = $checkDetailCartResult[0]['quantityCart'] + $quantity;
            $dbHelper->update("detailcart", ['quantityCart' => $newQuantity], "idDetailCart = $idDetailCart");
        } else {
            // Thêm sản phẩm mới vào giỏ
            $data = [
                "idCart" => $idCart,
                "idProduct" => $productId,
                "size" => $size,
                "quantityCart" => $quantity,
                "price" => $price
            ];
            $dbHelper->insert("detailcart", $data);
        }

        // Redirect để tránh gửi lại form
        header("Location: detailProduct.php?id=$productId");
        exit;
    } else {
        // Người dùng chưa đăng nhập
        header("Location: login.php");
        exit;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<style>
.product-content {
    padding: 20px;
    /* border: 1px solid #ddd; */
    border-radius: 5px;
}

.variant-btn.active {
    background-price: #28a745;
    price: #fff;
}

.product-price {
    font-size: 24px;
    font-weight: bold;
    price: #d9534f;
}

.btn-plus,
.btn-minus {
    cursor: pointer;
}

input[type="number"] {
    width: 70px;
    text-align: center;
}
</style>

<body>
    <?php include "./includes/header.php" ?>

    <main class="container my-4">

        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($products[$idProduct]['images'])): ?>
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <?php foreach ($products[$idProduct]['images'] as $index => $image) : ?>
                        <button type="button" data-bs-target="#carouselExampleIndicators"
                            data-bs-slide-to="<?= $index; ?>" class="<?= $index === 0 ? 'active' : ''; ?>"
                            aria-label="Slide <?= $index + 1; ?>"></button>
                        <?php endforeach; ?>
                    </div>
                    <div class="carousel-inner">
                        <?php foreach ($products[$idProduct]['images'] as $index => $image) : ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
                            <img src="../admin/products/image/<?= htmlspecialchars($image); ?>" class="d-block w-100"
                                alt="Product Image">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <?php else: ?>
                <p>Không có hình ảnh sản phẩm để hiển thị.</p>
                <?php endif; ?>
            </div>


            <div class="col-md-6">
                <div class="product-content">
                    <form id="product-form-<?= $idProduct; ?>" data-product-id="<?= $idProduct; ?>"
                        action="" method="POST">
                        <input type="hidden" name="product_id" value="<?= $idProduct; ?>">
                        <h3 class="fw-bold fs-3 mb-3"><?= htmlspecialchars($products[$idProduct]['nameProduct']); ?>
                        </h3>
                        <div class="product-description mb-4 mt-3">
                            <p><?= nl2br(htmlspecialchars($products[$idProduct]['description'])); ?></p>
                        </div>

                        <!-- Phần Size và Giá -->
                        <div class="product-size mb-4">
                            <label for="size-selection" class="form-label">Chọn kích thước (Khối lượng):</label><br>
                            <div class="button-group">
                                <?php foreach ($products[$idProduct]['variants'] as $variantKey => $variant) : ?>
                                <?php if ($variant['quantity'] > 0): ?>
                                <button type="button" class="btn btn-outline-success  rounded  btn-variant m-2 mb-3"
                                    data-size="<?= htmlspecialchars($variant['size']); ?>"
                                    data-price="<?= htmlspecialchars($variant['price']); ?>"
                                    data-quantity="<?= htmlspecialchars($variant['quantity']); ?>" 
                                    value="<?= htmlspecialchars($variant['size']); ?>">
                                    <?= htmlspecialchars($variant['size']); ?>
                                </button>
                                <?php else: ?>
                                <button type="button" class="btn btn-outline-secondary  rounded btn-variant m-2 mb-3 disabled" disabled>
                                    <?= htmlspecialchars($variant['size']); ?> (Hết hàng)
                                </button>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                            <input type="hidden" name="size" id="selected-size-<?= $idProduct; ?>">
                            <p class="errors text-danger mt-2" id="variant-error-<?= $idProduct; ?>"></p>
                        </div>

                        <!-- Giá sản phẩm -->
                        <div class="product_price fs-4 mt-3 fw-bold text-danger" id="current-price">
                            <?= formatCurrencyVND($products[$idProduct]['price']); ?>
                        </div>

                        <!-- Phần Số lượng -->
                        <div class="product-quantity mt-4">
                            <label for="quantity" class="form-label">Chọn số lượng:</label>
                            <div class="input-group w-25 mb-3">
                                <button class="btn btn-outline-success btn-minus" type="button">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="100"
                                    class="form-control border border-success text-center">
                                <button class="btn btn-outline-success btn-plus" type="button">+</button>
                            </div>
                        </div>

                        <!-- Nút Thêm vào giỏ hàng -->
                        <div class="product-cart mt-4">
                        <button type="button" onclick="submitFormCart(<?= $idProduct; ?>)" class="btn btn-primary w-100 btn-add-to-cart">
                            Thêm vào giỏ hàng
                            </button>

                        </div>
                </div>
                </form>
            </div>

        </div>
        <hr>
        <div class="my-4" style="width: 50%;">
            <h3>Thống Kê Đánh Giá</h3>
            <div class="rating-statistics">
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">5 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 70%;" aria-valuenow="70"
                            aria-valuemin="0" aria-valuemax="100">70%</div>
                    </div>
                    <span class="ms-2">70%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">4 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%;" aria-valuenow="20"
                            aria-valuemin="0" aria-valuemax="100">20%</div>
                    </div>
                    <span class="ms-2">20%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">3 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 5%;" aria-valuenow="5"
                            aria-valuemin="0" aria-valuemax="100">5%</div>
                    </div>
                    <span class="ms-2">5%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">2 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 3%;" aria-valuenow="3"
                            aria-valuemin="0" aria-valuemax="100">3%</div>
                    </div>
                    <span class="ms-2">3%</span>
                </div>
                <div class="d-flex align-items-center mb-2">
                    <span class="me-2">1 sao</span>
                    <div class="progress w-100">
                        <div class="progress-bar bg-dark" role="progressbar" style="width: 2%;" aria-valuenow="2"
                            aria-valuemin="0" aria-valuemax="100">2%</div>
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
                        <p class="card-text">“Sản phẩm khá ổn, nhưng giao hàng hơi chậm. Hy vọng lần tới sẽ nhanh hơn.”
                        </p>
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
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="card-img-top"
                            alt="Sản phẩm liên quan">
                        <div class="card-body">
                            <h5 class="card-title">Sản Phẩm 1</h5>
                            <p class="card-text">Giá: 80,000 VNĐ</p>
                            <a href="#" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="card-img-top"
                            alt="Sản phẩm liên quan">
                        <div class="card-body">
                            <h5 class="card-title">Sản Phẩm 2</h5>
                            <p class="card-text">Giá: 90,000 VNĐ</p>
                            <a href="#" class="btn btn-primary">Xem Chi Tiết</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="card-img-top"
                            alt="Sản phẩm liên quan">
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
document.addEventListener('DOMContentLoaded', () => {
    const variantButtons = document.querySelectorAll('.btn-variant');
    const priceDisplay = document.getElementById('current-price');

    // Xử lý chọn khối lượng
    variantButtons.forEach(button => {
        button.addEventListener('click', () => {
            const size = button.getAttribute('data-size'); // Lấy khối lượng
            const price = button.getAttribute('data-price'); // Lấy giá
            const quantity = button.getAttribute('data-quantity');
            const formId = button.closest('form').id;
            const productId = formId.replace('product-form-', '');

            // Lưu giá trị size và price vào input ẩn
            document.getElementById(`selected-size-${formId.split('-')[2]}`).value = size;
            // Lưu giá trị size và price vào input ảnh            

            // Cập nhật giá hiển thị
            priceDisplay.textContent = `${parseInt(price).toLocaleString('vi-VN')}đ`;

            // Đổi trạng thái nút
            variantButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
        });
    });

    // Handle quantity change
    document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const quantityInput = this.parentElement.querySelector('#quantity');
                let quantity = parseInt(quantityInput.value, 10);
                quantityInput.value = Math.min(quantity + 1, 100);
            });
        });

        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const quantityInput = this.parentElement.querySelector('#quantity');
                let quantity = parseInt(quantityInput.value, 10);
                quantityInput.value = Math.max(quantity - 1, 1);
            });
        });
});

function submitFormCart(productId) {
    // Lấy thông tin size đã chọn và số lượng từ form
    var size = document.querySelector(`#selected-size-${productId}`).value;
    var quantity = document.querySelector(`#quantity`).value;

    // Kiểm tra nếu chưa chọn size
    if (!size) {
        document.getElementById(`variant-error-${productId}`).textContent = 'Vui lòng chọn khối lượng.';
        return;
    }else {
            const selectedVariantKey = size ;
            const selectedVariantButton = document.querySelector(`.btn-variant[value="${selectedVariantKey}"]`);
            const availableQuantity = parseInt(selectedVariantButton.getAttribute('data-quantity'), 10);

            if (quantity > availableQuantity) {
                document.getElementById(`variant-error-${productId}`).textContent =
                    'Số lượng yêu cầu vượt quá số lượng có sẵn.';
            } else {
                document.getElementById(`variant-error-${productId}`).textContent = '';
                const form = document.getElementById(`product-form-${productId}`);
                form.submit();
            }
        }

}

</script>
</body>

</html>