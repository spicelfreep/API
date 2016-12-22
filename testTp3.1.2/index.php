<?php 
//ini_set('memory_limit', '12M'); //设置PHP最大使用内存
define('APP_DEBUG',True); //开启调试模式
define('THINK_PATH', "./Core/");
define('APP_NAME', "ap");
define('APP_PATH', "./ap/");

//require_once('360_safe3.php');//360安全防护代码
require THINK_PATH.'ThinkPHP.php';
