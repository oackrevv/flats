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
use think\Db;

function addlog($object_id='',$title='未设置',$description=''){
    $request = request();
    $ip = $request->ip();
    $uid = session('loginInfo.id');
    $url = $request->url();
    $url1 = substr($url,1);
    $find = Db::name('f_menu')->whereOr('href',$url)->whereOr('href',$url1)->find();
    if(!empty($object_id)){
        $object_info['object_id'] = (string)$object_id;
    }
    if(isset($find['id'])){
        $object_info['menu_id'] = (string)$find['id'];
    }
    $data = [
        'uid'           => $uid,
        'title'         => $find['name']??$title,
        'href'          => $find['href']??$url1,
        'operation_ip'  => $ip,
        'description'   => $description,
        'object_info'   => isset($object_info) ? json_encode($object_info,256) : '',
    ];
    return Db::name('f_log')->insert($data);
}

/**
 * 处理上传的图片
 * @param string $file
 * @param string $old_file_path
 * @return array
 */
function disposeImage($file='',$old_file_path=''){
    $size     = 5242880;//5mb
    $ext      = 'jpg,jpeg,png,gif';
    $validate = ['size'=>$size,'ext'=>$ext];
    if(empty($file)) return false;// 直接返回
    $path = 'upload/'.date('Ym');
    if(is_array($file)){
        $num   = 0;
        $total = count($file);
        $err   = '';
        $data  = [];
        foreach ($file as $val){
            $info = $val->validate($validate)->move($path);
            if($info){
                $filePath = str_replace('\\','/',$info->getSaveName());
                $data[] = '/'.$path.'/'.$filePath;
            }else{
                $num += 1;
                $err = $val->getError();
            }
            unset($info);//需要解除图片进程占用，不然后面删不了已经上传的图片
        }
        if($num === $total || $num != 0){
            if($num != 0)foreach ($data as $val)@unlink($_SERVER['DOCUMENT_ROOT'].$val);//如有发生错误，就删除已经上传的
            $result = ['code'=>0,'msg'=>$err];
        }else{
            $result = ['code'=>1,'msg'=>'上传成功','data'=>$data];
        }
    }else{
        $info = $file->validate($validate)->move($path);
        if($info){
            $filePath = str_replace('\\','/',$info->getSaveName());
            $data = '/'.$path.'/'.$filePath;
            $result = ['code'=>1,'msg'=>'上传成功','data'=>$data];
        }else{
            $result = ['code'=>0,'msg'=>$file->getError()];
        }
    }
    // 有没有需要删除的旧文件
    if($old_file_path != '') deleteFile($old_file_path);
    return $result;
}

/**
 * 删除服务器文件
 * @param string $file_path
 */
function deleteFile($file_path=''){
    if($file_path != ''){
        if(strpos($file_path,'|')){
            $file_path = explode("|",$file_path);
        }
        if(is_array($file_path)){
            foreach ($file_path as $key => $val)@unlink($_SERVER['DOCUMENT_ROOT'].strstr($val,'/upload'));
        }else{
            @unlink($_SERVER['DOCUMENT_ROOT'].strstr($file_path,'/upload'));
        }
    }
}

/**
 * 生成32位唯一标识
 * @return string
 */
function uuid(){
    return strtolower(md5(microtime(uniqid(rand(),true))));
}

/**
 * 生成随机字符串
 * @param int $len 生成的字符串长度
 * @return string
 */
function random_string($len = 6){
    $chars = [
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
    ];
    $charsLen = count($chars) - 1;
    shuffle($chars);    // 将数组打乱
    $output = "";
    for ($i = 0; $i < $len; $i++) {
        $output .= $chars[mt_rand(0, $charsLen)];
    }
    return $output;
}