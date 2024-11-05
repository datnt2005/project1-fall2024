document.addEventListener('DOMContentLoaded', function() {
    var carouselElement = document.querySelector('#carouselExampleIndicators');
    var carousel = new bootstrap.Carousel(carouselElement, {
        interval: 3000, 
        ride: 'carousel' 
    });
});
