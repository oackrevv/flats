var $,tab,dataStr,layer;
layui.config({
	base : "static/admin/js/"
}).extend({
	"bodyTab" : "bodyTab"
})
layui.use(['bodyTab','form','element','layer','jquery'],function(){
	var form = layui.form,
		element = layui.element;
		$ = layui.$;
    	layer = parent.layer === undefined ? layui.layer : top.layer;
		tab = layui.bodyTab({
			openTabNum : "50",  //最大可打开窗口数量
			url : "/admin/menu/menuList" //获取菜单json地址
		});

	//默认要显示的菜单
	getData($(".topLevelMenus li.layui-this,.mobileTopLevelMenus dd.layui-this").data('menu'));

	//通过顶部菜单获取左侧二三级菜单
	function getData(id){
		$.getJSON(tab.tabConfig.url,function(data){
			dataStr = data[id].children;
			tab.render();
		})
	}
	//页面加载时判断左侧菜单是否显示
	//通过顶部菜单获取左侧菜单
	$(".topLevelMenus li,.mobileTopLevelMenus dd").click(function(){
		if($(this).parents(".mobileTopLevelMenus").length != "0"){
			$(".topLevelMenus li").eq($(this).index()).addClass("layui-this").siblings().removeClass("layui-this");
		}else{
			$(".mobileTopLevelMenus dd").eq($(this).index()).addClass("layui-this").siblings().removeClass("layui-this");
		}
		$(".layui-layout-admin").removeClass("showMenu");
		$("body").addClass("site-mobile");
		getData($(this).data("menu"));
		//渲染顶部窗口
		tab.tabMove();
	})

	//隐藏左侧导航
	$(".hideMenu").click(function(){
		if($(".topLevelMenus li.layui-this a").data("url")){
			layer.msg("此栏目状态下左侧菜单不可展开");  //主要为了避免左侧显示的内容与顶部菜单不匹配
			return false;
		}
		$(".layui-layout-admin").toggleClass("showMenu");
		//渲染顶部窗口
		tab.tabMove();
	})

	//手机设备的简单适配
    $('.site-tree-mobile').on('click', function(){
		$('body').addClass('site-mobile');
	});
    $('.site-mobile-shade').on('click', function(){
		$('body').removeClass('site-mobile');
	});

	// 添加新窗口
	$("body").on("click",".layui-nav .layui-nav-item a:not('.mobileTopLevelMenus .layui-nav-item a')",function(){
		//如果不存在子级
		if($(this).siblings().length == 0){
			addTab($(this));
			$('body').removeClass('site-mobile');  //移动端点击菜单关闭菜单层
		}
		$(this).parent("li").siblings().removeClass("layui-nav-itemed");
	})

	//清除缓存
	$(".clearCache").click(function(){
		window.sessionStorage.clear();
        window.localStorage.clear();
        var index = layer.msg('清除缓存中，请稍候',{icon: 16,time:false,shade:0.8});
        $.get('/admin/common/clearCache',function(res){
			setTimeout(function(){
				layer.close(index);
				layer.msg(res.msg,{time:2000,icon:1});
			},1000);
		});
    });

	//刷新后还原打开的窗口
    if(cacheStr == "true") {
        if (window.sessionStorage.getItem("menu") != null) {
            menu = JSON.parse(window.sessionStorage.getItem("menu"));
            curmenu = window.sessionStorage.getItem("curmenu");
            var openTitle = '';
            for (var i = 0; i < menu.length; i++) {
                openTitle = '';
                if (menu[i].icon) {
                    if (menu[i].icon.split("-")[0] == 'icon') {
                        openTitle += '<i class="seraph ' + menu[i].icon + '"></i>';
                    } else {
                        openTitle += '<i class="layui-icon">' + menu[i].icon + '</i>';
                    }
                }
                openTitle += '<cite>' + menu[i].title + '</cite>';
                openTitle += '<i class="layui-icon layui-unselect layui-tab-close" data-id="' + menu[i].layId + '">&#x1006;</i>';
                element.tabAdd("bodyTab", {
                    title: openTitle,
                    content: "<iframe src='" + menu[i].href + "' data-id='" + menu[i].layId + "'></frame>",
                    id: menu[i].layId
                })
                //定位到刷新前的窗口
                if (curmenu != "undefined") {
                    if (curmenu == '' || curmenu == "null") {  //定位到后台首页
                        element.tabChange("bodyTab", '');
                    } else if (JSON.parse(curmenu).title == menu[i].title) {  //定位到刷新前的页面
                        element.tabChange("bodyTab", menu[i].layId);
                    }
                } else {
                    element.tabChange("bodyTab", menu[menu.length - 1].layId);
                }
            }
            //渲染顶部窗口
            tab.tabMove();
        }
    }else{
		window.sessionStorage.removeItem("menu");
		window.sessionStorage.removeItem("curmenu");
	}
	// 修改密码
	$('.modifyPwd').click(function(){
		checkPermission('/admin/user/modifyPwd');
		layer.open({
			title:'修改密码',
			type:1,
			anim:4,
			btnAlign:'c',
			btn:['修改','取消'],
			content:'<form class="layui-form" style="padding: 10px 10px 0;" autocomplete="off" id="modifyPasswordForm"><div class="layui-form-item"><input type="password" class="layui-input" placeholder="请输入旧密码" maxlength="16" name="oldpassword"></div><div class="layui-form-item"><input type="password" class="layui-input" placeholder="请输入新密码" maxlength="16" name="password"></div><div class="layui-row"><input type="password" class="layui-input" placeholder="请输入确认密码" maxlength="16" name="repassword"></div></form>',
			yes:function(index,layero){
				var msg = '';
				var loadIndex = layer.load();
				var oldpassword = layero.find('[name="oldpassword"]').val().trim();
				var password = layero.find('[name="password"]').val().trim();
				var repassword = layero.find('[name="repassword"]').val().trim();
				var formData = new FormData(layero.find('#modifyPasswordForm')[0]);
				switch (true) {
					case (!oldpassword):
						msg = '请输入旧密码';
						break;
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
						url:'/admin/user/modifyPwd',
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
								layer.alert(res.msg,{title:'提示',icon:1,closeBtn:false},function(index){
									layer.close(index);
									window.location.reload();
								});
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
	})
})

//打开新窗口
function addTab(_this){
	tab.tabAdd(_this);
}
