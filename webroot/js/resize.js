function handleResize() {
    const xs = 993;//768;
    const xxs = 600;//768;
    const width = $(window).width();
    var fontSize = 27;
    if (width <= xxs)
        fontSize = 12;
    else if (width <= xs)
        fontSize = 18;
    $("#myhtml, body, div.main-content").css("font-size", fontSize + "px");
}

$(window).on('load', function() {
    handleResize();
});

$(window).on('resize', function() {
    handleResize();
});