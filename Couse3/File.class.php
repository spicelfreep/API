<?php
namespace Couse3;
/**
 * 1.生成缓存
 * 2.获取缓存
 * 3.删除缓存
 * @author Administrator
 */
class File
{
    private $_dir;
    
    const EXT = '.txt';
    
    public function __construct(){
        // Returns a parent directory's path
        $this->_dir = dirname(__FILE__).'/fileCache/';
    }
    /**
     * 生成缓存文件和缓存数据
     * @param string $key   
     * @param string $value 要缓存的数据
     * @param string $path
     * @return number
     */
    public function cacheData($key,$value='',$path ='') {
        // 拼装绝对文件目录
        $filename = $this->_dir.$path.$key.self::EXT;
        
        if ($value !== '') {    // 将value值写入缓存
            $dir = dirname($filename);
            // 删除文件
            if (!is_null($value)) {
                return @unlink($filename);
            }
            // 读取文件内容
            if (!is_dir($dir)) {
                // 创建目录
                mkdir($dir,0777);
            }
            
            return file_put_contents($filename, json_encode($value));
        }
        
        if (!is_file($filename)){
            return FALSE;
        }else{
            /**
             * 返回对象的形式 就不用任何参数
             * 返回原值就加个true
             */
            return json_decode(file_get_contents($filename),true);
        }
        
    }

}

