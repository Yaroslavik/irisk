$(document).ready(function () {
    $('a').nivoLightbox();

    $(".topnav").accordion({
        accordion:true,
        speed: 500,
        closedSign: '[+]',
        openedSign: '[-]'
    });
});