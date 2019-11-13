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
namespace app\admin\model;

class Role extends Base
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'f_role';

    /**
     * 去掉指定字段
     * @var array
     */
    protected $hidden = [];

    /**
     * 排序规则
     * @var string
     */
    protected $order = 'id asc,create_time desc';

    /**
     * 数据列表
     * @param array $where
     * @return array
     */
    public function lists($where=[]){
        $result = $this->where($where)->page(self::$page,self::$limit)->order($this->order)->select();
        return $result;
    }

    /**
     * 所有数据
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function all(){
        $result = $this->where('status',1)->order($this->order)->select();
        return $result;
    }

    /**
     * 数据总数
     * @param array $where
     * @return float|string
     */
    public function counts($where=[]){
        return $this->where($where)->count();
    }

    /**
     * 查找一条数据
     * @param $where
     * @param string $field
     * @return mixed
     */
    public function findData($where,$field="*"){
        $result = $this->where($where)->field($field)->find();
        return $result;
    }

    /**
     * 添加
     * @param $data
     * @return bool
     */
    public function addition($data){
        $result = $this->allowField(true)->isUpdate(false)->save($data);
        return $result;
    }

    /**
     * 修改
     * @param $data
     * @return bool
     */
    public function modify($data){
        $result = $this->allowField(true)->isUpdate(true)->save($data);
        return $result;
    }

    /**
     * 删除
     * @param $where
     * @return int
     */
    public function deletes($where){
        $result = $this->where($where)->delete();
        return $result;
    }

}