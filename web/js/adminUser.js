$(document).ready(function () {
    var tagList = new List('tag-list', {
        valueNames: ['name', 'email', 'role'],
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