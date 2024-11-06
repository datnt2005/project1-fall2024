<?php

include "./DBUntil.php";
$dbHelper = new DBUntil();
$categories = $dbHelper->select("SELECT * FROM categories");
$subCategories = $dbHelper->select("SELECT * FROM subcategory INNER JOIN categories ON subcategory.idCategory = categories.idCategory");
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
                  <a href="shop.php?view=<?php echo $subcategory['idSubCategory']; ?>">
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
