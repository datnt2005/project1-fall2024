document.addEventListener('DOMContentLoaded', function() {
    var carouselElement = document.querySelector('#carouselExampleIndicators');
    var carousel = new bootstrap.Carousel(carouselElement, {
        interval: 3000, 
        ride: 'carousel' 
    });
});
function alertRemove(event, content) {
    event.preventDefault(); // Prevent default behavior of the <a> tag
    let targetUrl = event.target.href; // Store target URL

    let logout = document.getElementById('logout');
    let alertHtml = `
    <div class="container-fluid position_alert" id="alertBox">
        <div class="bg-alert d-flex flex-column align-items-center">
            <div class="content_alert">
                <h3 class="text-center">Bạn có chắc chắn muốn đăng xuất "<b>${content}</b>"?</h3>
            </div>
            <div class="icon-warning">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div class="btn-option d-flex justify-content-between mt-3">
                <button class="btn btn-secondary" onclick="abort()">Hủy</button>
                <button class="btn btn-danger" onclick="remove('${targetUrl}')">Đăng Xuất</button>
            </div>
        </div>
    </div>
`;
    logout.innerHTML = alertHtml;
}

function abort() {
    let alertBox = document.getElementById('alertBox');
    if (alertBox) {
        alertBox.remove();
    }
}

function remove(targetUrl) {
    let alertBox = document.getElementById('alertBox');
    if (alertBox) {
        alertBox.remove();
    }
    // Redirect to the target URL
    window.location.href = targetUrl;
}

