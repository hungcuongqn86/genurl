var page = 1;

function setupMenu() {
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

    $(document).on('click', '.pagination a', function (event) {
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        event.preventDefault();
        page = $(this).attr('href').split('page=')[1];
        getData(page);
    });

    $('.edit-url').unbind('click').click(function () {
        getUrlDetail($(this).closest("tr").attr('id'));
    });

    $('a.a-shortlink').unbind('click').click(function (event) {
        event.preventDefault();
        getUrlDetail($(this).closest("tr").attr('id'));
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

    $('#create-new').unbind('click').click(function () {
        $('#genUrlModal .modal-title').text('Create shorten URL');
        $("#genUrlModal .val-alert").hide();

        $('#genUrlModal #original_url').val('');
        $('#genUrlModal #title').val('');
        $('#genUrlModal #description').val('');
        $('#genUrlModal #image').val('');
        console.log(12121);
        $('#genUrlModal').modal('show');
    });

    $("form#genurlFrm").unbind('submit').submit(function (e) {
        e.preventDefault();
        if (g_validate()) {
            var formData = new FormData(this);
            showLoading($('#genurlFrm'));
            $.ajax({
                url: rooturl + '/shortener',
                type: "POST",
                data: formData,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    $('#genUrlModal').modal('hide');
                    hideLoading($('#genurlFrm'));
                    getData('1');
                },
                error: function (error) {
                    $('#genUrlModal').modal('hide');
                    if (error.responseJSON && error.responseJSON.message) {
                        alert(error.responseJSON.message);
                    } else {
                        alert(error.statusText);
                    }
                    hideLoading($('#genurlFrm'));
                }
            });
        }
    });

    $('#shorten').show().unbind('click').click(function () {
        $('form#genurlFrm').trigger('submit');
    });

    $("form#updateurlFrm").unbind('submit').submit(function (e) {
        e.preventDefault();
        if (u_validate()) {
            var formData = new FormData(this);
            var id = $('#id').val();

            showLoading($('#updateurlFrm'));
            $.ajax({
                url: rooturl + '/update-url/' + id,
                type: "POST",
                data: formData,
                enctype: 'multipart/form-data',
                processData: false,
                contentType: false,
                cache: false,
                success: function (data) {
                    hideLoading($('#updateurlFrm'));
                    getUrlDetail(id);
                },
                error: function (error) {
                    if (error.responseJSON && error.responseJSON.message) {
                        alert(error.responseJSON.message);
                    } else {
                        alert(error.statusText);
                    }
                    hideLoading($('#updateurlFrm'));
                }
            });
        }
    });

    $('#updateUrlBtn').unbind('click').click(function () {
        $('form#updateurlFrm').trigger('submit');
    });

    $('.delete-url').unbind('click').click(function () {
        var id = $(this).closest("tr").attr('id');

        showLoading($('#list-conten'));
        $.ajax({
            url: rooturl + '/delete-url/' + id,
            type: "POST",
            dataType: 'json',
            data: {},
            success: function (data) {
                hideLoading($('#list-conten'));
                getData('1');
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert(error.statusText);
                }
                hideLoading($('#list-conten'));
            }
        });
    });

    $('.delete-link').unbind('click').click(function () {
        var url_id = $('#id').val();
        var id = $(this).closest("tr").attr('id');

        showLoading($('#updateurlFrm'));
        $.ajax({
            url: rooturl + '/delete-link/' + id,
            type: "POST",
            dataType: 'json',
            data: {},
            success: function (data) {
                hideLoading($('#updateurlFrm'));
                getUrlDetail(url_id);
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert(error.statusText);
                }
                hideLoading($('#updateurlFrm'));
            }
        });
    });

    $('#addLink').unbind('click').click(function () {
        var id = $('#id').val();
        var count = $('#count').val();
        if (count < 1) {
            return false;
        }
        showLoading($('#updateurlFrm'));
        $.ajax({
            url: rooturl + '/add-link/' + id,
            type: "POST",
            dataType: 'json',
            data: {count: count},
            success: function (data) {
                hideLoading($('#updateurlFrm'));
                getUrlDetail(id);
            },
            error: function (error) {
                if (error.responseJSON && error.responseJSON.message) {
                    alert(error.responseJSON.message);
                } else {
                    alert(error.statusText);
                }
                hideLoading($('#updateurlFrm'));
            }
        });
    });

    pagination();
    btnBack();
}

function getData(page) {
    showLoading($('#list-conten'));
    $.ajax({
        url: rooturl + '?page=' + page,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#detail-conten").hide();
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

function getUrlDetail(id) {
    showLoading($('#list-conten'));
    var url = rooturl + '/get-url/' + id;
    $.ajax({
        url: url,
        type: "get",
        datatype: "html"
    }).done(function (data) {
        $("#list-conten").hide();
        $("#analytics-conten").hide();
        $("#detail-conten").empty().html(data).show();
        history.pushState({}, null, url);
        btnBack();
        setupMenu();
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
        $("#detail-conten").hide();
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

function g_validate() {
    if (!ValidURL($('#genurlFrm #original_url').val())) {
        $('#genurlFrm #original_url_alert').show();
        $('#genurlFrm #original_url').focus();
        return false;
    }
    $('#genurlFrm #original_url_alert').hide();
    return true;
}

function u_validate() {
    if (!ValidURL($('#updateurlFrm #original_url').val())) {
        $('#updateurlFrm #original_url_alert').show();
        $('#updateurlFrm #original_url').focus();
        return false;
    }
    $('#updateurlFrm #original_url_alert').hide();
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
    setupMenu();
});
