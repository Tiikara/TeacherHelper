
function updateEditEvents()
{
    $( ".delete-href" ).click(function(event) {

        event.preventDefault();

        var webElement = $(this);

        var itemId = webElement.attr("data-item-id");
        var url = webElement.attr("href");

        modal_confirm({
            text: "Вы уверены, что хотите удалить элемент?",
            confirm: function() {

                webElement.replaceWith('<div class="spinner"> \
                                            <div class="spinner-icon"></div> \
                                        </div>');

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