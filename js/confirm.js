
function modal_confirm(settings)
{
    if(typeof settings.text == "undefined")
        settings.text = "Empty text";

    if(typeof settings.confirm == "undefined")
        settings.confirm = function(){};

    if(typeof settings.cancel == "undefined")
        settings.cancel = function(){};

    var html_block = '<div class="modal" style="display: none;" id="confirm"> \
                        <div class="modal-content"> \
                            <div id="confirm-text">'+ settings.text +'<br> \
                                <a id="confirm-yes" href="" class="ok"></a><a id="confirm-no" href="" class="no"></a> \
                            </div> \
                        </div>';

    $('body').append(html_block);

    $('#confirm').fadeIn('slow');

    function confirmFadeOut()
    {
        $('#confirm').fadeOut('slow' , function () {
            $(this).remove();
        });
    }
    
    $("#confirm-yes").click(function(event){
        event.preventDefault();

        settings.confirm();

        confirmFadeOut();
    });

    $("#confirm-no").click(function(event){
        event.preventDefault();

        settings.cancel();

        confirmFadeOut();
    });
}