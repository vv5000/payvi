<?php
namespace Pay\Controller;
 
/**
 * @author     mselect <445712421@qq.com>
 *2019-11-9 *
 */
class RtzfController extends PayController{
 

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
	   	$type='wx';
	   }
	    if($array['pid']==903)
	   {
	   	$type='ali';
	   }
	    if($array['pid']==944)
	   {
	   	$type='yl';
	   }
	     if($array['pid']==951)
	   {
	   	$type='jd';
	   	$isApp='h5';
	   }
	   
	    
 		header("Content-type:text/html;charset=gbk");
 		$orderid     = I("request.pays_orderid");
        $body        = I('request.pays_productname');
        $notifyurl   = $this->_site . 'Pay_Rtzf_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Rtzf_callbackurl.html'; //返回通知

  
	   
        $parameter   = array(
            'code'         => 'Rtzf', // 通道名称
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
        $url       = $return['gateway'];
        //商品名称
         $ytime=date("Ymdhis");
        //本平台订单号
        $unified = [
        	"amount"=> sprintf('%.2f', $return['amount']),
        	"currency"=> "CNY",
        	"memo"=> "九付宝支付",
            "merchNo"=>$return['mch_id'],
            "notifyUrl"  =>$notifyurl,
            "orderNo"  => $return['orderid'],
            "outChannel"=>$type,
            "product"=>"商品",
            "reqTime"=>$ytime,
            "returnUrl"  =>$callbackurl,
            "title"  => "商品",
            "userId"  => "123456",
        ];
      header('Content-Type:text/html;charset=utf-8');
        $unified=json_encode($unified,320);
      
     
     
       $context=base64_encode($unified);
      
        $order= [
        	"amount"=> sprintf('%.2f', $return['amount']),
        	"currency"=> "CNY",
        	"memo"=> "九付宝支付",
            "merchNo"=>$return['mch_id'],
            "orderNo"  => $return['orderid'],
            "outChannel"=>$type,
            "product"=>"商品",
            "reqTime"=>$ytime,
            "title"  => "商品",
            "userId"  => "123456",
        ];
        $order=json_encode($order,320);
    
        $sign=md5($unified.$key);
     
        
       
           
        $fpost['sign'] =$sign;
        $fpost['context'] =$context;
        
        $fpost=json_encode($fpost);

      
        $response =  $this->curlPost($url,$fpost);
        $response = json_decode($response,true);
        $context = json_decode($response['context'],true);
        $pays_url=$context['code_url'];
 header("Location: $pays_url");
        exit;
	
		
	}


    //同步通知
    public function callbackurl()
    {
     //exit("交易成功！");
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
		
		 $response = json_decode($content,true);
         $context = json_decode($response['context'],true);
		


if ($context['orderState'] == 1 && $context['merchNo']=='RTSH60109915' && $response['msg']=='success'){
	$m_Order    = M("Order");
	$order_info = $m_Order->where(['pays_orderid' =>$context["orderNo"]])->find();
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
			
			
               $this->EditMoney($context["orderNo"], '', 0);
               exit("ok");
      }
      else
      {
      $m_Order    = M("Order");
      $time       = time(); //当前时间
      $res = $m_Order->where(['pays_orderid' =>$context["orderNo"], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);

      	 exit("ok");
      }
		
} else {
    exit("fail");
}
		
	
	
	
	}
	
	 public function curlPost($url = '', $json_data = '', $options = array())
    {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_POST, 1);
       curl_setopt($ch, CURLOPT_URL, $url);
       curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

       curl_setopt($ch, CURLINFO_HEADER_OUT, true);
       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json;charset=utf-8','Content-Length: '.strlen($json_data)));
       $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }
 
 
   
 
}
 
?>
