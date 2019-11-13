window.onload = function(){
    layui.use(['form'],function() {
        var form = layui.form;

        form.on('radio(type)',function(e){
            var value = parseInt(e.value);
            if(value === 1){
                $('#pid').hide();
                $('#icon').show();
                $('#target').show();
                $('#pid select').removeAttr('name');
                $('#icon input').attr('name','icon');
                $('#target select').attr('name','target');
            }else if(value === 2){
                $('#pid').show();
                $('#icon').show();
                $('#target').show();
                $('#pid select').attr('name','pid');
                $('#icon input').attr('name','icon');
                $('#target select').attr('name','target');
            }else if(value === 3){
                $('#pid').show();
                $('#icon').hide();
                $('#target').hide();
                $('#pid select').attr('name','pid');
                $('#icon input').removeAttr('name');
                $('#target select').removeAttr('name');
            }
        });

        $('#icon input[name="icon"]').click(function(){
            var _this = $(this);
            parent.layui.layer.open({
                title:'icon图标',
                type:2,
                shadeClose:true,
                anim:4,
                maxmin:true,
                area:['350px','500px'],
                content:['/admin/common/icon','no'],
                success:function(layero,index){
                    var body = parent.layui.layer.getChildFrame('body', index);
                    body.find('.layui-tab .layui-tab-content').on('click',' ul li',function(){
                        var text = $(this).find('i').attr('class');
                        form.val('form',{icon:text});
                        _this.parents('.layui-form-item').find('.show-icon i').attr('class',text);
                        _this.parents('.layui-form-item').find('.show-icon').removeClass('layui-hide');
                        parent.layui.layer.close(index);
                    })
                }
            })
        });

        $('.dispose_of_icon').click(function(){
            form.val('form',{icon:''});
            $('#icon input[name="icon"]').parents('.layui-form-item').find('.show-icon i').attr('class','');
            $(this).parents('.show-icon').addClass('layui-hide');
        });

        popupFormSubmit();
    });
};