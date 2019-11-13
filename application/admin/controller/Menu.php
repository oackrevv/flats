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

class Menu extends Base
{
    /**
     * 初始化
     */
    public function initialize()
    {
        parent::initialize();
        $this->model = model('Menu');
    }

    /**
     * 菜单首页
     * @return mixed
     */
    public function index(){
        if($this->request->has('tableList')){
            $result = $this->model->lists();
            success('ok',$result,count($result));
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
     * 添加 / 修改菜单
     * @return mixed
     */
    public function form(){
        if($this->request->isPost()){
            $post = $this->request->post();
            $validate = Validate::make([
                'name' => 'require',
            ],[
                'name.require' => '请输入名称',
            ]);
            if(!$validate->check($post)) $this->error($validate->getError());
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
        $menu = $this->menuList(true);
        $this->assign('menu',$menu);
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
            $find = $this->model->findData(['pid'=>$post['id']]);//查看有没有子菜单
            if(empty($find)){
                $result  = $this->model->deletes(['id'=>$post['id']]);
                if($result){
                    addlog($post['id']);
                    success('删除成功');
                }else{
                    error('删除失败');
                }
            }else{
                error('请先删除子菜单');
            }
        }
    }

    /**
     * 获取菜单列表
     * @return mixed
     */
    public function menuList($is_html=false){
        $where = [
            ['status','eq','1'],
            ['type','in','1,2'],
        ];
        if(self::$roleId !== 1) array_push($where,['id','in',self::$roleAuth]);
        $lists = $this->model->lists($where);
        $result = $this->model->disposeMenu($lists,0,0,$is_html);
        return $result;
    }

    /**
     * 获取顶部菜单
     * @return mixed
     */
    public function topMenu(){
        $where = [
            ['status','eq','1'],
            ['type','eq','1'],
            ['pid','eq','0'],
        ];
        if(self::$roleId !== 1) array_push($where,['id','in',self::$roleAuth]);
        $result = $this->model->lists($where);
        return $result;
    }
}