<?php
session_start();
include "./DBUntil.php";
$dbHelper = new DBUntil();
?>
<!DOCTYPE html>
<html lang="en">

<?php include "./includes/head.php" ?>

<body >
    <?php include "./includes/header.php" ?>

    <main>
        <div class="d-flex justify-content-center align-items-center header-outstanding">
            <p class="link-cate m-1 fs-5 text-white">Chào mừng bạn đến
                với thế giới các loại hạt của chúng tôi!</p>
        </div>
        <div class="page">
            <div class="container align-items-center">
                <div class="d-flex pt-2">
                    <p class=" m-1 fs-5 fw-bold">Trang chủ/ Liên hệ</p>
                </div>
            </div> 
        </div>

        <section id="contact" class="mb-5">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
    
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Slide 1">
                    </div>
                    <div class="carousel-item">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Slide 2">
                    </div>
                    <div class="carousel-item">
                        <img src="./images/assorted-nuts-bazzini-500x900-min.png" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Slide 3">
                    </div>
                </div>
    
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6 mt-5">
                        <form action="" method="post" class=" rounded p-5 form-contact">
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Nhập Tên</label>
                                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Nhập tên ...">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label ">Nhập Email</label>
                                <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="Nhập email ...">
                            </div>
                            <div class="mb-3">
                                <label for="exampleFormControlTextarea1" class="form-label">Ghi Chú</label>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="Ghi chú ..."></textarea>
                            </div>
                            <div class="">
                                <button type="submit" name="contact" class="btn contact fw-bold">Gửi thông tin</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="address">
                            <iframe class="rounded" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d1598.3397529035565!2d108.04564294549449!3d12.68033231396019!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31721d7f7bf62dad%3A0x4bab59269da695d7!2zVMaw4bujbmcgxJDDoGkgQ2hp4bq_biB0aOG6r25nIEJ1w7RuIE1hIFRodeG7mXQ!5e1!3m2!1svi!2s!4v1730447801931!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>"
                        </div>
                        
                    </div>
                </div>
                <div class="row text-center mt-5">
                    <div class="col-md-4 ">
                        <div class="home">
                            <div class="icon">
                                <i class="fa-solid fa-house fs-1"></i>
                            </div>
                            <p class="fw-bold fs-4">Địa chỉ</p>
                            <p>Phan Chu Trinh , TP Buôn Mê Thuột , Đăk Lăk</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="phone">
                            <div class="icon">
                                <i class="fa-solid fa-phone fs-1"></i>
                            </div>
                            <p class="fw-bold fs-4">Hotline</p>
                            <p>0987.654.321</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="time">
                            <div class="icon">
                                <i class="fa-solid fa-clock fs-1"></i>
                            </div>
                            <p class="fw-bold fs-4">Giờ mở cửa</p>
                            <p >8:00 - 18:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
       
    </main>

<?php include "./includes/footer.php" ?>
    <script src="./js/script.js"></script>
</body>

</html>