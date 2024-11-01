function confirmDelete(id) {
    Swal.fire({
        title: 'Bạn có chắc chắn muốn xóa?',
        text: "Hành động này không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Có, xóa nó!',
        cancelButtonText: 'Không, hủy!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'remove.php?id=' + id;
        }
    });
}
