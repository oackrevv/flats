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

class User extends Base
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'f_user';

    /**
     * 去掉指定字段
     * @var array
     */
    protected $hidden = ['password','update_time','delete_time'];

    /**
     * 追加属性
     * @var array
     */
    protected $append = ['sex_text'];

    /**
     * 排序规则
     * @var string
     */
    protected $order = 'id asc,create_time desc';

    /**
     * 关联角色
     * @return \think\model\relation\HasOne
     */
    public function role(){
        return $this->hasOne('Role','id','role_id')->bind(['role_name'=>'name']);
    }

    /**
     * 数据列表
     * @param array $where
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function lists($where=[]){
        $result = $this->with(['role'])->withSearch(self::$searchField,self::$searchArray)->where($where)->page(self::$page,self::$limit)->order($this->order)->select();
        return $result;
    }

    /**
     * 数据总数
     * @param array $where
     * @return float|string
     */
    public function counts($where=[]){
        return $this->withSearch(self::$searchField,self::$searchArray)->where($where)->count();
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
    public function modify($data,$where=[]){
        $result = $this->allowField(true)->isUpdate(true)->save($data,$where);
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

    // +----------------------------------------------------------------------
    // | 搜索器
    // +----------------------------------------------------------------------
    public function searchNameAttr($query, $value, $data){
        if($value != ''){
            $query->where('name','like',"%{$value}%");
        }
    }

    public function searchAccountAttr($query, $value, $data){
        if($value != ''){
            $query->whereOr('account','like',"%{$value}%");
        }
    }

    public function searchStatusAttr($query, $value, $data){
        if($value != ''){
            $query->where('status','eq',$value);
        }
    }

    public function searchRoleIdAttr($query, $value, $data){
        if($value != ''){
            $query->where('role_id','eq',$value);
        }
    }

    // +----------------------------------------------------------------------
    // | 修改器
    // +----------------------------------------------------------------------
    public function setPasswordAttr($value,$data){
        return encrypt($value,'sha1');
    }

    // +----------------------------------------------------------------------
    // | 获取器
    // +----------------------------------------------------------------------
    public function getSexTextAttr($value,$data){
        $array = array('保密','男','女');
        return $array[$data['sex']];
    }
}