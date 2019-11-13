<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 无忧 <905821135@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\controller;
use think\Controller;

class Base extends Controller
{
    /**
     * 当前登录信息
     * @var array
     */
    protected static $loginInfo = [];

    /**
     * 当前角色ID
     * @var null
     */
    protected static $roleId = null;

    /**
     * 当前角色权限
     * @var null
     */
    protected static $roleAuth = null;

    /**
     * 初始化
     */
	public function initialize()
	{
        if(session('?loginInfo') === false) $this->redirect('/admin/common/login');
        self::$loginInfo = session('loginInfo');
        self::$roleId    = self::$loginInfo['role_id'];
        self::$roleAuth  = model('Role')->where('id', self::$roleId)->value('permission');
	    if($this->request->isAjax() && $this->request->has('checkPermission') && self::$roleId !== 1){
	        $url = substr(str_replace('checkPermission=true','',$this->request->url()),0,-1);
            $url1 = substr($url,1);
            $memu_id = model('Menu')->whereOr('href',$url)->whereOr('href',$url1)->value('id');
            if(empty($memu_id)){
                error('无操作权限');
            }
            if(strpos(self::$roleAuth,(string)$memu_id) === false){
                error('无操作权限');
            }
        }
	    $this->assign('loginInfo',self::$loginInfo);
	}
}