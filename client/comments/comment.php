<?php
// include_once "./../DBUntil.php";
// $dbHelper = new DBUntil();
$idUser = $_SESSION['idUser'] ?? null;
$idProduct = $_GET['id'] ?? null;
// Lấy danh sách bình luận từ cơ sở dữ liệu

$listCmt = $dbHelper->select("SELECT cmt.*, pcmt.namePicComment AS namePic, us.* FROM comments cmt
                                INNER JOIN users us ON cmt.idUser = us.idUser
                                LEFT JOIN piccomment pcmt ON pcmt.idComment = cmt.idComment
                                WHERE cmt.idProduct = ?", [$idProduct]);
foreach ($listCmt as $comment) {
    if (!empty($comment['namePic'])) {
        $imageNamesByComment[$comment['idComment']][] = htmlspecialchars($comment['namePic']);
    }
}
// var_dump($imageNames);
// Hàm đếm số lượng bình luận theo từng mức sao
function countEvaluate($listCmt, $evaluate) {
    return count(array_filter($listCmt, function($cmt) use ($evaluate) {
        return $cmt['evaluate'] == $evaluate; // Giả định rằng cột 'evaluate' trong cơ sở dữ liệu chứa điểm đánh giá (1 đến 5 sao)
    }));
}

// Tính tổng số đánh giá và tổng số sao
$totalEvaluate = 0;
$totalStars = 0;
foreach ($listCmt as $evaluate) {
    $count = countEvaluate($listCmt, $evaluate['evaluate']); // Số lượng đánh giá theo mức sao
    $totalEvaluate += $count; // Tổng số lượng đánh giá
    $totalStars += $evaluate['evaluate'] * $count; // Tổng số sao
}

// Tính trung bình đánh giá
$averageRating = $totalEvaluate > 0 ? $totalStars / $totalEvaluate : 0;

//mua hàng mới được đánh giá
$result = $dbHelper->select("SELECT dadd.*, ord.*, dor.*, prd.*, us.* FROM users us
                             INNER JOIN detail_address dadd ON us.idUser = dadd.user_id
                             INNER JOIN orders ord ON dadd.detail_id = ord.idAddress   
                             INNER JOIN detailorder dor ON ord.idOrder = dor.idOrder 
                             INNER JOIN products prd ON dor.idProduct = prd.idProduct
                             WHERE  prd.idProduct = ?  AND us.idUser = ?", [$idProduct, $idUser]);

$status = $result[0]['statusOrder'] ?? null;    
?>

<section id="accountInformation" class="py-5 h-100">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="comment">
                    <div class="totalEvaluate p-4">
                        <h3>ĐÁNH GIÁ SẢN PHẨM</h3>
                        <div class="container">
                            <div class="row p-3 rounded-3" style="background-color: #f1f1f1;">
                                <div class="col-md-3">
                                    <div class="evaluate text-center text-warning fs-4 align-items-center">
                                        <span class="evaluateStart text fs-3"><?= round($averageRating, 1); ?></span>
                                        <span class="totalStart text">trên 5</span>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>
                                <aside class="col-md-9  text-dark ">
                                    <div class="filter ">
                                        <h5 class="text-dark">Lọc theo</h5>
                                        <div class="list-evaluate">
                                            <ul class="list-evaluate-list d-flex list-unstyled p-1">
                                                <li class="list-evaluate-item">
                                                    <a class="text-dark text-decoration-none border border-dark rounded p-1 w-100 m-2"
                                                        href="#">
                                                        Tất cả (<?php echo count($listCmt); ?>)
                                                    </a>
                                                </li>
                                                <li class="list-evaluate-item">
                                                    <a class="text-dark text-decoration-none border border-dark rounded p-1 w-100 m-2"
                                                        href="#">
                                                        5 Sao
                                                        (<?php echo countEvaluate($listCmt, 5); ?>)
                                                    </a>
                                                </li>
                                                <li class="list-evaluate-item">
                                                    <a class="text-dark text-decoration-none border border-dark rounded p-1 w-100 m-2"
                                                        href="#">
                                                        4 Sao
                                                        (<?php echo countEvaluate($listCmt, 4); ?>)
                                                    </a>
                                                </li>
                                                <li class="list-evaluate-item">
                                                    <a class="text-dark text-decoration-none border border-dark rounded p-1 w-100 m-2"
                                                        href="#">
                                                        3 Sao
                                                        (<?php echo countEvaluate($listCmt, 3); ?>)
                                                    </a>
                                                </li>
                                                <li class="list-evaluate-item">
                                                    <a class="text-dark text-decoration-none border border-dark rounded p-1 w-100 m-2"
                                                        href="#">
                                                        2 Sao
                                                        (<?php echo countEvaluate($listCmt, 2); ?>)
                                                    </a>
                                                </li>
                                                <li class="list-evaluate-item">
                                                    <a class="text-dark text-decoration-none border border-dark rounded p-1 w-100 m-2"
                                                        href="#">
                                                        1 Sao
                                                        (<?php echo countEvaluate($listCmt, 1); ?>)
                                                    </a>
                                                </li>
                                            </ul>

                                        </div>
                                    </div>
                                </aside>
                            </div>
                            <div class="row">

                                <?php 
                                if ($result != null && $status == "5") {
                                    include ('./comments/add.php'); 
                                }else {
                                    "";
                                }                             
                            ?>


                                <div class="listComment">
                                    <?php 
                                $displayedComments = []; // Mảng để theo dõi các bình luận đã hiển thị
                                foreach ($listCmt as $comment) { 
                                    if (in_array($comment['idComment'], $displayedComments)) {
                                        continue; // Nếu bình luận đã được hiển thị, bỏ qua
                                    }
                                    // Thêm bình luận vào danh sách đã hiển thị
                                    $displayedComments[] = $comment['idComment'];
                                ?>

                                    <div class="list-group-item border p-3 mt-3">
                                        <div class="row">
                                            <div class="col-md-1">
                                                <img src="../admin/users/image/<?= $comment['image'] ?>"
                                                    class="img-fluid border mx-5 d-block"
                                                    style="width: 35px; height: 35px; border-radius: 50%;" alt="avt">
                                            </div>
                                            <div class="col-md-11">
                                                <div class="user d-flex">
                                                    <p class="fw-bold"><?= $comment['name'] ?></p>
                                                    <span class="date text-secondary mx-3">'
                                                        <?= $comment['commentDate'] ?> '</span>

                                                </div>
                                                <div class="comment-evaluate">
                                                    <div class="starEvaluate ">
                                                        <?php for ($i = 0; $i < $comment['evaluate']; $i++) {
                                                        echo '<i class="fa-solid fa-star text-warning"></i>';
                                                    } ?>
                                                    </div>

                                                    <div class="comment-text">
                                                        <p>" <?= $comment['comment_text'] ?> "</p>
                                                    </div>
                                                    <div class="imageComment">
                                                        <?php if (!empty($imageNamesByComment[$comment['idComment']])) {
                                                        foreach ($imageNamesByComment[$comment['idComment']] as $image) { ?>
                                                        <img src="./comments/image/<?= $image ?>" class="img-fluid m-3"
                                                            width="100px" height="100px" alt="comment image">
                                                        <?php }
                                                    } ?>
                                                    </div>
                                                    <?php if ($_SESSION['idUser'] == $comment['idUser']) { ?>
                                                    <div class="action">
                                                        <a href="comments/update.php?idComment=<?php echo $comment['idComment'] ?>&idProduct=<?php echo $idProduct; ?>"
                                                            class="update_users text-decoration-none fw-bold mx-2">Chỉnh
                                                            sửa</a>
                                                        

                                                        <a href="comments/remove.php?idComment=<?php echo $comment['idComment'] ?>&idProduct=<?php echo $idProduct; ?>"
                                                            class="remove_users fw-bold text-danger text-decoration-none">Xóa</a>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


