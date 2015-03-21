
$( document ).ready(function() {

    NProgress.configure({ parent: '#pagecontainer' });

    $( ".loader-button" ).click(function(event) {
        event.preventDefault();

        var url = $(this).attr("href");
        var urlAjax = url + "&ajax";

        NProgress.start();

        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: null,
            success: function (data) {
                $( "#pagecontainer").html(data);
                history.pushState('', '', url);
                NProgress.done();
            }
        });

    });
});