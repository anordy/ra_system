jQuery(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});

$(document).ready(function() {
    $(".nav-tabs a").click(function() {
        $(this).tab('show');
    });
});