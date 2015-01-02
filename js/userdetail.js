$(document).ready(function () {
    $('#tabs_wrapper a').click(function (e) {
        e.preventDefault();
        $(this).tab('show')
    })
});