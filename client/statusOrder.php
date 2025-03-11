<?php
    class Status {
        public function status($num) {
            switch($num) {
                case 1:
                    echo "<span class='text-warning fw-bold fs-6'>Đang chờ xác nhận</span>";
                    break;
                case 2:
                    echo "<span class='text-info fw-bold fs-6'>Đã thanh toán, đang chờ xác nhận</span>";
                    break;
                case 3:
                    echo "<span class='text-primary fw-bold fs-6'>Shop đang chuẩn bị hàng</span>";
                    break;
                case 4:
                    echo "<span class='text-success fw-bold fs-6'>Đơn hàng đang được giao đến bạn</span>";
                    break;
                case 5:
                    echo "<span class='text-success fw-bold fs-6'>Giao hàng thành công</span>";
                    break;
                case 6:
                    echo "<span class='text-danger fw-bold fs-6'>Giao hàng thất bại</span>";
                    break;
                case 7:
                    echo "<span class='text-danger fw-bold fs-6'>Đơn hàng đã bị hủy</span>";    
                    break;
            }
        }
    }
?>