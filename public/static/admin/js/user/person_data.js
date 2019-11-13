window.onload = function(){
    layui.use(['form'],function() {
        var form = layui.form,
            index = layer.load();

        tabFormSubmit();
        layer.close(index);
    });
};