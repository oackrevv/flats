window.onload = function(){
    layui.use(['form','tree'],function() {
        var form = layui.form,
            tree = layui.tree,
            index = layer.load(),
            permission = $('#permission').val();
        $.ajax({
            url:'/admin/role/allMenuList',
            type:'post',
            dataType:'json',
            data:{permission:permission},
            success:function(res){
                tree.render({
                    elem: '#role-permission',
                    showCheckbox:true,
                    showLine:true,
                    data: res
                });
            }
        });
        popupFormSubmit();
        layer.close(index);
    });
};