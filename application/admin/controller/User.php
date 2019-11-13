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
use think\Validate;

class User extends Base
{
    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = model('User');
    }

    /**
     * 首页
     * @return mixed
     */
    public function index(){
        if($this->request->has('tableList')){
            $result = $this->model->lists();
            $count = $this->model->counts();
            if($count){
                success('ok',$result,$count);
            }else{
                error('无数据');
            }
        }
        $this->assign('role',model('Role')->all());
        return $this->fetch();
    }

    /**
     * 列表更新
     */
    public function listUpdate(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $validateId = new \app\admin\validate\ValidateId();
            if(!$validateId->check($post)) error($validateId->getError());
            $result = $this->model->modify($post);
            if($result){
                addlog($post['id']);
                success('更新成功');
            }else{
                error('更新失败');
            }
        }
    }

    /**
     * 添加 / 修改用户
     * @return mixed
     */
    public function form(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $file = $this->request->file();
            $rule = [
                'role_id'       => 'require',
                'name'          => 'require',
                'email'         => 'email',
                'phone'         => 'mobile',
                'account'       => 'require|unique:user',
                'password'      => 'require|min:6|max:16',
                'repassword'    => 'require|confirm:password',
            ];
            $msg = [
                'role_id.require'   => '请选择所属角色',
                'name.require'      => '姓名不能为空',
                'email.email'       => '邮箱地址格式不正确',
                'phone.mobile'      => '手机号码格式不正确',
                'account.require'   => '登录账号不能为空',
                'account.unique'    => '登录账号已存在',
                'password.require'  => '登录密码不能为空',
                'password.min'      => '登录密码不能小于6个字符',
                'password.max'      => '登录密码不能大于16个字符',
                'repassword.require'=> '确认密码不能为空',
                'repassword.confirm'=> '两次输入的密码不一致',
            ];
            if(isset($post['id']) && !empty($post['id'])){
                unset($rule['password'],$rule['repassword']);
                $findData = $this->model->findData(['id'=>$post['id']],'head_img');
            }

            $validate = Validate::make($rule,$msg);
            if(!$validate->check($post)) $this->error($validate->getError());

            if(isset($file['head_img']) && !empty($file['head_img'])){
                $image = disposeImage($file['head_img'],$findData['head_img']??'');
                if($image['code'] == false) error($image['msg']);
                $post['head_img'] = $image['data'];
            }

            if(isset($post['id']) && !empty($post['id'])){
                $result = $this->model->modify($post);
                if($result != false){
                    addlog($post['id']);
                    success('修改成功');
                }else{
                    error('修改失败');
                }
            }else{
                $result = $this->model->addition($post);
                if($result != false){
                    addlog($this->model->id);
                    success('添加成功');
                }else{
                    error('添加失败');
                }
            }
        }
        $this->assign('role',model('Role')->all());
        return $this->fetch();
    }

    /**
     * 删除
     */
    public function deletes(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $validateId = new \app\admin\validate\ValidateId();
            if(!$validateId->check($post)) error($validateId->getError());
            $result = $this->model->deletes(['id'=>$post['id']]);
            if($result){
                deleteFile($post['file_path']??'');
                addlog($post['id']);
                success('删除成功');
            }else{
                error('删除失败');
            }
        }
    }

    /**
     * 重置密码
     */
    public function resetPwd(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $validate = Validate::make([
                'id'         => 'require',
                'password'   => 'require|min:6|max:16',
                'repassword' => 'require|confirm:password',
            ],[
                'id.require'        => '用户ID不能为空',
                'password.require'  => '新密码不能为空',
                'password.min'      => '新密码不能小于6个字符',
                'password.max'      => '新密码不能大于16个字符',
                'repassword.require'=> '确认密码不能为空',
                'repassword.confirm'=> '两次输入的密码不一致',
            ]);
            if(!$validate->check($post)) error($validate->getError());
            $result = $this->model->modify($post);
            if($result){
                addlog($post['id']);
                success('重置密码成功');
            }else{
                error('重置密码失败');
            }
        }
    }

    /**
     * 个人资料
     */
    public function personData(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $file = $this->request->file();
            $post['id'] = self::$loginInfo['id'];
            $validate = Validate::make([
                'email'   => 'email',
                'phone'   => 'mobile',
            ],[
                'email.email'   => '邮箱地址格式不正确',
                'phone.mobile'  => '手机号码格式不正确',
            ]);
            if(!$validate->check($post)) error($validate->getError());
            if(isset($file['head_img']) && !empty($file['head_img'])){
                $image = disposeImage($file['head_img'],self::$loginInfo['head_img']);
                if($image['code'] == false) error($image['msg']);
                $post['head_img'] = $image['data'];
            }
            $result = $this->model->modify($post);
            if($result != false){
                session('loginInfo',array_replace_recursive(self::$loginInfo,$post));
                addlog($post['id']);
                success('修改成功');
            }else{
                error('修改失败');
            }
        }
        return $this->fetch();
    }

    /**
     * 修改密码
     */
    public function modifyPwd(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $post['id'] = self::$loginInfo['id'];
            $validate = Validate::make([
                'oldpassword'=> 'require',
                'password'   => 'require|min:6|max:16',
                'repassword' => 'require|confirm:password',
            ],[
                'oldpassword.require'=>'旧密码不能为空',
                'password.require'  => '新密码不能为空',
                'password.min'      => '新密码不能小于6个字符',
                'password.max'      => '新密码不能大于16个字符',
                'repassword.require'=> '确认密码不能为空',
                'repassword.confirm'=> '两次输入的密码不一致',
            ]);
            if(!$validate->check($post)) error($validate->getError());
            $res = $this->model->where(['id'=>$post['id'],'password'=>encrypt($post['oldpassword'],'sha1')])->value('id');
            if(empty($res)) error('旧密码不正确');
            $result = $this->model->modify($post);
            if($result){
                addlog($post['id']);
                session('loginInfo',null);
                success('修改密码成功，请重新登录！');
            }else{
                error('修改密码失败');
            }
        }
    }
}