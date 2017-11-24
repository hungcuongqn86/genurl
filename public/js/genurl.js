function setupMenu() {
    $('.edit-url').unbind('click').click(function () {
        getDetail($(this).closest( "tr" ).attr('id'));
    });

    $('a.a-analytics').unbind('click').click(function (event) {
        event.preventDefault();
        getAnalytics($(this).attr('href'));
    });
}

function getData(page) {
    showLoading();
    $.ajax({
        url: '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#analytics-conten").hide();
        $("#list-conten").show();
        $("#item-lists").empty().html(data);
        history.pushState({}, null, '?page=' + page);
        setupMenu();
        hideLoading();
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading();
    });
}

function getDetail(id) {
    showLoading();
    $.ajax({
        url: '/get-url/' + id,
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
                    showLoading();
                    $.ajax({
                        url: '/update-url/' + id,
                        type: "PUT",
                        dataType: 'json',
                        data: {
                            uri: $('#uri').val(),
                            original: original_url
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
                }
            });
            $('#shorten').hide();
            $('#myModal').modal('show');
        } else {
            alert(data.message);
        }
        hideLoading();
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading();
    });
}

function getAnalytics(url) {
    showLoading();
    $.ajax({
        url: url,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#list-conten").hide();
        $("#analytics-conten").empty().html(data).show();
        history.pushState({}, null, url);
        btnBack();
        hideLoading();
    }).fail(function (jqXHR, ajaxOptions, thrownError) {
        alert('No response from server');
        hideLoading();
    });
}

function btnBack() {
    $('.btn-back').unbind('click').click(function () {

    });
}

function showLoading() {
    $('#overlay').show();
}

function hideLoading() {
    $('#overlay').hide();
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

$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    setupMenu();

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
            }
        });
        $('#myModal').modal('show');
    });

    $('#automatically').unbind('click').click(function () {
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

    $(document).on('click', '.pagination a', function (event) {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        getData(page);
    });
});