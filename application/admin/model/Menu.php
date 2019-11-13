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

class Menu extends Base
{
    /**
     * 指定数据表
     * @var string
     */
    protected $table = 'f_menu';

    /**
     * 去掉指定字段
     * @var array
     */
    protected $hidden = [];

    /**
     * 排序规则
     * @var string
     */
    protected $order = 'sort asc,create_time desc';

    /**
     * 菜单列表
     * @param array $where
     * @return array
     */
    public function lists($where=[]){
        $result = $this->where($where)->order($this->order)->field('*,name as title')->select();
        return $result;
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
     * 删除数据
     * @param $where
     * @return int
     */
    public function deletes($where){
        $result = $this->where($where)->delete();
        return $result;
    }

    /**
     * 处理菜单
     * @param $list
     * @param int $pid
     * @param int $level
     * @param bool $is_html
     * @return array
     */
    public function disposeMenu($list,$pid=0,$level=0,$is_html=false){
        $result = [];
        foreach ($list as $key => $val){
            if($pid == $val['pid']){
                unset($list[$key]);
                $val['level'] = $level;
                if($is_html){
                    $val['html'] = str_repeat('&nbsp;',$level*8);
                    if($level >= 1){$val['html'] .= "L&nbsp;";}
                    $result[] = $val;
                    $result = array_merge($result,$this->disposeMenu($list,$val['id'],$level+1,true));
                }else{
                    $val['children'] = $this->disposeMenu($list,$val['id'],$level+1);
                    if($pid == 0){
                        $result[$val['id']] = $val;
                    }else{
                        $result[] = $val;
                    }
                }
            }
        }
        return $result;
    }
}