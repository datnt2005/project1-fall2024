<?php
include_once("./DBUntil.php");
$dbHelper = new DBUntil();
$view = "";
if (isset($_GET['view'])) {
    $view = $_GET['view'];
} else {
    $view = "";
}

$categories = $dbHelper->select("SELECT * FROM categories");
$subcategories = $dbHelper->select("SELECT * FROM subcategory");

$subcategoriesByCategory = [];
foreach ($subcategories as $subcate) {
    $subcategoriesByCategory[$subcate['idCategory']][] = $subcate;
}

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
                <?php foreach ($categories as $category) : ?>
                <li class="category-item mt-4">
                    <a href="shop.php?category=<?php echo $category['idCategory']; ?>"
                        class="fw-bold fs-5 text-uppercase">
                        <?php echo $category['nameCategory']; ?>
                    </a>
                </li>
                <?php if (isset($subcategoriesByCategory[$category['idCategory']])) : ?>
                <?php foreach ($subcategoriesByCategory[$category['idCategory']] as $subCategory) : ?>
                <li class="category-item subcategory">
                    <a href="shop.php?view=<?php echo $subCategory['idSubCategory']; ?>">
                        <?php echo $subCategory['nameSubCategory']; ?>
                    </a>

                </li>

                <?php endforeach; ?>
                <?php endif; ?>
                <?php endforeach; ?>
            </ul>






            <ul class="mt-4 px-0">
                <li class="category-item">
                    <a href="shop.php?view=Dog_products" class="fw-bold fs-5 text-uppercase">Giá</a>
                </li>
                <li class="category-item  price"><a href="../client/shop.php?min_price=0&amp;max_price=100000">0k
                        - 100k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=100000&amp;max_price=200000">100k
                        - 200k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=200000&amp;max_price=300000">200k
                        - 300k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=300000&amp;max_price=400000">300k
                        - 400k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=400000&amp;max_price=500000">400k
                        - 500k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=500000&amp;max_price=1000000">500k
                        - 1000k</a></li>
                <li class="category-item price"><a
                        href="../client/shop.php?min_price=1000000">Trên 1000k</a></li>
                
            </ul>
        </div>
    </div>

</aside>