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
                        {field: 'id', minWidth: 80, title: 'ID', align: 'center' },
                        {field: 'name', minWidth: 100, title: '名称',align:'center'},
                        {
                            field: 'status', title: '状态', minWidth: 100, align: 'center',
                            templet: function (d) {
                                if(d.id !== 1){
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
                        {field: 'remark', title: '备注',minWidth: 100},
                        {field: 'create_time', title: '创建时间',minWidth:120, align: 'center'},
                        {title: '操作', width: 170, templet: '#tableListBar', fixed: "right", align: "center"}
                    ]],
                });
            };

        renderTable();

        $('#refresh-btn').click(function () {
            renderTable();
        });

        $("#add-btn").click(function () {
            operate({
                title:'添加角色',
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
                case 'edit':
                    operate({
                        title:'修改角色',
                        url:'form?type=edit',
                    },function(body){
                        body.find('[name="name"]').val(data.name);
                        body.find("[name='status'][value='" + data.status + "']").prop("checked", "checked");
                        body.find('[name="remark"]').val(data.remark);
                        body.find('[lay-filter="submit"]').after('<input type="hidden" name="id" value="'+data.id+'">');
                        body.find('[lay-filter="submit"]').after('<input type="hidden" id="permission" value="'+data.permission+'">');
                    });
                    break;
                case 'del':
                    deletes({
                        title:'确定要删除该角色吗?',
                        where:{id:data.id},
                    });
                    break;
            }
        });
    });
};