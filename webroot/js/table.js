function handleTableRowSelect(newRow) {
    const selectedCSS = "selected";
    var rows = $('table.sleek tr');
    var wasSelected = $(newRow).hasClass(selectedCSS);
    rows.each(function() { $(this).removeClass(selectedCSS); });
    if (!wasSelected)
        $(newRow).addClass(selectedCSS);
}

var $th = $('table.slick').find('thead tr');
$('.tableFixHead').on('scroll', function() {
    $th.css('transform', 'translateY('+ this.scrollTop +'px)');
});