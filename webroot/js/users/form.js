/*
FIXME doesn't work
$(window).on('load', function() {
    const screenHeight = $(window).height();
    const navHeight = $('nav.nav-height').height();
    const padding = (screenHeight - navHeight) / 4.0;
    console.log(padding);
    $('div.user-form .pad').css('padding-top', padding);
});
*/

function handleResize() {
    const xs = 993;//768;
    const width = $(window).width();
    var textAlign = (width <= xs) ? 'center' : 'right';
    $('.field-name').css('text-align', textAlign);
}

$(window).on('load', function() {
    handleResize();
});

$(window).on('resize', function() {
    handleResize();
});