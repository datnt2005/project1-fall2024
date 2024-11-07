document.addEventListener('DOMContentLoaded', function() {
    var carouselElement = document.querySelector('#carouselExampleIndicators');
    var carousel = new bootstrap.Carousel(carouselElement, {
        interval: 3000, 
        ride: 'carousel' 
    });
});


    // Get the available quantity from the hidden input field
    var availableQuantity = parseInt(document.getElementById("availableQuantity").value);
    var quantityInput = document.getElementById("quantity");

    function decreaseQuantity() {
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity > 1) {
            quantityInput.value = currentQuantity - 1;
        }
    }

    function increaseQuantity() {
        var currentQuantity = parseInt(quantityInput.value);
        if (currentQuantity < availableQuantity) {
            quantityInput.value = currentQuantity + 1;
        } else {
            alert("Số lượng không thể vượt quá số lượng tồn kho.");
        }
    }

