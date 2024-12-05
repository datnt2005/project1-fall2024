<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<body>

    <?php include "./includes/header.php" ?>
    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến
                với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="container align-items-center">
                <div class="d-flex pt-2">
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ Giới thiệu</p>
                </div>
            </div>
        </div>

        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100"
                        style="height: 400px; object-fit: cover;" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100"
                        style="height: 400px; object-fit: cover;" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100"
                        style="height: 400px; object-fit: cover;" alt="Slide 3">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="container my-5">
            <div class="row align-item-center rounded" style="background-color: var(--color); height: 265px; ">
                <div class="col-md-4 p-3">
                    <img src="images/assorted-nuts-bazzini-500x900-min.png" alt="Hình ảnh 1" class="img-fluid w-100">
                </div>
                <div class="col-md-8 pt-5 text-white text-center ">
                    <h3 class=" mt-3 fs-2 font-italic">Đôi điều về Vua Hạt</h3>
                    <p class="w-75 mx-auto fs-5">Không dao to búa lớn, Vua Hạt đến với mong muốn trở thành địa chỉ uy
                        tín đáng tin cậy cho
                        người tiêu dùng trong nước khi có nhu cầu về những thực phẩm bổ sung dinh dưỡng, tốt cho sức
                        khỏe và bền vững.</p>
                </div>

            </div>

            <div class="row align-item-center rounded mt-5" style=" height: 265px; background-color: var(--color-header);">
                    <div class="col-md-8 pt-5 text-center " style="color: var(--color);">
                        <h3 class=" mt-3 fs-2 font-italic">Tầm nhìn của Vua Hạt</h3>
                        <p class="w-75 mx-auto fs-5">Nâng cao nhận thức của người Việt Nam trong việc phòng chống bệnh
                            tật bằng
                            chế độ ăn uống khoa học, bổ sung dinh dưỡng hợp lý và có lối sống lành mạnh.</p>
                    </div>
                <div class="col-md-4 p-3">
                    <img src="images/assorted-nuts-bazzini-500x900-min.png" alt="Hình ảnh 2" class="img-fluid">
                </div>
            </div>

            <div class="row mt-5 align-item-center rounded" style="background-color: var(--color); height: 265px; ">
                <div class="col-md-4 p-3">
                    <img src="images/assorted-nuts-bazzini-500x900-min.png" alt="Hình ảnh 1" class="img-fluid w-100">
                </div>
                <div class="col-md-8 pt-5 text-white text-center ">
                    <h3 class=" mt-3 fs-2 font-italic">Sứ mệnh của chúng tôi</h3>
                    <p class="w-75 mx-auto fs-5">Không dao to búa lớn, Vua Hạt đến với mong muốn trở thành địa chỉ uy
                        tín đáng tin cậy cho
                        người tiêu dùng trong nước khi có nhu cầu về những thực phẩm bổ sung dinh dưỡng, tốt cho sức
                        khỏe và bền vững.</p>
                </div>

            </div>
        </div>
    </main>

<?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>
</body>

</html>