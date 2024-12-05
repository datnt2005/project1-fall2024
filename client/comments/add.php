<?php
include_once(__DIR__ . '/../DBUntil.php');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$dbHelper = new DBUntil();
$errors = [];
$idProduct = $_GET['id'];
$comment = "";
$evaluate = "";
$idUser = $_SESSION['idUser'] ?? null;
$imageNames = [];

// Kiểm tra nếu form bình luận được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra bình luận
    if (!isset($_POST['comment']) || empty($_POST['comment'])) {
        $errors['comment'] = "Vui lòng điền đầy đủ bình luận";
    } else {
        $comment = htmlspecialchars($_POST['comment']);
    }
    
    // Kiểm tra đánh giá sao
    if (!isset($_POST['evaluate']) || empty($_POST['evaluate'])) {
        $errors['evaluate'] = "Vui lòng chọn đánh giá sao.";
    } else {
        $evaluate = filter_input(INPUT_POST, 'evaluate', FILTER_SANITIZE_NUMBER_INT);
    }
    
    // Kiểm tra và xử lý ảnh nếu có
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] != 4) {
        $target_dir = __DIR__ . "/image/";
        $IMAGE_TYPES = array('jpg', 'jpeg', 'png');
        
        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $target_file = $target_dir . basename($imageName);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Kiểm tra loại tệp
            if (!in_array($imageFileType, $IMAGE_TYPES)) {
                $errors['images'][] = "$imageName có loại tệp không hợp lệ.";
            }

            // Kiểm tra kích thước tệp
            if ($_FILES['images']["size"][$key] > 1000000000) {
                $errors['images'][] = "$imageName có kích thước quá lớn.";
            }

            // Nếu không có lỗi, tiến hành tải tệp lên
            if (empty($errors)) {
                if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_file)) {
                    $imageNames[] = htmlspecialchars(basename($imageName));
                } else {
                    $errors['images'][] = "Có lỗi xảy ra khi tải $imageName.";
                }
            }
        }
    }
    
    // Nếu không có lỗi, tiến hành thêm bình luận vào database
    if (empty($errors)) {
        $data = [
            'commentDate' => date('Y-m-d H:i:s'),
            'comment_text' => $comment,
            'idProduct' => $idProduct,
            'idUser' => $idUser,
            'evaluate' => $evaluate
        ];
        
        // Thêm bình luận và lấy idComment
        $addComment = $dbHelper->insert('comments', $data);
        
        if ($addComment) {
            $idComment = $dbHelper->lastInsertId();
            // Nếu có ảnh, thêm từng ảnh vào bảng piccomment
            if (!empty($imageNames)) {
                foreach ($imageNames as $image) {
                    $dataPic = [
                        'idComment' => $idComment,
                        'namePicComment' => $image,
                    ];
                    $addPicComment = $dbHelper->insert('piccomment', $dataPic);
                    if (!$addPicComment) {
                        $errors['db'] = "Có lỗi xảy ra khi thêm hình ảnh vào cơ sở dữ liệu.";
                    }
                }
            }
            // Chuyển hướng sau khi bình luận thành công
            header('Location: ../detailProduct.php?id=' . $idProduct);
            exit();
        } else {
            $errors['db'] = "Có lỗi xảy ra khi thêm bình luận vào cơ sở dữ liệu.";
        }
    }
}

// Lấy danh sách hình ảnh bình luận

?>
<style>
    .evaluate button.btn-evaluate:hover{
    color: rgb(247, 168, 0) !important;
}
.evaluate button.btn-evaluate.active{
    color: rgb(247, 168, 0) !important;
    border:none;
}
</style>
<div class="addComment bg-light p-4 rounded">
    <div class="col-md-12">
        <hr>
        <div class="comment-form container">
            <form id="comment-form" action="./comments/add.php?id=<?= $idProduct ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" >
                <!-- Phần đánh giá sao -->
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
                <textarea class="form-control" id="comment" name="comment" placeholder="Nhập bình luận ..." required></textarea><br><br>
                
                <!-- Phần thêm hình ảnh -->
                <label for="images" class="form-label fw-bold text-secondary">Thêm hình ảnh</label><br>
                <input type="file" id="images" name="images[]" class="form-control w-50" multiple><br><br>

                <!-- Nút gửi bình luận -->
                <button type="submit" name="submit_comment" onclick = "addComment()" class="btn btn-success ">Gửi bình luận</button>
            </form>

            <?php
            // Hiển thị lỗi nếu có
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    if (is_array($error)) {
                        foreach ($error as $e) {
                            echo "<p class='text-danger'>$e</p>";
                        }
                    } else {
                        echo "<p class='text-danger'>$error</p>";
                    }
                }
            }
            ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttonsAddComment = document.querySelectorAll('.btn-evaluate');
    const formComment = document.getElementById('comment-form');

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
