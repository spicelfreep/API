<?php

$arr = array(
    'id' => 1,
    'name' => 'daniu',
);

$data  = '输出json数据';
$newData = iconv('UTF-8', 'GBK', $data);

var_dump( json_encode($newData) );  // boolean false
echo json_encode($data);