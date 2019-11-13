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
use think\Db;
use think\App;
class main extends Base
{
    /**
     * 首页
     * @return mixed
     */
	public function index()
	{
		$data['this_version']	= 'v1.0';
		$data['frame']			= 'layui2.5.5 + ThinkPHP '.App::VERSION;
		$data['system'] 		= PHP_OS;//操作系统
		$data['php_version'] 	= PHP_VERSION;//php版本
		$data['max_upload_size']= ini_get('upload_max_filesize');//上传最大限制
		$data['execution_time'] = ini_get('max_execution_time').'秒';//脚本执行时间
		$data['remain_room_size']= formatBytes(disk_free_space('/'));//剩余空间大小
		$data['server'] 		 = $_SERVER["SERVER_SOFTWARE"];//服务器信息
		$data['database_version']=$this->getDataBaseVersion();//数据库版本
		$data['sapi']			 = php_sapi_name();//运行环境
		$data['ip']				 = request()->ip();//当前ip
		$this->assign('data',$data);
		return $this->fetch();
	}

    /**
     * 获取数据库版本
     * @return mixed
     */
	protected function getDataBaseVersion(){
        $version = Db::query("select version() as verion");
        return $version[0]['verion'];
	}
}