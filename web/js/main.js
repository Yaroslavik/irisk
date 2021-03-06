$(document).ready(function () {
    $('a').nivoLightbox();

    $(".topnav, .breadcumbs").find('a').each(function(){
        $(this).attr('href', $(this).attr('href') + '#catalog');
    });

    $(".topnav").find("li").each(function() {
        if ($(this).find("ul").size() != 0) {
            $(this).find("a:first").click(function(){return false;});
        }

        if ($(this).find('li.active').size() == 0 && $(this).hasClass('active')) {
            $(this).addClass('last-active');
        }
    }).end().accordion({
        accordion: true,
        speed: 500,
        closedSign: '[+]',
        openedSign: '[-]'
    });

    $(".slider").each(function () {
        var obj = $(this);
        $(obj).append("<div class='nav'></div>");
        $(obj).find("li").each(function () {
            $(obj).find(".nav").append("<span rel='" + $(this).index() + "'></span>");
            $(this).addClass("slider" + $(this).index());
        });
        $(obj).find("span").first().addClass("on");
    });

    function sliderJS(obj, sl) {
        var ul = $(sl).find("ul");
        var bl = $(sl).find("li.slider" + obj);
        var step = $(bl).width();
        $(ul).animate({marginLeft: "-" + step * obj}, 500);
    }

    $(document).on("click", ".slider .nav span", function () {
        var sl = $(this).closest(".slider");
        $(sl).find("span").removeClass("on");
        $(this).addClass("on");
        var obj = $(this).attr("rel");
        sliderJS(obj, sl);
        return false;
    });
});