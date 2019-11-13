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

// 应用公共文件
use think\facade\Cache;

/**
 * 获取天气
 * @author Oackrevv
 * @param  string $version v6今天天气 v1七天天气
 * @return [array]
 */
function getWeather($version="v6"){
    $cache_name = "weather_info_$version";
	$weather = Cache::get($cache_name);
	if(empty($weather)){
		$ak       = config('custom.BaiDuConfig.map_ak');
		$location = @file_get_contents("http://api.map.baidu.com/location/ip?ak=$ak");
		$location = json_decode($location,true);
		$city     = '北京';
	    if(!$location['status']){
			$province = $location['content']['address_detail']['province'];
			$city     = $location['content']['address_detail']['city'];
			$address  = $province.$city;
	    }
	    // 去掉市或区
	    if(strstr($city,"市",true)){
	    	$city = strstr($city,"市",true);
	    }else if(strstr($city,"区",true)){
	    	$city = strstr($city,"区",true);
	    }
        $app_id  = config('custom.WeatherConfig.app_id');
        $app_secret  = config('custom.WeatherConfig.app_secret');
		$weather = @file_get_contents("https://www.tianqiapi.com/api/?appid=$app_id&appsecret=$app_secret&version=$version&city=$city");
		$weather = json_decode($weather,true);
		$weather['wea_img'] = '/static/common/image/weather/'.$weather['wea_img'].'.png';
		$weather['address'] = isset($address) ? $address : $weather['city'];
	    Cache::set($cache_name,$weather,3600);
	}
    return $weather;
}

/**
 * 单位转换
 * @author Oackrevv
 * @param  [type] $size
 * @return [type]
 */
function formatBytes($size) { 
    $units = array(' B', ' KB', ' MB', ' GB',' TB'); 
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024; 
    return round($size, 2).$units[$i]; 
}

/**
 * 时间计算(年，月，日，时，分，秒)
 * @param $time 可以是当前时间/你要传进来的时间
 * @return 计算好的时间
 */
function format_date($time){
    $t=time()-$time;
    $f=array(
        '31536000'=>'年',
        '2592000'=>'个月',
        '604800'=>'星期',
        '86400'=>'天',
        '3600'=>'小时',
        '60'=>'分钟',
        '1'=>'秒'
    );
    foreach ($f as $k=>$v)    {
        if (0 !=$c=floor($t/(int)$k)) {
            return $c.$v.'前';
        }
    }
}

/**
 * 加密
 * @param string $str
 * @param string $way
 * @return string
 */
function encrypt($str='',$way='md5'){
    return $way == 'sha1' ? sha1($str) : md5($str);
}

/**
 * 当前时间
 * @return false|string
 */
function currentTime(){
    return date('Y-m-d H:i:s',time());
}

/**
 * 操作成功的快捷方法
 * @param mixed $msg 提示信息
 * @param mixed $data 返回的数据
 * @param array $header 发送的Header信息
 * @return void
 */
function success($msg = '', $data = '',$count = '', array $header = [])
{
    $code   = 1;
    $result = [
        'code' => $code,
        'msg'  => $msg,
    ];
    if($count != ''){
        $result['count'] = $count;
    }
    if($data != ''){
        $result['data'] = $data;
    }
    $type     = "json";
    $response =  \think\Response::create($result, $type)->header($header);
    throw new \think\exception\HttpResponseException($response);
}

/**
 * 操作错误的快捷方法
 * @param mixed $msg 提示信息,若要指定错误码,可以传数组,格式为['code'=>您的错误码,'msg'=>'您的错误消息']
 * @param mixed $data 返回的数据
 * @param array $header 发送的Header信息
 * @return void
 */
function error($msg = '', $data = '', array $header = [])
{
    $code = 0;
    if (is_array($msg)) {
        $code = $msg['code'];
        $msg  = $msg['msg'];
    }
    $result = [
        'code' => $code,
        'msg'  => $msg,
    ];
    if($data != ''){
        $result['data'] = $data;
    }
    $type     = "json";
    $response =  \think\Response::create($result, $type)->header($header);
    throw new \think\exception\HttpResponseException($response);
}