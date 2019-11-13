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
use think\Model;

class Base extends Model
{
    /**
     * 第几页开始
     * @var int
     */
    protected static $page = 1;

    /**
     * 每页多少条
     * @var int
     */
    protected static $limit = 10;

    /**
     * 需要搜索的数据键对值
     * @var array
     */
    protected static $searchArray = [];

    /**
     * 需要搜索的字段
     * @var array
     */
    protected static $searchField = [];

    /**
     * 初始化
     */
    protected static function init()
    {
        self::$page = request()->has('page') ? request()->param('page/d') : self::$page;
        self::$limit = request()->has('limit') ? request()->param('limit/d') : self::$limit;
        self::$searchArray = request()->has('search') ? request()->param('search/a') : self::$searchArray;
        if(!empty(self::$searchArray)){
            foreach (self::$searchArray as $key => $val){
                if(strpos($key,',') != false){
                    unset(self::$searchArray[$key]);
                    $filedArr = explode(',',$key);
                    foreach ($filedArr as $k => $v) self::$searchArray[$v] = $val;
                }else{
                    array_push($filedArr,$key);
                }
            }
            self::$searchField = $filedArr;
        }
    }
}