//khi click vào ảnh sẽ mở ra input file
$("#current-avatar").on('click', function() {
    $("#avatar").click();
});

// khi chọn ảnh sẽ hiển thị ảnh đại diện mới
$("#avatar").on('change', function() {
    let input = this;
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $("#current-avatar").attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
});

$("#guest-account-update").on('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let urlUpdate = $(this).attr('action');
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: urlUpdate,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $("#update-btn").text('Updating...').prop('disabled', true);
        },

        success: function(response) {
            if (response.success) {
                toastr.success(response.message);
                if (response.data.avatar) {
                    $("#current-avatar").attr('src', response.data.avatar);
                }
            } else {
                toastr.error(response.message);
            }
        },
        error: function(xhr, status, error) {
            let error = xhr.responseJSON.errors;
            $.each(error, function(key, value) {
                toastr.error(value[0]);
            });
        },

        complete: function() {
            $("#update-btn").text('Update Profile').prop('disabled', false);
        }
    })
});
