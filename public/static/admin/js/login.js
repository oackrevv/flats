layui.use(['form','layer','jquery'],function(){
    var form = layui.form,
        layer = layui.layer,
    $ = layui.jquery;
    //登录按钮
    form.on("submit(login)",function(data){
        var _this = $(this);
        var loadIndex = layer.load();
        _this.text("登录中").attr("disabled","disabled").addClass("layui-disabled");
        $.post('',data.field,function(res){
            if(res.code){
                layer.msg(res.msg,{icon:1,time:2000},function(){
                    location.href = res.url;
                });
                _this.text('登录');
            }else{
                layer.alert(res.msg,{
                    title:'提示',
                    icon:5,
                    closeBtn:false
                },function(index){
                    _this.text("登录").removeAttr("disabled").removeClass("layui-disabled").parents('.layui-form').find('#imgCode img').click();
                    layer.closeAll();
                });
            }
        });
        return false;
    });

    //表单输入效果
    $(".loginBody .input-item").click(function(e){
        e.stopPropagation();
        $(this).addClass("layui-input-focus").find(".layui-input").focus();
    })
    $(".loginBody .layui-form-item .layui-input").focus(function(){
        $(this).parent().addClass("layui-input-focus");
    })
    $(".loginBody .layui-form-item .layui-input").blur(function(){
        $(this).parent().removeClass("layui-input-focus");
        if($(this).val() != ''){
            $(this).parent().addClass("layui-input-active");
        }else{
            $(this).parent().removeClass("layui-input-active");
        }
    });

    var stars = 800;
    var $stars = $('.stars');
    var r = 800;
    for (var i = 0; i < stars; i++) {
        if (window.CP.shouldStopExecution(1)) {
            break;
        }
        var $star = $('<div/>').addClass('star');
        $stars.append($star);
    }
    window.CP.exitedLoop(1);
    $('.star').each(function () {
        var cur = $(this);
        var s = 0.2 + Math.random() * 1;
        var curR = r + Math.random() * 300;
        cur.css({
            transformOrigin: '0 0 ' + curR + 'px',
            transform: ' translate3d(0,0,-' + curR + 'px) rotateY(' + Math.random() * 360 + 'deg) rotateX(' + Math.random() * -50 + 'deg) scale(' + s + ',' + s + ')'
        });
    });
})
