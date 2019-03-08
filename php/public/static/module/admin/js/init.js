(function($){
    
    /**
     * 获取OneBase基础配置
     * @type {object}
     */
    var ob = window.ob = {};

    /* 基础对象检测 */
    ob || $.error("OneBase基础配置没有正确加载！");

    /* 通过name设置元素的值 */
    ob.setValue = function(name, value){
        
        var first = name.substr(0,1), input, i = 0, val;

        if(value === "") return;

        if("#" === first || "." === first){

                input = $(name);
        } else {

                input = $("[name='" + name + "']");
        }

        if(input.eq(0).is(":radio")) { //单选按钮
            
            input.filter("[value='" + value + "']").each(function(){this.checked = true;});
        } else if(input.eq(0).is(":checkbox")) { //复选框
            
            if(!$.isArray(value)){

                val = new Array();
                val[0] = value;
            } else {
                
                val = value;
            }
            for(i = 0, len = val.length; i < len; i++){
                
                input.filter("[value='" + val[i] + "']").each(function(){this.checked = true});
            }
        } else {  //其他表单选项直接设置值
            
            input.val(value);
        }
    };
    
    /* 前端存储 */
    ob.store = function(name, val){
        
        if (typeof (Storage) !== "undefined") {
            
            if ("undefined" !== typeof (val)) {
                
                localStorage.setItem(name, val);
            } else {
                
                return localStorage.getItem(name);
            }
        } else {
            
          window.alert('store error!');
        }
    };
    
    var url_arr = new Array();
    
    /* 批量加载JS脚本文件 */
    ob.loadMultiScripts = function(arr) {

        var _arr = $.map(arr, function(scr) {

            var index = $.inArray(scr,url_arr);
            
            if(index <= 0) {
                return $.getScript(scr, function() {

                  url_arr.push(scr);
                });
            }
        });

        return $.when.apply($, _arr);
    };

})(jQuery);