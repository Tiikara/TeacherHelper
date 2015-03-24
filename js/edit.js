
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

    $( '[contenteditable="true"]' ).focus(function(event) {

        $(this).data("before_text", $(this).text())

    }).blur(function(event) {
        if($(this).data("before_text") !== $(this).text())
        {
            $(this).data("before_text", $(this).text());

            var webElement = $(this);

            $.ajax({
                url: webElement.attr("data-edit-url"),
                type: 'POST',
                data: { ajax_post_value: webElement.text()},
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
    });

};