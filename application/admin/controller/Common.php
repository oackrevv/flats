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
use think\Validate;

class Common extends Controller
{
    /**
     * 登录
     * @return mixed
     */
    public function login(){
        if($this->request->isPost()){
            $model = model('User');
            $post = $this->request->post();
            $validate = Validate::make([
                'account'       =>  'require',
                'password'      =>  'require',
                'captcha|验证码' =>  'require|captcha'
            ],[
                'account.require'   =>  '用户名不能为空',
                'password.require'  =>  '密码不能为空',
            ]);
            if(!$validate->check($post)) error($validate->getError());
            $where = [
                ['account','eq',$post['account']],
                ['password','eq',encrypt($post['password'],'sha1')],
            ];

            $user = $model->findData($where,'id,role_id,status,login_num');
            if(empty($user)) error('账号或密码错误!');
            if(!$user['status']) error('账号已被禁用，无法登录!');

            $role = model('Role')->findData(['id'=>$user['role_id']],'status,name');
            if(empty($role)) error('所属角色不存在，无法登录!');
            if(!$role['status']) error('所属角色已被禁用，无法登录!');

            $model->modify(['login_ip'=>request()->ip(),'login_time'=>currentTime(),'login_num'=>$user['login_num']+1],$where);
            $loginInfo = $model->findData($where)->toArray();
            $loginInfo['role_name']  = $role['name'];
            session('loginInfo',$loginInfo);
            addlog($user->id,'登录系统');
            $this->success('登录成功','/admin');
        }
        return $this->fetch();
    }

    /**
     * 退出
     */
    public function logout(){
        addlog(session('loginInfo.id'),'退出登录');
        session('loginInfo',null);
        $this->success('退出成功','/admin/common/login');
    }

    /**
     * icon图标界面
     * @return mixed
     */
    public function icon(){
        return $this->fetch();
    }

    /**
     * 解锁
     */
    public function unlock(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $validate = Validate::make([
                'password'   => 'require',
            ],[
                'password.require'  => '密码不能为空',
            ]);
            if(!$validate->check($post)) error($validate->getError());
            $res = model('User')->where(['id'=>session('loginInfo.id'),'password'=>encrypt($post['password'],'sha1')])->value('id');
            if(empty($res)) error('密码错误，请重新输入');
            success('解锁成功');
        }
    }

    /**
     * 清除缓存
     */
    public function clearCache(){
        \think\facade\Cache::clear();
        success('清除缓存成功');
    }
}