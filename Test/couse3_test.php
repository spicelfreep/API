<?php
use Couse3\File;
require ('../Couse3/File.class.php');
$arr = array(
    'id' => 1,
    'name' => 'tiancai',
    'hobby' => '扣鼻屎',
    'type' => array(1,2,3,4),
);

$couse_file = new File();
/* 
if ($couse_file->cacheData('inde_mk_cache')) {
    $content = $couse_file->cacheData('inde_mk_cache');
    var_dump($content);
}else{
    echo 'error';
} */

/* if ($couse_file->cacheData('inde_mk_cache',$arr)) {
    echo 'success';
}else{
    echo 'error';
} */

if ($couse_file->cacheData('inde_mk_cache',null)) {
 echo 'success';
 }else{
 echo 'error';
 } 