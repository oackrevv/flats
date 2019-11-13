window.onload = function() {
    layui.use(['form','table'], function () {
        var form = layui.form,
            table = layui.table;
            renderTable = function () {
                table.render({
                    elem: '#tableList',
                    url: '?tableList',
                    even: true,
                    response:{
                      statusCode:1
                    },
                    page:true,
                    cellMinWidth:100,
                    cols: [[
                        {
                            width: 60, title: '头像', align: 'center',
                            templet:function(d){
                                if(d.head_img){
                                    return '<a href="javascripg:void(0)" onclick="showBigImg(this,\''+d.head_img+'\')"><img src="'+d.head_img+'" width="30" height="100%" /></a>';
                                }else{
                                    return '';
                                }
                            }
                        },
                        // {field: 'id', width: 80, title: 'ID', align: 'center',sort:true},
                        {field: 'role_name', title: '所属角色', align: 'center' },
                        {field: 'name', title: '姓名',align:'center'},
                        // {field: 'sex_text', title: '性别', align: 'center' },
                        {field: 'account', title: '登录账号', align: 'center' },
                        {field: 'email', title: '邮箱',align:'center'},
                        {field: 'phone', title: '手机号码',align:'center'},
                        {
                            field: 'status', title: '状态', minWidth: 100, align: 'center',
                            templet: function (d) {
                                if(d.id !== 10000){
                                    if (d.status) {
                                        return '<input type="checkbox" name="status" lay-skin="switch" value="0" data-id="'+d.id+'" lay-text="启用|禁用" lay-filter="switch" checked>'
                                    } else {
                                        return '<input type="checkbox" name="status" lay-skin="switch" value="1" data-id="'+d.id+'" lay-text="启用|禁用" lay-filter="switch">'
                                    }
                                }else{
                                    return '<input type="checkbox" lay-skin="switch" lay-text="启用|禁用" disabled checked>'
                                }
                            }
                        },
                        {field: 'login_ip', title: '登录IP',align:'center'},
                        {field: 'login_time', title: '登录时间',minWidth: 120,align:'center',sort:true},
                        {field: 'create_time', title: '创建时间',minWidth:120, align: 'center',sort:true},
                        {title: '操作', width: 170, templet: '#tableListBar', fixed: "right", align: "center"}
                    ]],
                });
            };

        renderTable();

        tableSearch();

        $("#add-btn").click(function () {
            operate({
                title:'添加用户',
                url:'form?type=add',
            });
        });

        // 列表开关
        updateListSwitch();

        //列表操作
        table.on('tool(tableList)', function (obj) {
            var layEvent = obj.event,
                data = obj.data;
            switch (layEvent) {
                case 'resetPassword':
                    checkPermission('resetPwd');
                    layer.open({
                        title:'重置密码',
                        type:1,
                        anim:4,
                        btnAlign:'c',
                        btn:['重置','取消'],
                        content:'<form class="layui-form" style="padding: 10px 10px 0;" autocomplete="off" id="resetPasswordForm"><div class="layui-form-item"><input type="text" class="layui-input layui-disabled" readonly placeholder="'+data.account+'" maxlength="16"></div><div class="layui-form-item"><input type="password" class="layui-input" placeholder="请输入新密码" maxlength="16" name="password"></div><div class="layui-row"><input type="password" class="layui-input" placeholder="请输入确认密码" maxlength="16" name="repassword"></div><input type="hidden" name="id" value="'+data.id+'"></form>',
                        yes:function(index,layero){
                            var msg = '';
                            var loadIndex = layer.load();
                            var password = layero.find('[name="password"]').val().trim();
                            var repassword = layero.find('[name="repassword"]').val().trim();
                            var formData = new FormData(layero.find('#resetPasswordForm')[0]);
                            switch (true) {
                                case (!password):
                                    msg = '请输入新密码';
                                    break;
                                case (password.length < 6):
                                    msg = '新密码长度不能小于6位数';
                                    break;
                                case (password.length > 16):
                                    msg = '新密码长度不能小于6位数';
                                    break;
                                case (!repassword):
                                    msg = '请输入确认密码';
                                    break;
                                case (repassword !== password):
                                    msg = '两次输入的密码不一致';
                                    break;
                            }
                            if(msg !== ''){
                                layer.close(loadIndex);
                                layer.msg(msg,{icon:5,time:2000,anim:1,offset:'140px'});
                            }else{
                                $.ajax({
                                    url:'resetPwd',
                                    type:'post',
                                    data:formData,
                                    dataType:'json',
                                    async:false,
                                    cache:false,
                                    contentType:false,
                                    processData:false,
                                    success:function(res){
                                        if(res.code){
                                            layer.close(index);
                                            layer.msg(res.msg,{icon:1,time:2000});
                                        }else{
                                            layer.alert(res.msg,{
                                                title:'提示',
                                                icon:5,
                                                closeBtn:false
                                            },function(index){
                                                layer.close(index);
                                            });
                                        }
                                        layer.close(loadIndex);
                                    }
                                });
                            }
                        }
                    });
                    break;
                case 'edit':
                    operate({
                        title:'修改用户',
                        url:'form?type=edit',
                    },function(body){
                        body.find('[name="name"]').val(data.name);
                        body.find('[name="email"]').val(data.email);
                        body.find('[name="phone"]').val(data.phone);
                        body.find('[name="sex"]').val(data.sex);
                        body.find('[name="head_img"]').next().attr({'src':data.head_img,'data-src':data.head_img});
                        body.find("[name='status'][value='" + data.status + "']").prop("checked", "checked").siblings().attr('disabled',true);
                        if(data.id === 10000){
                            body.find('[name="role_id"]').val(data.role_id).find('option[value="'+data.role_id+'"]').siblings().remove();
                            body.find('[name="account"]').val(data.account).attr('readonly',true);
                        }else{
                            body.find('[name="role_id"]').val(data.role_id);
                            body.find('[name="account"]').val(data.account);
                        }
                        body.find('#password_box').remove();
                        body.find('[lay-filter="submit"]').after('<input type="hidden" name="id" value="'+data.id+'">');
                    });
                    break;
                case 'del':
                    deletes({
                        title:'确定要删除该用户吗?',
                        where:{id:data.id},
                        param:{file_path:data.head_img}
                    });
                    break;
            }
        });
    });
};