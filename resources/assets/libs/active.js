$(function () {
    function treeviewItemActive(item) {
        item.siblings().removeClass('active');
        item.addClass("active");
        item.parent().parent().addClass("active");
        item.parent().addClass("in");
    }

    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]);
        return null;
    }

    if ($("#title_name").length > 0) {
        var title = $("#title_name").data('title');
        if (title.length) {
            $('title').html(title);

            $("#side-menu span").each(function () {
                if ($(this).text() == title) {
                    treeviewItemActive($(this).parent().parent());
                }
            });
        }
    }

    $('#side-menu ul.treeview-menu li').click(function () {
        treeviewItemActive($(this));
    });

    var _active = getQueryString('_active');
    if (_active) {
        $("#side-menu a").each(function () {
            if ($(this).attr('href') == _active) {
                $(this).click();
            }
        });
    }
})
