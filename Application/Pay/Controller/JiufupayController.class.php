<?php
namespace Pay\Controller;
 
/**
 * @author     mselect <445712421@qq.com>
 *2019-11-9 *
 */
class JiufupayController extends PayController{
 

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
            '3' => '0',  //支付宝
            '1' => '1',  //微信
            '14' => '3',   //银行卡
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
        $notifyurl   = $this->_site . 'Pay_Jiufupay_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Jiufupay_callbackurl.html'; //返回通知

        $parameter   = array(
            'code'         => 'Jiufupay', // 通道名称
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
        $url       = $return['gateway'].'/trade/placeOrder';
        //商品名称

        //本平台订单号
        $orderid    = $return['orderid'];
        $orderuid   = "商品";
        $price      = sprintf('%.2f', $return['amount']);
        $uid        = $return['mch_id'];
        $token      = $return['signkey'];


        $merchantUserID		= $return['mch_id'];						//商户系统用户号 入金时候建议按照订单号值配置
        $userId		= $return['mch_id']; 			//商户ID
        $number		= $price;						//数量
        $userOrder	= $orderid;						//订单号
        $payType	= $this->typearray[$return['paytype']];						//支付类型
        $isPur	= "1";					//状态
        $remark		= "1";						//备注
        $appID		=$return['appid']; //APPID
        $sn			= $return['signkey'];	//密钥
        $ckValue=md5($userId."|".$merchantUserID."|".$userOrder."|".$number."|".$payType."|".$isPur."|".$remark."|".$appID."|".$sn);//签名串


        $post_data=array(
            'merchantUserID' => $merchantUserID,
            'userId' => $userId,
            'number' => $number,
            'userOrder' => $userOrder,
            'payType' => $payType,
            'isPur' => $isPur,
            'remark' => $remark,
            'appID' => $appID,
            'sn' => $sn,
            'ckValue' => $ckValue,
        );
        $rdata = $this->curlPost($url, $post_data);
        $rdata_array = json_decode($rdata, true);
       // var_dump($rdata_array);
        if($rdata_array['resultCode']!='0000'){
             ob_start();
             ob_end_clean();
             echo '<!DOCTYPE html>
<html>
<head><meta charset="utf8">
    <title>PayIng</title>
</head>
<body background-color="#FFFFFF">
'.$rdata_array['resultCode'].":".iconv("UTF-8","gbk//TRANSLIT",$rdata_array['resultMsg']).'</body></html>';
    exit();

        }else{
            $pays_url = $rdata_array['data']['payPage'];
        }
        header("Location: $pays_url");
        exit;
}


    //同步通知
    public function callbackurl()
    {
    	

    
       	file_put_contents('Jiufupay.txt','error2' , FILE_APPEND );
        $Order      = M("Order");
        $pays_status = $Order->where(['pays_orderid' => $_REQUEST["userOrder"]])->getField("pays_status");
        if ($pays_status == 1) {
          //  $this->EditMoney($_REQUEST["userOrder"], '', 1); //第三个参数为1时，页面会跳转到订单信息里面的 pays_callbackurl
       // } else {
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

        file_put_contents('Jiufupay.txt', json_encode($response) . PHP_EOL, FILE_APPEND);
 		
		$rst = $this->rsaCheck($response, $response['key'] , $response['sign_type']);
		$m_Order    = M("Order");
		$order_info = $m_Order->where(['pays_orderid' =>$response['userOrder']])->find(); //获取订单信息

			
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
			
			
               file_put_contents('Jiufupay.txt','success' , FILE_APPEND );
               $this->EditMoney($response['userOrder'], '', 0);
               exit("success");
      }
      else
      {
      $m_Order    = M("Order");
      $time       = time(); //当前时间
      $res = $m_Order->where(['pays_orderid' => $response['userOrder'], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);
      file_put_contents('Jiufupay.txt','success' , FILE_APPEND );

      	 exit("success");
      }
		}else {
			
			file_put_contents('Jiufupay.txt','error2' , FILE_APPEND );
			exit;
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
       /* $pays_info = M('ChannelAccount')->where(array("mch_id" => $data['userId']))->find();
        $userId 		= $data['userId'];   		 //商户号
        $orderId 	= $data['orderId']; 		 //接收传递的订单号
        $userOrder		= $data['userOrder']; 		 	 //金额
        $number	=$data['number'];	     //状态码
        $merPriv =$data['merPriv'];
        $remark =$data['remark'];
        $date	=$data['date']; 	     //返回错误
        $resultCode		=$data['resultCode'];  			 //时间
        $resultMsg 	= $data['resultMsg'];  		 //获取签名串
        $sn			= $pays_info['signkey'];  						 //商户密匙（form表单中未提交在这重新输入值进行验证）
        $appID		= $data['appID'];
        $chkValue	= $data['chkValue'];
        $md55		= md5($userId."|".$orderId."|".$userOrder."|".$number."|".$date."|".$resultCode."|".$resultMsg."|".$appID."|".$sn);//利用商户密匙重新生成md5加密串与接受的签名串进行验证
        echo ($userId."|".$orderId."|".$userOrder."|".$number."|".$date."|".$resultCode."|".$resultMsg."|".$appID."|".$sn);//利用商户密匙重新生成md5加密串与接受的签名串进行验证
        var_dump($chkValue);
        echo "<br>";
        var_dump($md55);
      */

        $order_info = M('order')->where(array("pays_orderid" => $data['userOrder']))->find();
         if($data['resultCode'] == "0000" && $order_info){  			 //判断返回的提现状态
            if(round($order_info['pays_amount'],2)==$data['number']){
               return true;
            }else{
               return false;
            }
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
