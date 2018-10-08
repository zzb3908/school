<?php
/**
 * 自定义Cache服务扩展&约束（引入ThinkPHP Redis）
 * 2017-05-17 添加检测redis服务状态，如果redis服务器连接失败或宕机则使用本地文件缓存，确保缓存始终可用
 * @version		2.0 CacheService.class.php
 * @author		iclubs <iclubs@126.com>
 * @copyright	Copyright (c) 2016, Openver.com 
 * @link 		http://www.openver.com
 */

namespace app\common\service;

class Cache{
	protected $config; //array
	protected $cacheClass;
	public function __construct($options=array()) {
		$this->config = $options ? $options : config('redis');
		try {
			$this->checkRedis();
			$this->cacheClass = new \think\cache\driver\Redis($this->config);
		} catch (\Exception $e) {
			$this->cacheClass = new \think\cache\driver\File($this->config);
		}
	}
	public function getKey($string){
        // return sprintf('%X', crc32($string));
        return $string;
	}
	public function set($key, $value, $expire=null){
		if(is_null($expire)) {
            $expire = $this->config['expire'];
        }
		return $this->cacheClass->set($key,$value,$expire);
	}
	public function get($key){
		return $this->cacheClass->get($key);
	}
	public function remove($key){
		return $this->cacheClass->rm($key);
	}
	public function clear($tag = null){
		return $this->cacheClass->clear($tag);
	}

	// 用于检测redis连接是否成功
	private function checkRedis(){
		$redis = new \Redis;
		$func = $this->config['persistent'] ? 'pconnect' : 'connect';
    	$redis->$func($this->config['host'], $this->config['port'], $this->config['timeout']);
    	if ($this->config['password']) $redis->auth($this->config['password']);
        if ($this->config['select'] != 0) $redis->select($this->config['select']);
    	$redis->ping();
    	$redis->close();
	}
}