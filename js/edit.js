
function updateEditEvents()
{
    var webDeleteHref = $( '.delete-href' );
    var webContenteditable = $( '[contenteditable]' );

    webDeleteHref.unbind('click', updateEditEvents.clickDeleteHref);
    webContenteditable.unbind('focus', updateEditEvents.focusContentEditable);
    webContenteditable.unbind('blur', updateEditEvents.blurContentEditable);

    updateEditEvents.clickDeleteHref = function(event)
    {
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
    }

    updateEditEvents.focusContentEditable = function(event)
    {
        $(this).data("before_text", $(this).text());
    }

    updateEditEvents.blurContentEditable = function(event)
    {
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

            $(this).append('<div class="spinner" style="padding-left: 5px;"> \
                                        <div class="spinner-icon" style="text-align: center;"></div> \
                                    </div>');

            $(this).attr("contenteditable", "false");

        }
    }

    webDeleteHref.bind('click', updateEditEvents.clickDeleteHref);
    webContenteditable.bind('focus', updateEditEvents.focusContentEditable);
    webContenteditable.bind('blur', updateEditEvents.blurContentEditable);

    $( ".add-href" ).click(function(event) {
        event.preventDefault();

        var url = $(this).attr("href");

        var webLoad;

        if(updateEditEvents.countAddRequests == 0)
        {
            var block_load = '<div class="spinner" style="padding-top: 10px;"> \
                                <div class="spinner-icon" style="text-align: center;"></div> \
                            </div>';
            webLoad = $(block_load);
            webLoad.appendTo( $('.add-load') );
        }
        else
        {
            webLoad = $('.add-load .spinner');
        }

        updateEditEvents.countAddRequests++;

        var ids = $(this).attr("data-add-ids").split(" ");

        var jsonStrData = "{";

        for(var i=0;i<ids.length;i++)
        {
            if(i!=0)
            {
                jsonStrData += ",";
            }

            jsonStrData += '"' + ids[i] + '":"'+ $("#"+ids[i]).val()+'"';
        }

        jsonStrData+="}";

        var jsonSendData = JSON.parse(jsonStrData);

        $.ajax({
            url: url,
            type: 'POST',
            data: jsonSendData,
            success: function (data) {

                updateEditEvents.countAddRequests--;

                if(updateEditEvents.countAddRequests == 0)
                {
                    webLoad.detach();
                }

                var objData = JSON.parse(data);

                if(typeof objData == "undefined")
                {
                    return;
                }

                var newStrElement = $("#add-template")[0].outerHTML;

                for(var key in objData) {
                    newStrElement = newStrElement.replace(new RegExp("::"+key+"::", "g") , objData[key]);
                }

                var newElement = $(newStrElement);

                newElement.removeAttr("style");
                newElement.removeAttr("id");
                newElement.show("slow");

                $("table#table-edit").append(newElement);

                updateEditEvents();
            }
        });
    });
}

updateEditEvents.countAddRequests = 0;