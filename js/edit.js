
function updateEditEvents()
{
    $( ".delete-href" ).click(function(event) {

        event.preventDefault();
        var itemId = $(this).attr("data-item-id");
        var url = $(this).attr("href");

        modal_confirm({
            text: "Вы уверены, что хотите удалить элемент?",
            confirm: function() {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: null,
                    success: function (data) {

                        var mainItem = $('[data-main-item-id="' + itemId + '"]');

                        mainItem.fadeOut('slow', function(){
                            mainItem.remove();
                        });
                    }
                });
            }
        });


    });



};