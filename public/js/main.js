jQuery(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});

$(document).ready(function() {
    $(".nav-tabs a").click(function() {
        $(this).tab('show');
    });

    $('a.logout-link').on('click', function(event) {
        event.preventDefault();
        $('#logout-form').submit();
    });
});


