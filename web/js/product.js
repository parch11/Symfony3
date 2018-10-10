$(document).ready(function () {
    var productList = new List('product-list', {
        valueNames: ['name'],
        page: 8,
        pagination: true
    });
    $('.jPaginateNext').on('click', function () {
        var list = $('.pagination').find('li');
        $.each(list, function (position, element) {
            if ($(element).is('.active')) {
                $(list[position + 1]).trigger('click');
            }
        })
    });


    $('.jPaginateBack').on('click', function () {
        var list = $('.pagination').find('li');
        $.each(list, function (position, element) {
            if ($(element).is('.active')) {
                $(list[position - 1]).trigger('click');
            }
        })
    });
});