/* Masonry for Changelogs boxes */
$(function(){
    var m = new Masonry($('.grid').get()[0], {
        itemSelector: ".version-box"
    });
});

$(function() {
    $('.dropdown-el').click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).toggleClass('expanded');
        $('#'+$(e.target).attr('for')).prop('checked',true);
    });
    $(document).click(function() {
        $('.dropdown-el').removeClass('expanded');
    });
});

/* Display and hide GNU Logo when collapse is active */
$(function() {
    $(".gnu-license a").click(function() {
        $(".gnu-logo").toggle();
    });
});

/* Popovers */
$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    /* Fixs double click needed when closing popover from button */
    $('body').on('hidden.bs.popover', function (e) {
        $(e.target).data("bs.popover").inState.click = false;
    });
});

/* Clipboard */
$(document).ready(function() {
    new Clipboard('.copy-md5sum');
});


/* Handle testimonies grid system */
$(function(){
    var m = new Masonry($('.pwg-testimonies-row').get()[0], {
        itemSelector: ".pwg-testimonies-content"
    });
});

/* Change testimonies background color dependng on user type (individual, pro, organisation */
$(document).ready(function() {
    var colors = ["#EBF5FF","#E1D6FF","#FFEDCF"];
    var fontColors = ["#338AC5","#7E72C0","#FF7700"];
    $('.Individual').css("background-color", colors[0]).css("color", fontColors[0]);
    $('.Professional').css("background-color", colors[1]).css("color", fontColors[1]);
    $('.Organisation').css("background-color", colors[2]).css("color", fontColors[2]);
});
