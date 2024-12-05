<?php
include "../../client/DBUntil.php";
session_start();
$dbHelper = new DBUntil();

// Kiểm tra xem người dùng đã tìm kiếm chưa
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
$listComment = [];

// Thực hiện tìm kiếm nếu có từ khóa, nếu không thì lấy tất cả danh mục
if (!empty($searchTerm)) {
    $listComment = $dbHelper->select("SELECT  cmt.*, pcmt.*, us.*, prd.* FROM comments cmt
                                      LEFT JOIN piccomment pcmt ON pcmt.idComment = cmt.idComment
                                      JOIN products prd ON prd.idProduct = cmt.idProduct
                                      JOIN users us ON cmt.idUser = us.idUser
                                      WHERE cmt.comment_text LIKE ? OR cmt.evaluate LIKE ?", 
                                      array('%' . $searchTerm . '%', '%' . $searchTerm . '%'));
} else {
    $listComment = $dbHelper->select("SELECT  cmt.*, pcmt.*, us.*, prd.* FROM comments cmt
                                      LEFT JOIN piccomment pcmt ON pcmt.idComment = cmt.idComment
                                      JOIN products prd ON prd.idProduct = cmt.idProduct
                                      JOIN users us ON cmt.idUser = us.idUser");
}

// Tạo mảng lưu tên hình ảnh theo id bình luận
$imageNamesByComment = [];
foreach ($listComment as $picComment) {
    if (!empty($picComment['namePic'])) {
        $imageNamesByComment[$picComment['idComment']][] = htmlspecialchars($picComment['namePic']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
    <div id="wrapper">
        <?php include "../includes/sidebar.php" ?>
        <!-- Page Content -->
        <div id="content">
            <?php include "../includes/nav.php" ?>
            <!-- Main Content -->
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Bình luận</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mt-4">
                                    <div class="search-items">
                                        <form method="GET">
                                            <input class="input-search mb-3" type="search" name="search" id="search"
                                                placeholder="Tìm kiếm" style="height: 35px;">
                                            <button type="submit" class="btn btn-dark bg-gradient text-white"
                                                style="height: 35px">Tìm kiếm</button>
                                        </form>
                                    </div>
                                </div>
                                <table class="table" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Tên</th>
                                            <th>Bình luận</th>
                                            <th>Sản phẩm</th>
                                            <th>Hình ảnh</th>
                                            <th>Thời gian</th>
                                            <th>Đánh giá</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $displayedComments = [];
                                        foreach ($listComment as $comments) { 
                                            if (in_array($comments['idComment'], $displayedComments)) {
                                                continue; // Skip if already displayed
                                            }
                                            $displayedComments[] = $comments['idComment'];
                                    ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($comments['name']); ?></td>
                                            <td><?php echo htmlspecialchars($comments['comment_text']); ?></td>
                                            <td><?php echo htmlspecialchars($comments['nameProduct']); ?></td>
                                            <td>
                                                <?php if (!empty($imageNamesByComment[$comments['idComment']])): ?>
                                                    <?php foreach ($imageNamesByComment[$comments['idComment']] as $image): ?>
                                                        <img src="../../client/comments/image/<?php echo $image; ?>" class="img-fluid m-1"
                                                            width="50px" height="50px" alt="Comment Image">
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <span>Không có hình ảnh</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($comments['commentDate']); ?></td>
                                            <td><?php echo htmlspecialchars($comments['evaluate']); ?> <i class="fa-solid fa-star text-warning"></i></td>
                                            <td>
                                                <div class="action">
                                                    <a href="../../client/detailProduct.php?id=<?php echo htmlspecialchars($comments['idProduct']); ?>"
                                                       class="remove_users fw-bold text-primary text-decoration-none mx-3"><i class="fs-5 fa-solid fa-eye"></i></a>
                                                    <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="confirmDelete('<?php echo $comments['idComment'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
    </div>
</body>
<script src="../js/script.js"></script>
</html>
