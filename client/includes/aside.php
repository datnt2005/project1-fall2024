<?php
include_once("./DBUntil.php");
$dbHelper = new DBUntil();

// Lấy tất cả danh mục và danh mục con
$categories = $dbHelper->select("SELECT * FROM categories");
$subCategories = $dbHelper->select("SELECT * FROM subcategory INNER JOIN categories ON subcategory.idCategory = categories.idCategory");

// Hàm format tiền tệ
function formatCurrencyVND($number)
{
    return number_format($number, 0, ',', '.') . 'đ';
}

// Lấy giá trị từ URL (category, subcategory, min_price, max_price)
$categoryId = isset($_GET['category']) ? $_GET['category'] : null;
$subcategoryId = isset($_GET['subcategory']) ? $_GET['subcategory'] : null;
$minPrice = isset($_GET['min_price']) ? $_GET['min_price'] : null;
$maxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : null;
$page = isset($_GET['page']) ? $_GET['page'] : 1;  // Trang hiện tại, mặc định là trang 1
$productsPerPage = 9;  // Số sản phẩm trên mỗi trang (đổi thành 9)
$offset = ($page - 1) * $productsPerPage;  // Tính toán vị trí bắt đầu

// Truy vấn sản phẩm, lọc theo category, subcategory và giá trị min_price, max_price nếu có
$query = "SELECT PR.*, 
                 SUM(PS.quantityProduct) AS total_quantity, 
                 PS.price AS price,
                 (SELECT PI.namePicProduct 
                  FROM picproduct PI 
                  WHERE PI.idProduct = PR.idProduct 
                  ORDER BY PI.idPicProduct 
                  LIMIT 1) AS namePicProduct
          FROM products PR
          INNER JOIN product_size PS ON PR.idProduct = PS.idProduct";

// Nếu có category
if ($categoryId) {
    $query .= " WHERE PR.idCategory = $categoryId";
}

// Nếu có subcategory
if ($subcategoryId) {
    if ($categoryId) {
        $query .= " AND PR.idSubCategory = $subcategoryId";
    } else {
        $query .= " WHERE PR.idSubCategory = $subcategoryId";
    }
}

// Nếu có giá trị min_price và max_price
if ($minPrice && $maxPrice) {
    if ($categoryId || $subcategoryId) {
        $query .= " AND PS.price BETWEEN $minPrice AND $maxPrice";
    } else {
        $query .= " WHERE PS.price BETWEEN $minPrice AND $maxPrice";
    }
}

$query .= " GROUP BY PR.idProduct LIMIT $productsPerPage OFFSET $offset";

// Thực thi truy vấn lấy sản phẩm
$listProducts = $dbHelper->select($query);

// Truy vấn đếm tổng số sản phẩm để tính số trang
$countQuery = "SELECT COUNT(DISTINCT PR.idProduct) AS total FROM products PR INNER JOIN product_size PS ON PR.idProduct = PS.idProduct";

// Nếu có category, subcategory, min_price, max_price
if ($categoryId) {
    $countQuery .= " WHERE PR.idCategory = $categoryId";
}
if ($subcategoryId) {
    if ($categoryId) {
        $countQuery .= " AND PR.idSubCategory = $subcategoryId";
    } else {
        $countQuery .= " WHERE PR.idSubCategory = $subcategoryId";
    }
}
if ($minPrice && $maxPrice) {
    if ($categoryId || $subcategoryId) {
        $countQuery .= " AND PS.price BETWEEN $minPrice AND $maxPrice";
    } else {
        $countQuery .= " WHERE PS.price BETWEEN $minPrice AND $maxPrice";
    }
}

$totalProducts = $dbHelper->select($countQuery);
$totalPages = ceil($totalProducts[0]['total'] / $productsPerPage);  // Tính số trang

?>
<aside class="sidebar">
    <div class="sidebar">
        <div class="sidebar-product-general pt-4 ">
            <ul class="px-0 heading">
                <li class="category-item  fw-bold fs-4">
                    DANH MỤC SẢN PHẨM
                </li>
            </ul>
            <ul class="category-list px-0">
                <?php foreach ($categories as $category) { ?>
                    <li class="category-item mt-4">
                        <a href="shop.php?category=<?php echo $category['idCategory']; ?>" class="fw-bold fs-5 text-uppercase">
                            <?php echo htmlspecialchars($category['nameCategory']); ?>
                        </a>

                    </li>
                    <li class="category-item subcategory">
                        <?php foreach ($subCategories as $subcategory) {

                            if ($subcategory['idCategory'] == $category['idCategory']) { ?>
                    <li class="category-item subcategory">
                        <a href="shop.php?subcategory=<?php echo $subcategory['idSubCategory']; ?>">
                            <?php echo htmlspecialchars($subcategory['nameSubCategory']); ?>
                        </a>

                    </li>
                <?php } ?>
            <?php } ?>

            </li>
        <?php } ?>
            </ul>






            <ul class="mt-4 px-0">
                <li class="category-item">
                    <a href="shop.php?view=Dog_products"
                        class="fw-bold fs-5 text-uppercase">Giá</a>
                </li>
                <li class="category-item  price"><a
                        href="../client/shop.php?min_price=0&amp;max_price=5000000">0k
                        - 100k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=5000000&amp;max_price=10000000">100k
                        - 200k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=10000000&amp;max_price=15000000">200k
                        - 300k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=15000000&amp;max_price=20000000">300k
                        - 400k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=20000000&amp;max_price=30000000">400k
                        - 500k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=30000000&amp;max_price=40000000">500k
                        - 1000k</a></li>
            </ul>
        </div>
    </div>

</aside>