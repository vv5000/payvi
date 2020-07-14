<?php
namespace Pay\Controller;
 
/**
 * @author     mselect <445712421@qq.com>
 *2019-10-7 *
 */
class CqwapController extends PayController{
 

	//正式地址
	public $url;
	//appid
	public $shid;
	//商户应用私钥
	public $key ;


 
	public $payment_id;
 
	public $charset = 'utf-8';
 
	public function __construct()
    {
        parent::__construct();
        $this->typearray=array(
            '3' => 'zfb',
            '4' => 'zfb',
            '1' => 'wx',
            '2' => 'wx',
            '7' => 'yl',
            '8' => 'yl',
            '9' => 'yl',
            '10' => 'yl',
            '11' => 'yl',
            '12' => 'yl',
            '13' => 'yl',
            '14' => 'yl'
        );
    }
 
	/**
	 * @author     mselect <445712421@qq.com>
	 *
	 * @DateTime 2018-11-16 10:57:51
	 * 发起支付
	 */
	public function pay($array){
 		header("Content-type:text/html;charset=gbk");
 		$orderid     = I("request.pays_orderid");
        $body        = I('request.pays_productname');
        $notifyurl   = $this->_site . 'Pay_Cqwap_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Cqwap_callbackurl.html'; //返回通知
        $parameter   = array(
            'code'         => 'Cqwap', // 通道名称
            'title'        => '',
            'exchange'     => 1, // 金额比例
            'gateway'      => '',
            'orderid'      => '',
            'out_trade_id' => $orderid,
            'body'         => $body,
            'channel'      => $array,
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
        $return    = $this->orderadd($parameter);

      	//第四方接口地址 （网关）
        $url       = $return['gateway'];
        //商品名称
        $goodsname = "团购商品";
		//返回通知
        $return_url = $callbackurl; 

        //本平台订单号
        $orderid    = $return['orderid'];
        $orderuid   = "商品";
        $price      = sprintf('%.2f', $return['amount']);
        $uid        = $return['mch_id'];
        $token      = $return['signkey'];


        $data = array(
            "shid" =>$return['mch_id'],   //商户ID
            "key" => $return['signkey'],
            "orderid" => $orderid,
            "amount" => $price,
            "pay" => $this->typearray[$return['paytype']],
            "notify_url" => $notifyurl,
            "return_url" => $callbackurl,
        );

$button_name='正在跳转';
$sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='".$url."' method='POST'>";
while (list ($key, $val) = each ($data)) {
    $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
}
$sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
$sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";

$ismobile = isMobile();
if($ismobile){
    echo $sHtml;
    exit();
}else{
    $filename = "paycache/pay" . time() . ".html";
    file_put_contents($filename,$sHtml);
    echo '<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PayIng</title>
</head>
<frameset rows="100%" frameborder="no" border="0" framespacing="0">
    <frame src="'.$filename.'" name="mainFrame" id="mainFrame" title="mainFrame" />
</frameset>
<noframes><body background-color="#FFFFFF">
    </body>
</noframes></html>';
    exit();
}

}


    /*移动端判断*/
    function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
        {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset ($_SERVER['HTTP_VIA']))
        {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高
        if (isset ($_SERVER['HTTP_USER_AGENT']))
        {
            $clientkeywords = array ('nokia',
                'sony',
                'ericsson',
                'mot',
                'samsung',
                'htc',
                'sgh',
                'lg',
                'sharp',
                'sie-',
                'philips',
                'panasonic',
                'alcatel',
                'lenovo',
                'iphone',
                'ipod',
                'blackberry',
                'meizu',
                'android',
                'netfront',
                'symbian',
                'ucweb',
                'windowsce',
                'palm',
                'operamini',
                'operamobi',
                'openwave',
                'nexusone',
                'cldc',
                'midp',
                'wap',
                'mobile'
            );
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
            {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT']))
        {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
            {
                return true;
            }
        }
        return false;
    }



    //同步通知
    public function callbackurl()
    {
        $Order      = M("Order");
        if(!$_REQUEST["orderid"]){
            exit("未传任何信息！");
        }
        $pays_status = $Order->where(['pays_orderid' => $_REQUEST["orderid"]])->getField("pays_status");
        if ($pays_status == 1) {
          //  $this->EditMoney($_REQUEST["orderid"], '', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pays_callbackurl
        //} else {
            exit("交易成功！");
        }
    }
 
	/**
	 * @author     mselect <445712421@qq.com>
	 *
	 * @DateTime 2018-11-16 13:36:17 { function_description }
	 * 支付宝支付通知
	 *
	 * @param      <type>  $data   The data
	 */
	public function notifyurl(){



 		
		//验签
		//组合验签数据
		$response  = $_POST;
        file_put_contents('23.txt', json_encode($response) . PHP_EOL, FILE_APPEND);

		$rst = $this->rsaCheck($response, $response['key'] , $response['sign_type']);
		
		
		$m_Order    = M("Order");
		$order_info = $m_Order->where(['pays_orderid' =>$response['orderid']])->find(); //获取订单信息
	
		    $rows = array(
                        'out_trade_no' => $order_info['pays_orderid'],
                        'result_code' => '1',
                        'transaction_id' =>  $order_info['pays_orderid'],
                        'fromuser' => '1',
                        'time_end' => time(),
                        'total_fee' => $order_info['pays_amount'],
                        'pays_memberid' => $order_info['pays_memberid']-10000,
                        'bank_type' => '1',
                        'trade_type' => 'UPALIWAP',
                        'payname' => $order_info['pays_zh_tongdao']
                    );
                    M('Paylog')->add($rows);
		if($rst){
              $Websiteconfig = D("Websiteconfig");
       
        $list = $Websiteconfig->find();
      if($list['hdhd']==1)
      {
			
			               file_put_contents('Cqwap.txt','success' , FILE_APPEND );

               $this->EditMoney($response['orderid'], '', 0);
               exit("success");
      }
      else
      {
      $m_Order    = M("Order");
      $time       = time(); //当前时间
      $res = $m_Order->where(['pays_orderid' =>$response['orderid'], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);
      file_put_contents('Cqwap.txt','success' , FILE_APPEND );

      exit("success");
	  }
		}else {
			file_put_contents('Cqwap.txt','error2' , FILE_APPEND );
			exit("fail");
		}
	}
 
 
	
 
	public function generateSign($params, $signType = "RSA") {
        return $this->sign($this->getSignContent($params), $signType);
    }
 
     public function getSignContent($params) {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }
        unset ($k, $v);
        return $stringToBeSigned;
    }
 
    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    function characet($data, $targetCharset) {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }
 
 
     /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value) {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }
 
 
    /**
     * @author     mselect <445712421@qq.com>
     *
     * @DateTime 2018-11-16 12:05:26
     * 签名函数
     *
     * @param      <type>  $data      The data
     * @param      string  $signType  The sign type
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    protected function sign($data, $signType = "RSA") {
        $priKey=$this->rsaPrivateKey;
        
 
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION,'5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256); //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
 
    /**
     * @author     mselect <445712421@qq.com>
     *
     * @DateTime 2018-11-16 12:06:12
     * 验签函数
     *
     * @param      <type>  $data        The data    带签名数据
     * @param      <type>  $sign        The sign	要校对的签名结果
     * @param      string  $type        The type
     *
     * @return     <type>  ( description_of_the_return_value )
     */
    public function rsaCheck($data, $sign,$type = 'RSA'){
        $order_info = M('order')->where(array("pays_orderid" => $data['orderid']))->find();
        if($order_info && round($order_info['pays_amount'],2)==$data['amount'] && $data['key']==$order_info['key']){
            return true;
        }else{
            return false;
        }
    }
 
    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
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
 
?>
