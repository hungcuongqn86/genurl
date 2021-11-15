var page = 1;

function setupMenu() {
    $('.edit-url').unbind('click').click(function () {
        getDetail($(this).closest("tr").attr('id'));
    });

    $('a.a-analytics').unbind('click').click(function (event) {
        event.preventDefault();
        getAnalytics($(this).attr('href'));
    });

    $('#timeframe').unbind('change').change(function () {
        getAnalytics($(this).val());
    });

    $('.copy-short-url').unbind('click').click(function (event) {
        ClipboardHelper.copyText($(this).attr('short-url'));
    });
}

function getData(page) {
    showLoading($('#list-conten'));
    $.ajax({
        url: rooturl + '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#analytics-conten").hide();
        $("#list-conten").show();
        $("#item-lists").empty().html(data);
        pagination();
        history.pushState({}, null, rooturl + '?page=' + page);
        setupMenu();
        hideLoading($('#list-conten'));
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading($('#list-conten'));
    });
}

function getDetail(id) {
    showLoading($('#list-conten'));
    $.ajax({
        url: rooturl + '/get-url/' + id,
        type: "get",
        datatype: "json"
    }).done(function (data) {
        if (!data.error) {
            $('.modal-title').text('Update URL');
            $(".val-alert").hide();
            $('#uri').val(data.data.uri);
            $('#original_url').val(data.data.original);
            $('#update-url').show().unbind('click').click(function () {
                if (validate()) {
                    var original_url = decodeURIComponent($('#original_url').val());
                    showLoading($('#myModal-modal-content'));
                    $.ajax({
                        url: rooturl + '/update-url/' + id,
                        type: "PUT",
                        dataType: 'json',
                        data: {
                            uri: $('#uri').val(),
                            original: original_url
                        },
                        success: function (data) {
                            $('#myModal').modal('hide');
                            hideLoading($('#myModal-modal-content'));
                            getData(page);
                        },
                        error: function (error) {
                            $('#myModal').modal('hide');
                            if (error.responseJSON && error.responseJSON.message) {
                                alert(error.responseJSON.message);
                            } else {
                                alert(error.statusText);
                            }
                            hideLoading($('#myModal-modal-content'));
                        }
                    });
                }
            });
            $('#shorten').hide();
            $('#myModal').modal('show');
        } else {
            alert(data.message);
        }
        hideLoading($('#list-conten'));
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading($('#list-conten'));
    });
}

function getAnalytics(url) {
    showLoading($('#list-conten'));
    $.ajax({
        url: url,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#list-conten").hide();
        $("#analytics-conten").empty().html(data).show();
        history.pushState({}, null, url);
        btnBack();
        setupMenu();
        hideLoading($('#list-conten'));
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading($('#list-conten'));
    });
}

function btnBack() {
    $('.btn-back').unbind('click').click(function () {
        getData(page);
    });
}

function showLoading(div) {
    if ($(div).find('.data-loading').length === 0) {
        $(div).addClass('dataload').append('<div class="data-loading"><span style="margin: auto;"><div id="loader"></div></span></div>');
    }
}

function hideLoading(div) {
    $(div).find('.data-loading').remove();
}

function validate() {
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
    return true;
}

function ValidURL(str) {
    var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
    return regex.test(str);
}

function pagination() {
    $('ul.pagination li.active')
        .prev().addClass('show-mobile')
        .prev().addClass('show-mobile');
    $('ul.pagination li.active')
        .next().addClass('show-mobile')
        .next().addClass('show-mobile');
    $('ul.pagination')
        .find('li:first-child, li:last-child, li.active')
        .addClass('show-mobile');
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

$(document).ready(function () {
    pagination();
    setupMenu();
    btnBack();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#genUrlModal').on('show.bs.modal', function (e) {
        $('#original_url').focus();
    });

    $('#myModal').on('show.bs.modal', function (e) {
        $('#uri').focus();
    });

    $('#create-new').unbind('click').click(function () {
        $('.modal-title').text('Create shorten URL');
        $(".val-alert").hide();
        $('#uri').val('');
        $('#original_url').val('');
        $('#update-url').hide();
        $('#shorten').show().unbind('click').click(function () {
            if (validate()) {
                var original_url = decodeURIComponent($('#original_url').val());
                showLoading($('#myModal-modal-content'));
                $.ajax({
                    url: rooturl + '/shortener',
                    type: "POST",
                    dataType: 'json',
                    data: {
                        uri: $('#uri').val(),
                        original_url: original_url
                    },
                    success: function (data) {
                        $('#myModal').modal('hide');
                        hideLoading($('#myModal-modal-content'));
                        getData('1');
                    },
                    error: function (error) {
                        $('#myModal').modal('hide');
                        if (error.responseJSON && error.responseJSON.message) {
                            alert(error.responseJSON.message);
                        } else {
                            alert(error.statusText);
                        }
                        hideLoading($('#myModal-modal-content'));
                    }
                });
            }
        });
        $('#genUrlModal').modal('show');
    });

    $('#automatically').unbind('click').click(function () {
        showLoading($('#myModal-modal-content'));
        $.ajax({
            url: rooturl + '/auto-uri',
            type: "GET",
            success: function (data) {
                if (!data.error) {
                    $('#uri').val(data.data);
                } else {
                    alert(data.message);
                }
                hideLoading($('#myModal-modal-content'));
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert(error.statusText);
                }
                hideLoading($('#myModal-modal-content'));
            }
        });
    });

    $(document).on('click', '.pagination a', function (event) {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
});
