function getData(page) {
    showLoading();
    $.ajax({
        url: '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#item-lists").empty().html(data);
        history.pushState({}, null, '?page=' + page);
        hideLoading();
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading();
    });
}

function ValidURL(str) {
    var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
    return regex.test(str);
}

function showLoading() {
    $('#overlay').show();
}

function hideLoading() {
    $('#overlay').hide();
}

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#shorten').click(function () {
        if (!ValidURL($('#original_url').val())) {
            $('#div-alert').show();
            return false;
        }
        $('#div-alert').hide();
        var original_url = decodeURIComponent($('#original_url').val());
        showLoading();
        $.ajax({
            url: '/shortener',
            type: "POST",
            dataType: 'json',
            data: {
                original_url: original_url
            },
            success: function (data) {
                getData('1');
            }
        });
    });

    $(document).on('click', '.pagination a', function (event) {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
});