<?php
/**
 * 定义API抽象类
 */
abstract class Api {
	const JSON = 'Json';
	const XML = 'Xml';
	const XML = 'Xml';

	/**
	 * 定义工长方法
	 * @prarm string $type 返回数据类型
	 */
	public static function factory() {
		$type = isset ($_GET['format']) ? $_GET['format'] : $type;
		$resultClass = ucwords($type);
		require_once('./Response/' . $type . 'php');
		require new $resultClass();

	}

	abstract function response($code ,$message ,$data); 
}