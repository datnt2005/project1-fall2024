$(document).ready(function() {
    // Lắng nghe sự thay đổi của tỉnh
    $('#province').on('change', function() {
        var province_id = $(this).val();

        if (province_id) {
            $.ajax({
                url: 'ajax_get_district.php', // Đường dẫn file PHP
                method: 'GET',
                data: { province_id: province_id },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.log(response.error);
                        return;
                    }

                    // Reset danh sách huyện và xã
                    $('#district').empty().append('<option value="">Chọn một Quận/huyện</option>');
                    $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');

                    // Thêm huyện vào dropdown
                    $.each(response, function(i, district) {
                        $('#district').append(`<option value="${district.id}">${district.name}</option>`);
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Lỗi AJAX: ' + errorThrown);
                }
            });
        } else {
            // Nếu không chọn tỉnh, reset danh sách huyện và xã
            $('#district').empty().append('<option value="">Chọn một Quận/huyện</option>');
            $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');
        }
    });

    // Lắng nghe sự thay đổi của huyện
    $('#district').on('change', function() {
        var district_id = $(this).val();

        if (district_id) {
            $.ajax({
                url: 'ajax_get_wards.php',
                method: 'GET',
                data: { district_id: district_id },
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        console.log(response.error);
                        return;
                    }

                    $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');

                    $.each(response, function(i, ward) {
                        $('#wards').append(`<option value="${ward.id}">${ward.name}</option>`);
                    });
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.log('Lỗi AJAX: ' + errorThrown);
                }
            });
        } else {
            $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');
        }
    });
});
