$.pjax.defaults.timeout = 10000;
$.pjax.defaults.maxCacheLength = 0;

pjax_mode == 1 && $(document).pjax('a[target!=_blank]', '.content');

$(document).on('pjax:send', function() {

    $('.fakeloader').show();
});

$(document).on('pjax:complete', function() {

    $('.fakeloader').hide();

    var ob_title_hidden = $("#ob_title_hidden").val();

    if (ob_title_hidden != null && ob_title_hidden != '') {
        
         document.title =  'OneVideo | ' + ob_title_hidden;
    }

    var $checkboxAll = $(".js-checkbox-all"),
        $checkbox = $("tbody").find("[type='checkbox']").not("[disabled]"),
        length = $checkbox.length,
        i=0;

        //启动icheck
        $((".table [type='checkbox']")).iCheck({
          checkboxClass: 'icheckbox_flat-grey',
        });
        
        //全选checkbox
        $checkboxAll.on("ifClicked",function(event){
          if(event.target.checked){
            $checkbox.iCheck('uncheck');
            i=0;
          }else{
            $checkbox.iCheck('check');
            i=length;
          }
        });

        $checkbox.on('ifClicked',function(event){
          event.target.checked ? i-- : i++;
          if(i==length){
            $checkboxAll.iCheck('check');
          }else{
            $checkboxAll.iCheck('uncheck');
          }
        });


    /**
     * PJAX模式重写get请求提交处理
     */
    $('.ajax-get').click(function(){

        var target;

        if ( $(this).hasClass('confirm') ) {

            if(!confirm('确认要执行该操作吗?')){

                return false;
            }
        }

        if ( (target = $(this).attr('href')) || (target = $(this).attr('url')) ) {

            if ($(this).attr('is-jump') == 'true') {
                
                $.pjax({url: target,container: '.content'});
                
            } else {
                $.get(target).success(function(data){
                    
                    obalertp(data);
                });
            }
        }

        return false;
    });

    /**
     * PJAX模式重写表单POST提交处理
     */
    $('.ajax-post').click(function(){

        var target,query,form;

        var target_form = $(this).attr('target-form');

        var that = this;

        var nead_confirm=false;

        if( ($(this).attr('type')=='submit') || (target = $(this).attr('href')) || (target = $(this).attr('url')) ){

            form = $('.'+target_form);

            if ($(this).attr('hide-data') === 'true'){//无数据时也可以使用的功能
                form = $('.hide-data');
                query = form.serialize();
            }else if (form.get(0)==undefined){
                return false;
            }else if ( form.get(0).nodeName=='FORM' ){

                if ( $(this).hasClass('confirm') ) {

                    if(!confirm('确认要执行该操作吗?')){

                        return false;
                    }
                }
                if($(this).attr('url') !== undefined){
                        target = $(this).attr('url');
                }else{
                        target = form.get(0).action;
                }
                query = form.serialize();
            }else if( form.get(0).nodeName=='INPUT' || form.get(0).nodeName=='SELECT' || form.get(0).nodeName=='TEXTAREA') {

                form.each(function(k,v){
                    if(v.type=='checkbox' && v.checked==true){
                        nead_confirm = true;
                    }
                })

                if ( nead_confirm && $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }

                query = form.serialize();
            }else{

                if ( $(this).hasClass('confirm') ) {
                    if(!confirm('确认要执行该操作吗?')){
                        return false;
                    }
                }
                query = form.find('input,select,textarea').serialize();
            }

            var is_ladda_button =  $(that).hasClass('ladda-button');

            is_ladda_button ? button.start('.ladda-button') : $(that).addClass('disabled').attr('autocomplete','off').prop('disabled',true);

            $.post(target,query).success(function(data){

                obalertp(data);

                is_ladda_button ? button.stop('.ladda-button') : $(that).removeClass('disabled').prop('disabled',false);
            });
        }

        return false;
    });
    
    //排序
    $(".sort-text").change(function(){
        
        var val = $(this).val();

        if(!((/^(\+|-)?\d+$/.test(val)) && val >= 0)){
            
            toast.warning('请输入正整数');
            return false;
        }

        $.post($(this).attr("href"),{id:$(this).attr('id'), value:val}, function(data){

            obalertp(data);

            },"json");

    });
    
    //全选|全不选
    $(".checkbox-select-all").click(function(){
        
        var select_status = $(this).find("input").is(":checked");

        var table_input = $(".table").find("input");

        if (select_status) {
            
            table_input.prop("checked", true);
            
        } else {
            table_input.prop("checked", false);
        }

    });
    
    //批量处理
    $('.batch_btn').click(function(){
        
            var $checked = $('.table input[type="checkbox"]:checked');
            
            if($checked.length != 0){
                    if(confirm('您确认批量操作吗？')){
                        
                        $.post($(this).attr("href"),{ids:$checked.serializeArray(), status:$(this).attr("value")}, function(data){

                            obalertp(data);

                        },"json");
                    }
            } else {
                
                toast.warning('请选择批量操作数据');
            }
            return false;
    });
    
    //搜索功能
    $("#search").click(function(){
        
        var url = searchFormUrl(this);

        $.pjax({url: url,container: '.content'});
    });

    //回车搜索
    $(".search-input").keyup(function(e){
        if(e.keyCode === 13){
                $("#search").click();
                return false;
        }
    });

});

/**
 * PJAX模式重写跳转处理
 */
var obalertp = function (data) {

    if (data.code) {
        
        toast.success(data.msg);
    } else {
        
        if(typeof data.msg == "string"){
            
             toast.error(data.msg);
        }else{
            
            var err_msg = '';
            
            for(var item in data.msg){ err_msg += "Θ " + data.msg[item] + "<br/>"; }
            
            toast.error(err_msg);
        }
    }
    
    data.url && $.pjax({url: data.url,container: '.content'});
};

/**
 * PJAX模式左侧菜单优化点击显示
 */
$('.sidebar-menu li').click(function () {
    if ($(this).find('ul').length <= 0) {
        $(this).siblings('li').removeClass('active');
        $(this).addClass('active');
    }
});

/**
 * 搜索表单url
 */
var searchFormUrl = function (obj) {

    var url = $(obj).attr('url');
    
    var query  = $('.search-form').find('input,select').serialize();
    query = query.replace(/(&|^)(\w*?\d*?\-*?_*?)*?=?((?=&)|(?=$))/g,'');
    query = query.replace(/^&/g,'');
    if( url.indexOf('?')>0 ){
        url += '&' + query;
    }else{
        url += '?' + query;
    }
    
    return url;
};
