<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2018/1/30 0030
 * Time: 10:51
 */
require_once 'utils.php';
class utils
{
    /**
     * 加密方式
     */
    public static function Sign($params, $apiKey)
    {
        ksort($params);
        $string = "";
        foreach ($params as $name => $value) {
            $string .= $name . '=' . $value . '&';
        }
        $string = substr($string, 0, strlen($string) -1 );
        $string .= $apiKey;
        return strtoupper(sha1($string));
    }
    /**
     * 模拟post请求
     */
    public static function postHtml($Url, $PostArry){
        if(!is_array($PostArry)){
            throw new Exception("无法识别的数据类型【PostArry】");
        }
        $FormString = "<body onLoad=\"document.actform.submit()\">正在处理，请稍候.....................<form  id=\"actform\" name=\"actform\" method=\"post\" action=\"" . $Url . "\">";
        foreach($PostArry as $key => $value){
            $FormString .="<input name=\"" . $key . "\" type=\"hidden\" value='" . $value . "'>\r\n";
        }
        $FormString .="</form></body>";
        return $FormString;
    }
    /**
     * 模拟get请求
     */
    public static function getHtml($Url, $PostArry){
        if(!is_array($PostArry)){
            throw new Exception("无法识别的数据类型【PostArry】");
        }
        $FormString = "<body onLoad=\"document.actform.submit()\">正在处理，请稍候.....................<form  id=\"actform\" name=\"actform\" method=\"get\" action=\"" . $Url . "\">";
        foreach($PostArry as $key => $value){
            $FormString .="<input name=\"" . $key . "\" type=\"hidden\" value='" . $value . "'>\r\n";
        }
        $FormString .="</form></body>";
        return $FormString;
    }
    /**
     * 打印成text
     */
    public static function LogWirte($Astring)
    {
        $path = dirname(__FILE__);
        $path = $path."/Log/";
        $file = $path."Log".date('Y-m-d',time()).".txt";
        if(!is_dir($path)){	mkdir($path); }
        $LogTime = date('Y-m-d H:i:s',time());
        if(!file_exists($file))
        {
            $logfile = fopen($file, "w") or die("Unable to open file!");
            fwrite($logfile, "[$LogTime]:".$Astring."\r\n");
            fclose($logfile);
        }else{
            $logfile = fopen($file, "a") or die("Unable to open file!");
            fwrite($logfile, "[$LogTime]:".$Astring."\r\n");
            fclose($logfile);
        }
    }
    /**
     * 获取真实IP
     */
    public static function get_onlineip() {
        $ch = curl_init('http://www.ip138.com/ip2city.asp');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $a = curl_exec($ch);
        preg_match('/\[(.*)\]/', $a, $ip);
        return $ip;
    }
    /**
     * 打印参数
     */
    public static function PayWirte($OrderNo, $content)
    {
        $path = dirname(__FILE__);
        $path = $path."/Log/";
        $file = $path.$OrderNo.".txt";
        if(!is_dir($path)){	mkdir($path); }
        $logfile = fopen($file, "w") or die("Unable to open file!");
        fwrite($logfile, json_encode($content));
        fclose($logfile);
    }
    /**
     * curl模拟post
     */
    public static function curl_post($data, $url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT,'Opera/9.80 (Windows NT 6.2; Win64; x64) Presto/2.12.388 Version/12.15');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $html = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno' . curl_error($curl);
        }
        curl_close($curl);
        return $html;
    }
}