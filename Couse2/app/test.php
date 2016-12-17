<?php
// 引入输出输出方式类
require ('../Response.class.php');

$arr = array(
    'id' => 1,
    'name' => 'tiancai',
    'hobby' => '扣鼻屎',
    'type' => array(1,2,3,4),
);
// 调用json方法
//Response::json(200,'数据传输成功',$arr);
// Response::axml();
// Response::xml();
// *只管一个入口，只是多加一个参数  并且可以调试
Response::dataShow(200,'数据传输成功',$arr);
