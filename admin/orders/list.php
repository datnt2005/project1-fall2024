<?php
    include "../../client/DBUntil.php";
    include "../../client/statusOrder.php";
    session_start();
    $dbHelper = new DBUntil();
    $status = new Status();
    function formatCurrencyVND($number) {
        return number_format($number, 0, ',', '.'). 'đ';
    }
    // Kiểm tra xem người dùng đã tìm kiếm chưa
    $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : "";    
    $listOrder = [];

    // Thực hiện tìm kiếm nếu có từ khóa, nếu không thì lấy tất cả danh mục
    if (!empty($searchTerm)) {
        $listOrder = $dbHelper->select("SELECT ord.*, dadr.*, dor.*, w.name AS nameWard, d.name AS nameDistrict, p.name AS nameProvince,
                                        GROUP_CONCAT(DISTINCT CONCAT(prd.nameProduct, ' (', dor.sizeOrder, ')') SEPARATOR ', ') AS products
                                        FROM orders ord
                                        INNER JOIN detailorder dor ON ord.idOrder = dor.idOrder
                                        INNER JOIN products prd ON dor.idProduct = prd.idProduct
                                        INNER JOIN detail_address dadr ON ord.idAddress = dadr.detail_id
                                        INNER JOIN province p ON dadr.province_id = p.province_id 
                                        INNER JOIN district d ON dadr.district_id = d.district_id 
                                        INNER JOIN wards w ON dadr.ward_id = w.wards_id
                                        WHERE prd.nameProduct LIKE ? OR ? = ''
                                        GROUP BY ord.idOrder", array('%' . $searchTerm . '%', $searchTerm));
    
    } else {
        $listOrder = $dbHelper->select("SELECT ord.*, dadr.*, dor.*, w.name AS nameWard, d.name AS nameDistrict, p.name AS nameProvince,
                                        GROUP_CONCAT(DISTINCT CONCAT(prd.nameProduct, ' (', dor.sizeOrder, ')') SEPARATOR ', ') AS products
                                        FROM orders ord
                                        INNER JOIN detailorder dor ON ord.idOrder = dor.idOrder
                                        INNER JOIN products prd ON dor.idProduct = prd.idProduct
                                        INNER JOIN detail_address dadr ON ord.idAddress = dadr.detail_id
                                        INNER JOIN province p ON dadr.province_id = p.province_id 
                                        INNER JOIN district d ON dadr.district_id = d.district_id 
                                        INNER JOIN wards w ON dadr.ward_id = w.wards_id
                                        GROUP BY ord.idOrder");
    }
       
    // var_dump($listOrder);
?>


<!DOCTYPE html>
<html lang="en">
<?php include "../includes/head.php" ?>

<body>
    <?php
if (isset($_SESSION['success'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Thành công!',
            text: '{$_SESSION['success']}',
            showConfirmButton: false,
            timer: 1500
        });
    </script>";
    unset($_SESSION['success']); // Xóa thông báo sau khi hiển thị
}
?>
    <div id="wrapper">
        <?php include "../includes/sidebar.php" ?>
        <!-- Page Content -->
        <div id="content">
            <?php include "../includes/nav.php" ?>
            <!-- Main Content -->
            <div class="container-fluid">
                <!-- Place your content here -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Đơn hàng</h3>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mt-4">
                                    <div class="search-items">
                                        <form method="GET">
                                            <input class="input-search mb-3" type="search" name="search" id="search"
                                                placeholder="Tìm kiếm" style="height: 35px;">
                                            <button type="submit" class="btn btn-dark bg-gradient text-white"
                                                style="height: 35px">Search</button>
                                        </form>
                                    </div>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Sản phẩm</th>
                                            <th>Người mua</th>
                                            <th>Số lượng</th>
                                            <th>Tổng tiền</th>
                                            <th>Thời gian</th>
                                            <th>Ghi chú</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($listOrder as $orders) { ?>
                                        <tr class="align-middle">
                                            <td><b><?php echo $orders['products'] ?></b></td>
                                            <td><?php
                                                    echo $orders['name'] . " <br> " . $orders['phone'] ." - ".$orders['email']. "<br>" . $orders['village'] . ", " . $orders['nameWard'] . ", " . $orders['nameDistrict'] . ", " . $orders['nameProvince'];
                                                ?></td>
                                            <td><?php echo $orders['quantityOrder']?></td>
                                            <td><?php echo formatCurrencyVND($orders['totalPrice'])?></td>
                                            <td><?php echo $orders['dateOrder']?></td>
                                            <td><?php 
                                                    if($orders['noteOrder'] == null){
                                                        echo "Null";
                                                    }else{
                                                        echo $orders['noteOrder'];
                                                    }?></td>
                                            <td><?php
                                                    $status->status($orders['statusOrder']);?></td>
                                            <td>
                                                <?php if($orders['statusOrder'] == 1): ?>
                                                <div class="action">
                                                    <a href="success.php?id=<?php echo $orders['idOrder']; ?>"
                                                        class="update_product text-decoration-none fw-bold mx-2">Xác
                                                        nhận</a>
                                                        <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="deleteSuccess('<?php echo $orders['idOrder'] ?>')">Từ chối</a>
                                            </td>
                                                </div>
                                                <?php elseif($orders['statusOrder'] == 7):?>
                                                <div class="action">
                                                    <a href="javascript:void(0);"
                                                        class="remove_categories fw-bold text-danger text-decoration-none"
                                                        onclick="confirmDelete('<?php echo $orders['idOrder'] ?>')">Xóa</a>
                                            </td>
                            </div>
                            <?php elseif ($orders['statusOrder'] == 6 || $orders['statusOrder'] == 2 || $orders['statusOrder'] == 3 || $orders['statusOrder'] == 4 || $orders['statusOrder'] == 5): ?>
                            <div class="action">
                                <a href="addStatus.php?id=<?php echo $orders['idOrder']?>"
                                    class="remove_users fw-bold text-primary text-decoration-none mx-2"><i class="fs-5 fa-solid fa-pen-nib"></i></a>
                                <a href="javascript:void(0);"
                                    class="remove_categories fw-bold text-danger text-decoration-none"
                                    onclick="confirmDelete('<?php echo $orders['idOrder'] ?>')"><i class="fs-5 fa-solid fa-trash-can"></i></a>
                                </td>
                            </div>
                            <?php endif; ?>
                            </td>
                            </tr>
                            <?php }?>
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
<script>
    function deleteSuccess(id) {
    Swal.fire({
        title: 'Bạn có chắc chắn muốn từ chối đơn này?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Có, từ chối!',
        cancelButtonText: 'Không, hủy!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'cancel.php?id=' + id;
        }
    });
}
</script>
<script src="../js/script.js"></script>
</html>