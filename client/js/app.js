$(document).ready(function() {
  // Lắng nghe sự thay đổi ở "province"
  $('#province').on('change', function() {
      var province_id = $(this).val();
      if (province_id) {
          // Gửi yêu cầu AJAX để lấy danh sách huyện
          $.ajax({
              url: './project1-fall2024/client/ajax_get_district.php',  // URL để lấy danh sách huyện
              method: 'GET',
              dataType: 'json',
              data: { province_id: province_id },
              success: function(data) {
                  // Xóa các tùy chọn cũ trong select district
                  $('#district').empty().append('<option value="">Chọn một Quận/huyện</option>');

                  // Thêm các quận/huyện mới vào select district
                  $.each(data, function(i, district) {
                      $('#district').append($('<option>', {
                          value: district.id,
                          text: district.name
                      }));
                  });

                  // Xóa các tùy chọn cũ trong select wards
                  $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');
              },
              error: function(xhr, textStatus, errorThrown) {
                  console.log('Error: ' + errorThrown);
              }
          });
      } else {
          // Xóa tất cả các lựa chọn nếu không có tỉnh
          $('#district').empty().append('<option value="">Chọn một Quận/huyện</option>');
          $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');
      }
  });

  // Lắng nghe sự thay đổi ở "district"
  $('#district').on('change', function() {
      var district_id = $(this).val();
      if (district_id) {
          // Gửi yêu cầu AJAX để lấy danh sách xã
          $.ajax({
              url: './project1-fall2024/client/ajax_get_wards.php', // URL để lấy danh sách xã
              method: 'GET',
              dataType: 'json',
              data: { district_id: district_id },
              success: function(data) {
                  // Xóa các tùy chọn cũ trong select wards
                  $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');

                  // Thêm các xã/phường vào select wards
                  $.each(data, function(i, ward) {
                      $('#wards').append($('<option>', {
                          value: ward.id,
                          text: ward.name
                      }));
                  });
              },
              error: function(xhr, textStatus, errorThrown) {
                  console.log('Error: ' + errorThrown);
              }
          });
      } else {
          // Xóa tất cả các lựa chọn nếu không có huyện
          $('#wards').empty().append('<option value="">Chọn một xã/phường</option>');
      }
  });
});
