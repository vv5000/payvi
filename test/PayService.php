<?php
class PayService
{
    protected static $baseUrl  = 'http://api.fmpay.com.cn';
    protected static $mchId    = '100268';
    protected static $apiKey   = 'u9sjMHDewG6gJba4fv0mftoTphIDi3lw';

    public function __construct($mchId = '', $apiKey = '')
    {
        self::$mchId    = !empty($mchId) ? $mchId : self::$mchId;
        self::$apiKey   = !empty($apiKey) ? $apiKey : self::$apiKey;
    }

   /**
    * 请求支付
    * @param  [type] $totalFee   [description]
    * @param  [type] $outTradeNo [description]
    * @param  [type] $orderName  [description]
    * @param  [type] $payType    [description]
    * @return [type]             [description]
    */
    public function doPay($payType, $totalFee, $outTradeNo, $orderName, $returnUrl, $notifyUrl)
    {
        $unified = [
            "mch_id"        => self::$mchId,
            "out_trade_no"  => $outTradeNo,
            "subject"       => $orderName,
            "body"          => $orderName,
            "amount"        => $totalFee,
            "channel"       => 'wxpay_mch', //支付方式小写wx.scan
            "client_ip"     => $_SERVER['REMOTE_ADDR'],
            "return_url"    => $returnUrl,
            "notify_url"    => $notifyUrl,
            "extparam"      => [
                "noncestr"  => self::createNonceStr()
            ],
            "timestamp"     => time(),

        ];
        $unified['sign'] = self::getSign($unified);
        echo "<pre>";
        var_dump($unified);
        $response = self::curlPost(self::$baseUrl.'/pay/unifiedorder', $unified);
        $response = json_decode($response,true);
        
         echo "<pre>";
        var_dump($response);
        return self::verify($response);
    }

    /**
     * 订单查询
     * @param  [type] $order_no [description]
     * @param  [type] $channel  [description]
     * @return [type]           [description]
     */
    public function query($order_no,$channel)
    {
        $param = [
            "mch_id"    => self::$mchId,
            "out_trade_no"  => $order_no,
            "channel"   => $channel,
            "timestamp"     => time(),
        ];
        $param['sign'] = self::getSign($param);
        $response = self::curlPost(self::$baseUrl.'/pay/orderquery', $param);
        // dump($response);
        return self::verify($response);
    }

    /**
     * 接收异步通知
     * @return [type] [description]
     */
    public function notify()
    {
        $response = $_REQUEST;
        file_put_contents('notify-data.log', json_encode($response));
        // $str = '{"result_code":"OK","result_msg":"SUCCESS","charge":{"puid":0,"out_trade_no":"201905090139021127726473","subject":"\u652f\u4ed8\u6d4b\u8bd5","body":"\u652f\u4ed8\u6d4b\u8bd5","channel":"ali_pc","extra":"{\"noncestr\":\"CJQTWbjEF0gWhazf\"}","amount":"0.010","income":"0.000","user_in":"0.000","agent_in":"0.000","platform_in":"0.000","currency":"CNY","client_ip":"219.133.46.19","return_url":"http:\/\/pay.yeshidaji.com\/cashier\/Return.php","notify_url":"http:\/\/pay.yeshidaji.com\/cashier\/Notify.php","sign":"C2671A39BC574A051D998E732D87B124"}}';
        // $response = json_decode($response,true);
        return self::verify($response);
    }

    /**
     * 签名验证
     * @param  [type] $return [description]
     * @return [type]         [description]
     */
    public static function verify($return)
    {
        if(isset($return['result_code']) && $return['result_code'] == 'OK' && $return['result_msg'] == 'SUCCESS'){
            $charge = $return['charge'];
            $sign = self::getSign($charge);
            if($sign === $charge['sign']){
                return $return;
            }else{
                return false;
            }
        }else{
            return $return;
        }
    }

    /**
     * api数据签名
     * @param  [type] $params [description]
     * @param  [type] $key    [description]
     * @return [type]         [description]
     */
    public static function getSign($params)
    {
        ksort($params, SORT_STRING);
        $unSignParaString = self::formatQueryParaMap($params);
        $signStr = strtoupper(md5($unSignParaString . "&key=" . self::$apiKey));
        return $signStr;
    }

    /**
     * 字符串排序
     * @param  [type] $paraMap [description]
     * @return [type]          [description]
     */
    protected static function formatQueryParaMap($paraMap)
    {
        $buff = "";
        ksort($paraMap);

        foreach ($paraMap as $k => $v) {
            $buff .= ($k != 'sign' && $v != '' && !is_array($v)) ? $k.'='.$v.'&' : '';
        }
        $reqPar = '';
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }
        return $reqPar;
    }

    /**
     * 随机字符
     * @param  integer $length [description]
     * @return [type]          [description]
     */
    public static function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * [post请求]
     * @param  string $url      [description]
     * @param  string $postData [description]
     * @param  array  $options  [description]
     * @return [type]           [description]
     */
    public static function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_PROXY, "127.0.0.1"); //代理服务器地址
        // curl_setopt($ch, CURLOPT_PROXYPORT, 8888); //代理服务器端口
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
// ===================================================================================
/**
 * [dump description]
 * @param  [type]  $var   [description]
 * @param  boolean $echo  [description]
 * @param  [type]  $label [description]
 * @param  [type]  $flags [description]
 * @return [type]         [description]
 */
function dump($var, $echo = true, $label = null, $flags = ENT_SUBSTITUTE)
{
    $label = (null === $label) ? '' : rtrim($label) . ':';

    ob_start();
    var_dump($var);
    $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', ob_get_clean());

    if (!extension_loaded('xdebug')) {
        $output = htmlspecialchars($output, $flags);
    }

    $output = '<pre>' . $label . $output . '</pre>';

    if ($echo) {
        echo($output);
        return;
    }

    return $output;
}
