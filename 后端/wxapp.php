<?php
/**
 * zjhj_bd模块小程序接口定义
 *
 * @author 禾匠科技
 * @url
 */
defined('IN_IA') or exit('Access Denied');

class Zjhj_bdModuleWxapp extends WeModuleWxapp {
	public function doPageTest(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '返回消息';
		$data = array();
		return $this->result($errno, $message, $data);
	}
}