window.onload = function() {
    layui.config({
        base: '/static/common/js/plugin/lay-modules/'
    }).extend({
        treetable: 'treetable'
    }).use(['form', 'treetable','table'], function () {
        var form = layui.form,
            treetable = layui.treetable,
            table = layui.table;
            renderTreeTable = function () {
                layer.load(2);
                treetable.render({
                    elem: '#tableList',
                    url: '?tableList',
                    page: false,
                    height:"full-100",
                    treeSpid: 0,
                    treeColIndex: 1,
                    treeIdName: 'id',
                    treePidName: 'pid',
                    treeDefaultClose: false,
                    treeLinkage: false,
                    cols: [[
                        {
                            field: 'sort', width: 80, title: '排序', align: 'center',
                            templet: function (d) {
                                return '<input type="number" min="0" max="999" name="sort" value="' + d.sort + '" class="layui-input update-custom-input" data-id="'+d.id+'" data-val="'+d.sort+'" style="height:100%;">';
                            }
                        },
                        {field: 'name', minWidth: 200, title: '菜单名称'},
                        {
                            field: 'status', title: '状态', width: 100, align: 'center',
                            templet: function (d) {
                                if (d.status) {
                                    return '<input type="checkbox" name="status" lay-skin="switch" value="0" data-id="'+d.id+'" lay-text="启用|禁用" lay-filter="switch" checked>'
                                } else {
                                    return '<input type="checkbox" name="status" lay-skin="switch" value="1" data-id="'+d.id+'" lay-text="启用|禁用" lay-filter="switch">'
                                }
                            }
                        },
                        {
                            field: 'icon', title: '图标', width: 100, align: 'center',
                            templet: function (d) {
                                return '<i class="' + d.icon + '"></i>';
                            }
                        },
                        {field: 'href', title: '请求地址',minWidth: 120},
                        {
                            field: 'type', title: '类型', width: 100, align: 'center',
                            templet: function (d) {
                                var type = parseInt(d.type);
                                if (type === 1) {
                                    return '<span class="layui-badge layui-bg-blue">顶部菜单</span>';
                                } else if(type === 2){
                                    return '<span class="layui-badge-rim layui-bg-green">左侧菜单</span>';
                                } else if(type === 3){
                                    return '<span class="layui-badge-rim">节点按钮</span>';
                                }
                            }
                        },
                        {field: 'create_time', title: '创建时间', width: 160, align: 'center'},
                        {title: '操作', width: 170, templet: '#tableListBar', fixed: "right", align: "center"}
                    ]],
                    done: function (res) {
                        layer.closeAll('loading');
                    }
                });
            };

        renderTreeTable();

        $('#expand-btn').click(function () {
            treetable.expandAll('#tableList');
        });

        $('#fold-btn').click(function () {
            treetable.foldAll('#tableList');
        });
        $('#refresh-btn').click(function () {
            renderTreeTable();
        });

        $("#add-btn").click(function () {
            operate({
                title:'添加菜单',
                url:'form?type=add',
            });
        });

        // 列表开关
        updateListSwitch();

        // 列表自定义输入框
        updateListCustomInput();

        //列表操作
        table.on('tool(tableList)', function (obj) {
            var layEvent = obj.event,
                data = obj.data;
            switch (layEvent) {
                case 'add':
                    operate({
                        title:'添加菜单',
                        url:'form?type=add',
                    },function(body){
                        body.find('[name="pid"]').val(data.id);
                        body.find("[name='type'][value='1']").prop('disabled',true);
                    });
                    break;
                case 'edit':
                    operate({
                        title:'修改菜单',
                        url:'form?type=edit',
                    },function(body) {
                        body.find('[name="name"]').val(data.name);
                        body.find('[name="href"]').val(data.href);
                        if(data.icon){
                            body.find('#icon input').val(data.icon);
                            body.find('#icon .show-icon i').addClass(data.icon);
                            body.find('#icon .show-icon').removeClass('layui-hide');
                        }
                        body.find('[name="target"]').val(data.target);
                        if(data.type === 1){
                            body.find('#pid').hide();
                            body.find('#pid select').removeAttr('name');
                        }else if(data.type === 3){
                            body.find('#icon').hide();
                            body.find('#target').hide();
                            body.find('#icon input').removeAttr('name');
                            body.find('#target select').removeAttr('name');
                        }
                        if(data.pid !== 0){
                            body.find('#pid').show();
                            body.find('#pid select').val(data.pid);
                            //禁止选择
                            body.find('#pid select option[data-level]').each(function(index,item){
                                if($(this).attr('data-level') >= data.level){
                                    $(this).prop('disabled',true);
                                }
                            });
                        }
                        body.find("[name='type']").prop("disabled",true);
                        body.find("[name='type'][value='" + data.type + "']").prop("checked", "checked").removeAttr('disabled');
                        body.find("[name='status'][value='" + data.status + "']").prop("checked", "checked");
                        body.find('[lay-filter="submit"]').after('<input type="hidden" name="id" value="'+data.id+'">');
                    });
                    break;
                case 'del':
                    deletes({
                        title:'确定要删除该菜单吗?',
                        where:{id:data.id},
                    });
                    break;
            }
        });
    });
};