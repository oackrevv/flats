/**
 * 退出登录
 */
function logout(){
    layer.confirm('确定要退出登录吗？',{title:'提示',icon:3,shadeClose:true,btnAlign:'c'},function(index){
        var loadIndex = layer.load();
        $.post('/admin/common/logout',{},function(res){
            layer.msg(res.msg,{icon:1,time:2000},function(){
                location.href = res.url;
            });
            layer.close(index);
        })
    })
}

/**
 * 关闭当前iframe页内弹出的iframe页面
 */
function closeFrame(){
    var index = parent.layui.layer.getFrameIndex(window.name);
    parent.layui.layer.close(index);
}

/**
 * 关闭当前打开的的Tab iframe页面
 */
function closeTabIframe() {
    $(parent.document.body).find('#top_tabs .layui-this i.layui-tab-close').click();
}

/**
 * 显示大图
 * @param _this
 * @param url
 */
function showBigImg(_this,url='') {
    if(!url) url = $(_this).attr('data-src');
    var img = new Image();
    img.src = url;
    var w = parseInt($(window).width());
    if(img.complete){
        width = img.width;
        height = img.height;
    }else{
        img.onload = function(){
            width = img.width;
            height = img.height;
        }
    }
    if(width > w){
        width = w-20;
    }
    layer.open({
        title:false,
        type:1,
        shadeClose:true,
        area:[width+'px','auto'],
        content:'<a href="'+url+'" target="_blank"><img src="'+url+'" style="width:100%;min-height:100%;"/></a>',
    })
}

/**
 * 选择图片
 * @param _this
 */
function chooseImageChange(_this){
    var files = _this.files[0];
    var fileReader = new FileReader();
    fileReader.readAsDataURL(files);
    fileReader.onloadend = function(e){
        $(_this).prev('img').attr({'data-src':e.currentTarget.result,'src':e.currentTarget.result});
        $(_this).next('img').attr({'data-src':e.currentTarget.result,'src':e.currentTarget.result});
    }
}


/**
 * 重载表格
 */
function reloadTable(){
    try{
        renderTable();
    }catch (e) {

    }
    try {
        renderTreeTable();
    }catch (e) {

    }
}

/**
 * 数据表格搜索
 */
function tableSearch() {
    layui.form.on('submit(search)',function(obj){
        layui.table.reload('tableList', {
            page: {
                curr: 1 //重新从第 1 页开始
            },
            where: {
                search:obj.field
            }
        });
        return false;
    });
}

/**
 * 更新列表开关
 * @param _callback
 */
function updateListSwitch(_callback){
    layui.form.on('switch(switch)',function (obj) {
        checkPermission('listUpdate');
        var name = this.name;
        var value = obj.value;
        var id = $(this).data('id');
        var loadIndex = layer.load();
        var data = {id:id};
        data[name] = value;
        layer.confirm('确定要进行此操作吗？', {title:'提示', icon:3, shadeClose:true, btnAlign: 'c', end:function(){layer.close(loadIndex);reloadTable()}},function(index){
            $.post('listUpdate',data,function(res){
                if(res.code){
                    layer.close(index);
                    layer.msg(res.msg,{icon:1,time:2000});
                }else{
                    layer.alert(res.msg,{
                        title:'提示',
                        icon:5,
                    },function(index){
                        layer.close(index);
                    });
                }
                _callback ? _callback(res) : "";
            })
        });
    });
}

/**
 * 更新列表自定义输入框
 * @param _callback
 */
function updateListCustomInput(_callback) {
    $(document.body).on('blur','[lay-id="tableList"] .update-custom-input',function(){
        checkPermission('listUpdate');
        var id = $(this).data('id');
        var old_val = $(this).data('val');
        var name = $(this).attr('name');
        var new_val = $(this).val();
        var data = {id:id};
        data[name] = new_val;
        if(new_val == old_val) return;
        var loadIndex = layer.load();
        $.post('listUpdate',data,function(res){
            layer.close(loadIndex);
            if(res.code){
                layer.msg(res.msg,{icon:1,time:2000});
            }else{
                layer.alert(res.msg,{
                    title:'提示',
                    icon:5,
                },function(index){
                    layer.close(index);
                });
            }
            reloadTable();
            _callback ? _callback(res) : "";
        })
    })
}

/**
 * tab 页面表单提交
 * @param url
 */
function tabFormSubmit(url=''){
    layui.form.on('submit(submit)',function(e){
        var _this = $(this);
        var loadIndex = layer.load();
        var formData = new FormData(e.form);
        $.ajax({
            url:url,
            type:'post',
            data:formData,
            dataType:'json',
            async:false,
            cache:false,
            contentType:false,
            processData:false,
            success:function(res){
                if(res.code){
                    layer.msg(res.msg,{icon:1,time:2000});
                }else{
                    layer.alert(res.msg,{
                        title:'提示',
                        icon:5,
                    },function(index){
                        layer.close(index);
                    });
                }
                layer.close(loadIndex);
            },
            error:function(XMLHttpRequest,error){
                layer.alert(error,{
                    title:'提示',
                    icon:5,
                },function(index){
                    layer.close(index);
                });
                layer.close(loadIndex);
            }
        });
        return false;
    })
}

/**
 * iframe页面弹出表单提交
 * @param url
 */
function popupFormSubmit(url=''){
    layui.form.on('submit(submit)',function(e){
        var _this = $(this);
        var loadIndex = layer.load();
        var formData = new FormData(e.form);
        $.ajax({
            url:url,
            type:'post',
            data:formData,
            dataType:'json',
            async:false,
            cache:false,
            contentType:false,
            processData:false,
            success:function(res){
                if(res.code){
                    closeFrame();
                    _this.attr('is_submit',1);
                    layer.msg(res.msg,{icon:1,time:2000});
                }else{
                    layer.alert(res.msg,{
                        title:'提示',
                        icon:5,
                    },function(index){
                        layer.close(index);
                    });
                }
                layer.close(loadIndex);
            },
            error:function(XMLHttpRequest,error){
                layer.alert(error,{
                    title:'提示',
                    icon:5,
                },function(index){
                    layer.close(index);
                });
                layer.close(loadIndex);
            }
        });
        return false;
    })
}

/**
 * 添加 / 修改操作弹出
 * @param object
 * @param _callback
 */
function operate(object,_callback) {
    var title = object.title ? object.title : '操作';
    var url  = object.url ? object.url : '';
    if(!url) throw 'url 参数不能为空';
    checkPermission(url);
    var index = layui.layer.open({
        title: title,
        type: 2,
        content: url,
        success: function (layero, index) {
            body = layui.layer.getChildFrame('body', index);
            _callback ? _callback(body) : "";
            setTimeout(function () {
                layui.layer.tips('点击此处返回列表', '.layui-layer-setwin .layui-layer-close', {
                    tips: 3
                });
            }, 500)
        },
        end: function () {
            if (typeof body !== undefined) {
                var is_submit = body.find('[lay-filter="submit"]').attr('is_submit');
                if (is_submit) {
                    setTimeout(function () {
                        reloadTable();
                    }, 300);
                }
            }
        }
    });
    layui.layer.full(index);
    $(window).on("resize",function(){
        layui.layer.full(index);
    })
}

/**
 * 删除数据
 * @param object
 * @param _callback
 */
function deletes(object,_callback){
    var loadIndex = layer.load();
    var title = object.title ? object.title : '确定要删除该信息吗?';
    var where = object.where ? object.where : {};
    var param = object.param ? object.param : {};
    var url = object.url ? object.url : 'deletes';
    if(!Object.keys(where).length) throw 'where 参数不能为空';
    checkPermission(url);
    layer.confirm(title, {title:'提示', icon:3, shadeClose:true, btnAlign: 'c', end:function(){layer.close(loadIndex)}},function(index){
        $.post(url,Object.assign(where,param),function(res){
            if(res.code){
                layer.close(index);
                layer.msg(res.msg,{icon:1,time:2000});
                reloadTable();
            }else{
                layer.alert(res.msg,{
                    title:'提示',
                    icon:5,
                },function(index){
                    layer.close(index);
                });
            }
            _callback ? _callback(res) : "";
        })
    });
}

/**
 * 检查是否拥有操作权限
 * @param url
 */
function checkPermission(url=''){
    $.ajax({
        url:url,
        async:false,
        data:{checkPermission:true},
        success:function (res) {
            if(typeof res === 'object'){
                if(!res.code){
                    layer.closeAll();
                    setTimeout(function(){
                       $(document.body).find('#top_tabs .layui-this i.layui-tab-close').click();
                    },100);
                    layer.alert(res.msg ? res.msg : '权限不足',{title:'提示',shadeClose:true,icon:5});
                    throw res.msg ? res.msg : '权限不足';
                }
            }
        }
    });
}