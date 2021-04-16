$(document).ready(function () {
    $.get("https://api.khan-wheels.com/public/api/v1/web/getCategories?parent=1", {})
        .done(function (res) {
            $('.makeSelect').html('<option value="0">Select Make</option>');
            $("#wheel_list").html('');
            res.forEach(function (item, index, array) {
                $('.makeSelect').append(`<option value="${item['id']}">${item['text']}</option>`);
            })

        });
    $(".makeSelect").on("change", function (e) {
        $('.yearSelect').html('<option value="0">Select Year</option>');
        $('.modelSelect').html('<option value="0">Select Model</option>');
        $("#wheel_list").html('');
        if (e.target.value != 0) {
            $.get(`https://api.khan-wheels.com/public/api/v1/web/getCategories?parent=${e.target.value}`, {})
                .done(function (res) {

                    res.forEach(function (item, index, array) {
                        $('.yearSelect').append(`<option value="${item['id']}">${item['text']}</option>`);
                    })

                });
        }
    })
    $(".yearSelect").on("change", function (e) {
        $('.modelSelect').html('<option value="0">Select Model</option>');
        $("#wheel_list").html('');
        if (e.target.value != 0) {
            $.get(`https://api.khan-wheels.com/public/api/v1/web/getCategories?parent=${e.target.value}`, {})
                .done(function (res) {
                    res.forEach(function (item, index, array) {
                        $('.modelSelect').append(`<option value="${item['id']}">${item['text']}</option>`);
                    })
                });
        }
    })
    $(".modelSelect").on("change", function (e) {
        if (e.target.value != 0) {
            $.get(`https://api.khan-wheels.com/public/api/v1/web/getCategories?parent=${e.target.value}`, {})
                .done(function (res) {
                    let search_term = '';
                    $("#wheel_list").html('');

                    res.forEach(function (item, index, array) {
                        search_term += item['text'] + ' ';
                        $("#wheel_list").append(`<button type="button" class="btn btn-default" style="margin: 0 10px;">${item['text']}</button>`);
                    })

                    if (search_term != '') {
                        const encoded_serchterm = encodeURI($.trim(search_term));
                        const search_url = 'https://webshop.khan-wheels.com/index.php?route=product/search&search=' + encoded_serchterm;
                        $("#search_wheel").attr("href", search_url);
                        $("#search_wheel").attr("disabled", false);
                    } else {
                        $("#search_wheel").attr("href", '#');
                        $("#search_wheel").attr("disabled", false);
                    }
                });
        }
    })
});