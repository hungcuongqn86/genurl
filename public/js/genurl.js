$(window).on('hashchange', function () {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page === Number.NaN || page <= 0) {
            return false;
        } else {
            getData(page);
        }
    }
});

function getData(page) {
    $.ajax({
        url: '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#item-lists").empty().html(data);
        location.hash = page;
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
    });
}

function ValidURL(str) {
    var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
    return regex.test(str);
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
        $.ajax({
            url: '/shortener',
            type: "POST",
            dataType: 'json',
            data: {
                original_url: original_url
            },
            success: function (data) {
                console.log(data);
            }
        });
    });

    $(document).on('click', '.pagination a', function (event) {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();

        var myurl = $(this).attr('href');
        var page = $(this).attr('href').split('page=')[1];

        getData(page);
    });
});