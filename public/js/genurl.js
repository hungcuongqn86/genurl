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

var ClipboardHelper = {
    copyText: function (text) {
        var $tempInput = $("<textarea>");
        $("body").append($tempInput);
        $tempInput.val(text).select();
        document.execCommand("copy");
        $tempInput.remove();
    }
};

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
    var datarow = null;
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // right click menu
    $('.data-row').contextmenu(function () {
        datarow = this;
    }).contextPopup({
        items: [
            {
                label: 'Copy short URL',
                action: function () {
                    ClipboardHelper.copyText($(datarow).attr('short-url'));
                }
            },
            {
                label: 'Edit URL',
                action: function () {
                    alert('Edit URL')
                }
            }
        ]
    });

    $('#create-new').click(function () {
        $(".val-alert").hide();
        $('#uri').val('');
        $('#original_url').val('');
        $('#myModal').modal('show');
        $('#myModal').on('shown', function () {
            $("#uri").focus();
        })
    });

    $('#automatically').click(function () {
        showLoading();
        $.ajax({
            url: '/auto-uri',
            type: "GET",
            success: function (data) {
                if (!data.error) {
                    $('#uri').val(data.data);
                } else {
                    alert(data.message);
                }
                hideLoading();
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert(error.statusText);
                }
                hideLoading();
            }
        });
    });

    $('#shorten').click(function () {
        if (!$('#uri').val()) {
            $('#uri_alert').show();
            $('#uri').focus();
            return false;
        }
        $('#uri_alert').hide();

        if (!ValidURL($('#original_url').val())) {
            $('#original_url_alert').show();
            $('#original_url').focus();
            return false;
        }
        $('#original_url_alert').hide();

        var original_url = decodeURIComponent($('#original_url').val());
        showLoading();
        $.ajax({
            url: '/shortener',
            type: "POST",
            dataType: 'json',
            data: {
                uri: $('#uri').val(),
                original_url: original_url
            },
            success: function (data) {
                $('#myModal').modal('hide');
                getData('1');
            },
            error: function (error) {
                $('#myModal').modal('hide');
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert(error.statusText);
                }
                hideLoading();
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