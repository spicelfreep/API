<?php
/**
 * 输出通信数据API
 * @author Administrator
 */
class Response
{
    const JSON = 'json';
    /**
     * 综合通讯方式封装(默认是json)
     * @param number $code
     * @param string $message
     * @param array $data
     * @param string $type
     * @return string
     */
    public static function dataShow($code,$message='',$data=array(),$type=self::JSON){
        if (!is_numeric($code)) {   // 判断状态码
            return '';
        }
        
        $type = isset($_GET['format'])? $_GET['format'] : self::JSON ;
        
        $result = array(            // 组装数据
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        
        if ($type == 'json') {      // IE 看不到
            self::jsonEncode($code,$message,$data);
            exit;
        } elseif ($type == 'array'){    // 调试模式
            var_dump($result);
        } elseif ($type == 'xml'){      //
            self::xmlEncode($code,$message,$data);
            exit();
        } else {
            // TODO 后续业务增加
        }
        
    }
    /**
     * 按json方式输出通信数据
     * @param number $code 状态码
     * @param string $message 提示信息
     * @param array $data 数据
     * @return string
     */
    public static function jsonEncode($code=0,$message='',$data=array()) {
        if (!is_numeric($code)) {   // 判断状态码
            return '';
        }
        $result = array(            // 组装数据
            'code' => $code,
            'message' => $message,
            'data' => $data
        ); 
        
        echo json_encode($result);  // 以json方式输出数据
        
        exit;
    }
    /**
     * 按xml方式输出通信数据1
     * @param number $code 状态码
     * @param string $message 提示信息
     * @param array $data  数据
     * @return string
     */
    public static function xml() {

        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml .= "<root>\n";
        $xml .= "<code>200</code>\n";
        $xml .= "<message>数据返回成功</message>\n";
        $xml .= "<data>\n";
        $xml .= "<id>2</id>\n";
        $xml .= "<name>jok</name>\n";
        $xml .= "<hobby>扣鼻屎</hobby>\n";
        $xml .= "</data>\n";
        $xml .= "</root>";
        
        echo $xml;
    }
    /**
     * 按xml方式输出通信数据1
     * @param number $code 状态码
     * @param string $message 提示信息
     * @param array $data  数据
     * @return string 返回值
     */
    public static function xmlEncode($code=0,$message='',$data=array()) {
    
        if (!is_numeric($code)) {   // 判断状态码
            return '';
        }
        $result = array(            // 组装数据
            'code' => $code,
            'message' => $message,
            'data' => $data
        );
        header("Content-Type:text/xml");
        $xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
        $xml.="<root>\n";
        $xml.=self::xmlToEncode($result);
        $xml.="</root>\n";

        echo $xml;
    }
    /**
     * 递归遍历节点中的数据
     * @param array $data
     * @return string
     */
    public static function xmlToEncode($data=array()){
        
        $xml = '';$attr = '';
        foreach ($data as $key => $v){
            if (is_numeric($key)) {
                $attr = "id='{$key}'";
                $key = 'item';
            }
            $xml.="<{$key} {$attr}>";
            $xml.= is_array($v) ? self::xmlToEncode($v) : $v;
            $xml.="</{$key}>";
        }
        return $xml;
    }
    /**
     * 按xml方式输出通信数据2
     * @param number $code
     * @param string $message
     * @param array $data  
     */
    public static function axml($code=0,$message='',$data=array()) {
        $dom = new DOMDocument('1.0','UTF-8');
        $element = $dom->createElement('test','这是我的节点');
        // We insert the new element as root (child of the document)
        $dom->appendChild($element);
        echo $dom->saveXML();
    }
    
}

