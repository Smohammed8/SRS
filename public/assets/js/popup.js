showCancelMessage = (msg, ctxt) => {
    swal({
        title: "Are you sure?",
        text: msg,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc3545",
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        closeOnConfirm: false,
        closeOnCancel: false
    }, function(isConfirm) {
        if (isConfirm) {
            ctxt.submit();
            return true;
        } else {
            swal("Cancelled", "", "error");
            return false;
        }

    });
    return false
}
$('.load-modal').click(function() {
    var url = $(this).attr('data-value');
    $.post(url,
        function(data, status) {
            $("#temp").html(data)
            var calendar = $.calendars.instance('ethiopian','am');
            $('.dateAm').calendarsPicker({calendar: calendar});
            $('#temp .modal').modal('toggle');

        });
});
