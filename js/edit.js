
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

    var activeEditId = -1;
    var activeCurrentText;
    $( '[contenteditable="true"]' ).click(function(event) {
        event.preventDefault();

        if(activeEditId != $(this).id && $(this).attr("contenteditable") == "true")
        {
            activeEditId = $(this).id;
            activeCurrentText = $(this).html();

        }
    }).blur(function(event) {
      //  event.stopImmediatePropagation();

        if(activeEditId==$(this).id)
        {
            activeEditId = -1;
            if(activeCurrentText != $(this).html())
            {
                var webElement = $(this);

                $.ajax({
                    url: webElement.attr("data-edit-url"),
                    type: 'POST',
                    data: { ajax_post_value: webElement.html()},
                    success: function (data) {
                        webElement.children(".spinner").detach();
                        webElement.attr("contenteditable", "true");
                    }
                });

                $(this).append('<div class="spinner"> \
                                            <div class="spinner-icon" style="text-align: center;"></div> \
                                        </div>');

                $(this).attr("contenteditable", "false");
            }
        }
    });

};