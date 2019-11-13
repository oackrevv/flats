window.onload = function(){
    layui.use(['form'],function() {
        var form = layui.form,
            index = layer.load();
        form.verify({
            account:function(value,item){
                if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                    return '登录账号不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '登录账号首尾不能出现下划线 \'_\' ';
                }
            },
            password:[
                /^[\S]{6,12}$/,'登录密码必须6到16位，且不能出现空格'
            ],
            repassword:function(value,item){
                password = form.val('form').password;
                if(value.trim() !== password.trim()){
                    return '两次输入的密码不一致';
                }
            }
        });
        popupFormSubmit();
        layer.close(index);
    });
};