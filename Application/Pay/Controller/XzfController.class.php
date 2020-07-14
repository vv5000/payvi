<?php
namespace Pay\Controller;
 
/**
 * @author     mselect <445712421@qq.com>
 *2019-11-9 *
 */
class XzfController extends PayController{
 

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
$isApp='web';
	   if($array['pid']==902)
	   {
	   	$type='WXPAY';
	   }
	    if($array['pid']==903)
	   {
	   	$type='ALIPAY';
	   }
	    if($array['pid']==944)
	   {
	   	$type='ICBC';
	   }
	     if($array['pid']==950)
	   {
	   	$type='ALIPAY';
	   	$isApp='h5';
	   }
	   
	  
 		header("Content-type:text/html;charset=gbk");
 		$orderid     = I("request.pays_orderid");
        $body        = I('request.pays_productname');
        $notifyurl   = $this->_site . 'Pay_Xzf_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Xzf_callbackurl.html'; //返回通知

  
	   
        $parameter   = array(
            'code'         => 'Xzf', // 通道名称
            'title'        => '',
            'exchange'     => 1, // 金额比例
            'gateway'      => '',
            'orderid'      => '',
            'out_trade_id' => $orderid,
            'body'         => $body,
            'channel'      => $array,
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
      $return    = $this->norderadd($parameter);
        
       
        $key=$return['signkey'];
    
         //第四方接口地址 （网关）
        $url       = $return['gateway'].'/payment/v1/order/'.$return['orderid'].'-'.$return['orderid'];
        //商品名称

        //本平台订单号
        $unified = [
        	"orderNo"  => $return['orderid'],
            "totalFee"     =>sprintf('%.2f', $return['amount']),
            "defaultbank"  =>$type,
            "title"  => "商品",
            "paymethod"  =>"directPay",
            "service"  => "online_pay",
            "paymentType"  => "1",
            
            "merchantId"  =>$return['mch_id'],
            "returnUrl"  =>$callbackurl,
            "notifyUrl"  =>$notifyurl,
            "charset"  => "utf-8",
        	 "body"  =>"商品",
            "isApp"  =>$isApp,
        ];
      
         
        $unified['sign'] = $this->Sign($unified,$key);
        $unified['signType'] = "SHA";
      
        $HtmlStr =  $this->postHtml($url,$unified);
        var_dump($HtmlStr);
         
		
	}


    //同步通知
    public function callbackurl()
    {
    	

    
       	file_put_contents('sszagb.txt','error2' , FILE_APPEND );
        $Order      = M("Order");
        $pays_status = $Order->where(['pays_orderid' => $_POST["order_no"]])->getField("pays_status");
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
		$content = file_get_contents("php://input");
	

        parse_str($content,$data);
		
		foreach ($data as $key => $value){
    $params[$key] = $value;
}
unset($params['sign']);
unset($params['signType']);
$apikey="oauv5kbpy338idnpwwb3afouppo32fudvdmbcl7mqrfie1k6rrs0d4nm1kxo6xzw";
$str =$this->Sign($params,$apikey);
$sign = $_POST['sign'];
if ($sign == $str){
	file_put_contents('xzf5.log','单'.$_POST["order_no"]);
	
	$m_Order    = M("Order");
	$order_info = $m_Order->where(['pays_orderid' =>$_POST["order_no"]])->find();
//获取订单信息
		
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
           
           $Websiteconfig = D("Websiteconfig");
       
        $list = $Websiteconfig->find();
      if($list['hdhd']==1)
      {
			
			
               $this->EditMoney($_POST["order_no"], '', 0);
               exit("success");
      }
      else
      {
      $m_Order    = M("Order");
      $time       = time(); //当前时间
      $res = $m_Order->where(['pays_orderid' =>$_POST["order_no"], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);

      	 exit("success");
      }
		
} else {
	file_put_contents('xzf6.log','单'.$_POST["order_no"]);
    exit("fail");
}
		
	
	
	
	}
	
	
	
	public  function Sign($params, $apiKey)
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
	
    public function postHtml($Url, $PostArry){
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
 
 
   
 
}
 
?>
