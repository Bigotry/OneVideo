function addField(obj)
{
    var obj_parent = $(obj).parent().parent();

    obj_parent.parent().append(obj_parent.clone());
}

$(document).ajaxStart(function(){ $("button:submit").attr("disabled", true);}).ajaxStop(function(){$("button:submit").attr("disabled", false);});
                
$("form").submit(function()
{
    var self = $(this);
    
    self.ajaxSubmit({
        
        dataType:'json',
        success : function(data){
            
            $(".api_response_pre").html(JSON.stringify(data, null, 4));
            
            $("#api_response").show(500);
        }
      });
      
    return false;
});

function apiDetails(obj)
{

    $('.fakeloader').show();

    $.get($(obj).attr('url'), {}, success, "json");
    return false;
    function success(data)
    {

        $("#content").html(data.content);

        $('.fakeloader').hide();
    }
}