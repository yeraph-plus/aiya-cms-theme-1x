<?php
/**
 * 加密解密类
 * 该算法仅支持加密数字。比较适用于数据库中id字段的加密解密，以及根据数字显示url的加密。
 * @author 深秋的竹子
 * @version alpha
 * @加密原则 标记长度 + 补位 + 数字替换
 * @加密步骤：
 * 将a-z,A-Z,0-9 62个字符打乱，取前M(数字最大的位数)位作为 标记长度字符串，取第M+1 到第M+10位为数字替换字符串，剩余的为补位字符串
 * 1.计算数字长度n,取乱码的第n位作为标记长度。
 * 2.计算补位的长度，加密串的长度N -1 - n 为补位的长度。根据指定的算法得到补位字符串。
 * 3.根据数字替换字符串替换数字，得到数字加密字符串。
 * 标记长度字符 + 补位字符串 + 数字加密字符串 = 加密串
 * Usage:
 *   $obj = new XDeode(9);
 *   $e_txt = $obj->encode(123);
 *   echo $e_txt.'<br/>';
 *   echo $key->decode($e_txt);
 */

// Exit if accessed directly.
if (!defined('ABSPATH')){
    exit;
}

class XDeode{
    private $strbase = "Flpvf70CsakVjqgeWUPXQxSyJizmNH6B1u3b8cAEKwTd54nRtZOMDhoG2YLrI";
    private $key,$length,$codelen,$codenums,$codeext;
    function __construct($length = 9,$key = 2543.5415412812){
        $this->key = $key;
        $this->length = $length;
        $this->codelen = substr($this->strbase,0,$this->length);
        $this->codenums = substr($this->strbase,$this->length,10);
        $this->codeext = substr($this->strbase,$this->length + 10);
    }

    function encode($nums){
        $rtn = "";
        $numslen = strlen($nums);
        //密文第一位标记数字的长度
        $begin = substr($this->codelen,$numslen - 1,1);

        //密文的扩展位
        $extlen = $this->length - $numslen - 1;
        $temp = str_replace('.', '', $nums / $this->key);
        $temp = substr($temp,-$extlen);

        $arrextTemp = str_split($this->codeext);
        $arrext = str_split($temp);
        foreach ($arrext as $v) {
            $rtn .= $arrextTemp[$v];
        }

        $arrnumsTemp = str_split($this->codenums);
        $arrnums = str_split($nums);
        foreach ($arrnums as $v) {
            $rtn .= $arrnumsTemp[$v];
        }
        return $begin.$rtn;
    }

    function decode($code){
        $begin = substr($code,0,1);
        $rtn = '';
        $len = strpos($this->codelen,$begin);
        if($len!== false){
            $len++;
            $arrnums = str_split(substr($code,-$len));
            foreach ($arrnums as $v) {
            $rtn .= strpos($this->codenums,$v);
            }
        }

        return $rtn;
    }
    /*/加密函数  
    function encrypt($data,$key='CHENI'){  
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";  
        $nh = rand(0,64);  
        $ch = $chars[$nh];  
        $mdKey = md5($key.$ch);  
        $mdKey = mb_substr($mdKey,$nh%8, $nh%8+7);  
        $data= base64_encode($data);  
        $tmp = '';  
        $i=0;$j=0;$k = 0;  
        for ($i=0; $i<strlen($data); $i++) {  
        $k = $k == strlen($mdKey) ? 0 : $k;  
        $j = ($nh+strpos($chars,$data[$i])+ord($mdKey[$k++]))%64;  
        $tmp .= $chars[$j];  
        }  
        return urlencode($ch.$tmp);  
    }
    //解密函数  
    function decrypt($data,$key='CHENI'){
        $txt = urldecode($data);  
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";  
        $ch = $txt[0];  
        $nh = strpos($chars,$ch);  
        $mdKey = md5($key.$ch);  
        $mdKey = mb_substr($mdKey,$nh%8, $nh%8+7);  
        $txt = substr($txt,1);  
        $tmp = '';  
        $i=0;$j=0; $k = 0;  
        for ($i=0; $i<strlen($txt); $i++) {  
        $k = $k == strlen($mdKey) ? 0 : $k;  
        $j = strpos($chars,$txt[$i])-$nh - ord($mdKey[$k++]);  
        while ($j<0) $j+=64;  
        $tmp .= $chars[$j];  
        }  
        return base64_decode($tmp);  
    }
    */
}
