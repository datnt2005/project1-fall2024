<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>

    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/1d3d4a43fd.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: linear-gradient(rgba(117, 245, 25, 0.6), rgba(105, 186, 49, 0.6)), url('./images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
    </style>
</head>

<body style="background-color: var(--color); ">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-5" style="width: 40rem; background-color: var(--color-header);">
            <img src="./images/logo_du_an_1 2.png" class="d-block mx-auto" alt="" style="width: 180px; height: 130px;">
            <h3 class="text-center mb-2">Quên mật khẩu</h3>
            <form>
                <div class="form-group">
                    <label for="email"></label>
                    <input type="email" class="form-control" id="email" placeholder="Nhập email" required>
                </div>
                <button id="submit" style="margin-top: 20px; background-color: var(--color); color: var(--color-main);"
                    type="submit" class="btn form-control">Quên mật khẩu</button>
                    <a href="login.html" class="text-decoration-none text-success">Bạn đã có tài khoản</a>
            </form>
        </div>
    </div>
</body>

</html>