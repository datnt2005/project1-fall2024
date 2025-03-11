<?php
include "./../DBUntil.php";
session_start(); // Khởi động session
$dbHelper = new DBUntil();
$idComment = $_GET['idComment'];
$idProduct = $_GET['idProduct'];

// Lấy thông tin bình luận và hình ảnh từ CSDL
$listComments = $dbHelper->select("SELECT cmt.*, picCmt.* 
                                   FROM comments cmt
                                   LEFT JOIN piccomment picCmt 
                                   ON picCmt.idComment = cmt.idComment
                                   WHERE cmt.idComment = ?", [$idComment]);

$picComment = [];
foreach ($listComments as $pic) {
    if (!empty($pic['namePic'])) {
        $picComment[] = $pic['namePic'];
    }   
};

$errors = [];
$comment = "";
$evaluate = "";
$idUser = $_SESSION['idUser'] ?? null; // Giả sử bạn có sử dụng session để lấy ID người dùng
$imageNames = [];

// Kiểm tra nếu form bình luận được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra bình luận
    if (empty($_POST['comment'])) {
        $errors['comment'] = "Vui lòng điền đầy đủ bình luận.";
    } else {
        $comment = htmlspecialchars($_POST['comment']);
    }

    // Kiểm tra đánh giá sao
    if (empty($_POST['evaluate'])) {
        $errors['evaluate'] = "Vui lòng chọn đánh giá sao.";
    } else {
        $evaluate = filter_input(INPUT_POST, 'evaluate', FILTER_SANITIZE_NUMBER_INT);
    }

    // Kiểm tra và xử lý ảnh nếu có
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] != 4) {
        $target_dir = __DIR__ . "/image/";
        $IMAGE_TYPES = ['jpg', 'jpeg', 'png'];

        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $target_file = $target_dir . basename($imageName);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Kiểm tra loại tệp
            if (!in_array($imageFileType, $IMAGE_TYPES)) {
                $errors['images'][] = "$imageName có loại tệp không hợp lệ.";
            }

            // Kiểm tra kích thước tệp
            if ($_FILES['images']["size"][$key] > 1000000) {
                $errors['images'][] = "$imageName có kích thước quá lớn.";
            }

            // Nếu không có lỗi, tiến hành tải tệp lên
            if (empty($errors['images'])) {
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                    $imageNames[] = htmlspecialchars(basename($imageName));
                } else {
                    $errors['images'][] = "Có lỗi xảy ra khi tải $imageName.";
                }
            }
        }
    }

    if (empty($errors)) {
        $data = [
            'commentDate' => date('Y-m-d H:i:s'),
            'comment_text' => $comment,
            'idProduct' => $idProduct,
            'idUser' => $idUser,
            'evaluate' => $evaluate
        ];
    
        // Cập nhật bình luận
        $updateComment = $dbHelper->update("comments", $data, "idComment = $idComment");
    
        if ($updateComment) {
            // Nếu có ảnh, cập nhật từng ảnh vào bảng piccomment
            if (!empty($imageNames)) {
                foreach ($imageNames as $image) {
                    $dataPic = [
                        'idComment' => $idComment,
                        'namePicComment' => $image,
                    ];
                    
                    // Kiểm tra và thêm ảnh nếu ảnh chưa tồn tại
                    if (empty($picComment)) {
                        // Thêm ảnh mới vào bảng piccomment
                        $addPicComment = $dbHelper->insert('piccomment', $dataPic);
                        if (!$addPicComment) {
                            $errors['db'] = "Có lỗi xảy ra khi thêm hình ảnh vào cơ sở dữ liệu.";
                        }
                    } else {
                        // Cập nhật ảnh hiện có
                        $updatePicComment = $dbHelper->update("piccomment", $dataPic, "idComment = $idComment");
                        if (!$updatePicComment) {
                            $errors['db'] = "Có lỗi xảy ra khi cập nhật hình ảnh vào cơ sở dữ liệu.";
                        }
                    }
                }
            }
    
            // Chuyển hướng sau khi cập nhật thành công
            header('Location: ../detailProduct.php?id=' . $idProduct);
            exit();
        } else {
            $errors['db'] = "Có lỗi xảy ra khi cập nhật bình luận.";
        }
    }
    
}
?>
<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TdajtShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/1d3d4a43fd.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<style>
    .evaluate button.btn-evaluate:hover{
    color: rgb(247, 168, 0) !important;
}
.evaluate button.btn-evaluate.active{
    color: rgb(247, 168, 0) !important;
    border:none;
}
</style>
<body>
    <div class="container">
        <div class="row updateComment">
        <div class="col-md-4 offset-md-4  p-5 rounded mt-5" style="background-color: #f1f1f1;">
            <div class="comment-form">
                <!-- Form Cập Nhật Bình Luận -->
                <form id="update-comment-form" action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="comment_id" id="comment_id" value="<?= $listComments[0]['idComment'] ?>">

                    <div class="d-flex">
                <label for="name" class="form-label fw-bold text-secondary">Đánh giá</label>
                <div class="evaluate d-flex mx-3">
                    <input type="hidden" name="evaluate" id="selected-star">
                    <button type="button" class="btn d-flex btn-evaluate" style="border-right: 1px solid #ccc; border-radius: 0;,hover{background-color: orange;}" data-star="5">
                        <i class="fa-solid fa-star" id = "selected-star"></i>
                        <i class="fa-solid fa-star" id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                    </button>
                    <button type="button" class="btn d-flex btn-evaluate" style="border-right: 1px solid #ccc; border-radius: 0;" data-star="4">
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                    </button>
                    <button type="button" class="btn d-flex btn-evaluate" style="border-right: 1px solid #ccc; border-radius: 0;" data-star="3">
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                    </button>
                    <button type="button" class="btn d-flex btn-evaluate" style="border-right: 1px solid #ccc; border-radius: 0;" data-star="2">
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                    </button>
                    <button type="button" class="btn d-flex btn-evaluate" data-star="1">
                        <i class="fa-solid fa-star " id = "selected-star"></i>
                    </button>
                </div>
                </div>
                <div id="variant-error" class="text-danger mt-2"></div>

                    <!-- Phần thông tin bình luận -->
                    <label for="comment" class="form-label mt-3 fw-bold text-secondary">Bình luận</label><br>
                    <textarea class="form-control" id="comment-update" name="comment"
                        required><?= htmlspecialchars($listComments[0]['comment_text']) ?></textarea>
                    <?php if (isset($errors['comment'])): ?>
                    <div class="text-danger"><?= $errors['comment'] ?></div>
                    <?php endif; ?><br><br>

                    <!-- Phần thêm hình ảnh -->
                    <label for="images">Chỉnh sửa hình ảnh:</label><br>
                    <div class="listImage">
                        <?php if (!empty($picComment)): ?>
                        <?php foreach ($picComment as $image): ?>
                        <img src="./image/<?= htmlspecialchars($image); ?>" class="img-fluid m-3" width="100px"
                            height="100px" alt="comment image">
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <input type="file" id="images-update" name="images[]" class="form-control " multiple><br><br>

                    <?php if (isset($errors['images'])): ?>
                    <div class="text-danger">
                        <?php foreach ($errors['images'] as $error): ?>
                        <?= $error ?><br>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Nút gửi bình luận -->
                    <button type="submit" name="submit_update_comment" class="btn btn-success">Cập nhật bình
                        luận</button>
                        <a href="../detailProduct.php?id=<?= $idProduct ?>" class="btn btn-secondary" style = "margin-left: 450px; margin-top: -50px">Hủy</a>

                </form>
            </div>
        </div>
        </div>
    </div>

    <script>
document.addEventListener('DOMContentLoaded', () => {
    const buttonsAddComment = document.querySelectorAll('.btn-evaluate');
    const formComment = document.getElementById('update-comment-form');

    // Thêm sự kiện khi chọn đánh giá sao
    buttonsAddComment.forEach(button => {
        button.addEventListener('click', () => {
            const evaluate = button.getAttribute('data-star');
            document.getElementById('selected-star').value = evaluate;
            
            // Xóa class active của tất cả các nút và thêm vào nút được chọn
            buttonsAddComment.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');

            button.focus();
        });
    });

    // Thêm sự kiện kiểm tra khi form được submit
    formComment.addEventListener('submit', function(event) {
        const evaluate = document.getElementById('selected-star').value;
        if (!evaluate) {
            event.preventDefault();  // Ngăn form gửi đi
            document.getElementById('variant-error').textContent = 'Vui lòng chọn giá trị đánh giá sao.';
        }
    });
});


</script>
</body>

</html>