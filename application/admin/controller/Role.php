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

class Role extends Base
{
    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = model('Role');
    }

    /**
     * 角色首页
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
     * 添加 / 修改角色
     * @return mixed
     */
    public function form(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $validate = Validate::make([
                'name' => 'require',
            ],[
                'name.require' => '请输入角色名称',
            ]);
            if(!$validate->check($post)) $this->error($validate->getError());
            $post['permission'] = isset($post['permission']) && !empty($post['permission']) ? implode($post['permission'],',') : '';
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
            $result  = $this->model->deletes(['id'=>$post['id']]);
            if($result){
                addlog($post['id']);
                success('删除成功');
            }else{
                error('删除失败');
            }
        }
    }

    /**
     * 获取所有菜单
     * @return mixed
     */
    public function allMenuList(){
        $permission = explode(',',$this->request->post('permission'));
        $result = $this->disposeMenu(model('Menu')->lists(['status'=>'1']),$permission,0,0);
        return $result;
    }

    /**
     * 处理菜单
     * @param $list
     * @param $permission
     * @param int $pid
     * @param int $level
     * @return array
     */
    protected function disposeMenu($list,$permission,$pid=0,$level=0){
        $result = [];
        foreach ($list as $key => $val){
            if($pid == $val['pid']){
                unset($list[$key]);
                $val['level'] = $level;
                $val['field'] = 'permission[]';
                $children = $this->disposeMenu($list,$permission,$val['id'],$level+1);
                if(!empty($permission)){
                    if(in_array($val['id'],$permission)){
                        if(empty($children)){
                            $val['checked'] = true;
                        }
                        $val['spread'] = true;
                    }
                }
                $val['children'] = $children;
                $result[] = $val;
            }
        }
        return $result;
    }
}