<?php
    class Status {
        public function status($num) {
            switch($num) {
                case 1:
                    echo "<span class='text-success fw-bold fs-6'>Đang chờ xác nhận</span>";
                    break;
                case 2:
                    echo "Đã thanh toán, đang chờ xác nhận";
                    break;
                case 3:
                    echo "Shop đang chuẩn bị hàng";
                    break;
                case 4:
                    echo "Đơn hàng đang được giao đến bạn";
                    break;
                case 5:
                    echo "Giao hàng thành công";
                    break;
                case 6:
                    echo "<span class='text-danger fs-6'>Giao hàng thất bại</span>";
                    break;
                case 7:
                    echo "<span class='text-danger fw-bold fs-6'>Đơn hàng đã bị hủy</span>";    
                    break;
            }
        }
    }
?>