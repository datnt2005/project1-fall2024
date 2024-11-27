<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<?php
session_start();

// Hủy tất cả các session
session_unset();
session_destroy();

// Gửi thông báo đăng xuất thành công bằng JavaScript
echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Đăng xuất thành công!',
                text: 'Hẹn gặp lại bạn lần sau.',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'custom-ok-button'
                }
            }).then(() => {
                window.location.href = 'login.php'; // Chuyển hướng đến trang đăng nhập
            });
        });
      </script>";
?>
<style>
    .custom-ok-button {
        background-color: #69BA31 !important;
        color: white !important;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        font-size: 16px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .custom-ok-button:hover {
        background-color: #57a228 !important;
    }
</style>
