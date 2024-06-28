jQuery(function () {
    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });
});

$(document).ready(function () {
    $(".nav-tabs a").click(function () {
        $(this).tab('show');
    });

    $('a.logout-link').on('click', function (event) {
        event.preventDefault();
        $('#logout-form').submit();
    });
});


document.addEventListener('DOMContentLoaded', function () {
    var element = document.getElementById('showDataTableModal');
    let modalName = element.getAttribute('data-modal-name');
    let modalValue = element.getAttribute('data-modal-value');

    if (element) {
        element.addEventListener('click', function () {
            Livewire.emit('showModal', modalName, modalValue)
        });
    }

});