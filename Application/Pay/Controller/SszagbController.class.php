<?php
namespace Pay\Controller;
 
/**
 * @author     mselect <445712421@qq.com>
 *2019-11-9 *
 */
class SszagbController extends PayController{
 

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

	   if($array['pid']==902)
	   {
	   	$type='wxpay';
	   }
	    if($array['pid']==903)
	   {
	   	$type='alipay';
	   }
	  
 		header("Content-type:text/html;charset=gbk");
 		$orderid     = I("request.pays_orderid");
        $body        = I('request.pays_productname');
        $notifyurl   = $this->_site . 'Pay_Sszagb_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Sszagb_callbackurl.html'; //返回通知

        $parameter   = array(
            'code'         => 'Sszagb', // 通道名称
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
        $url       = $return['gateway'].'/pay/unifiedorder';
        //商品名称

        //本平台订单号
        $unified = [
            "mch_id"        => $return['mch_id'],
            "out_trade_no"  => $return['orderid'],
            "subject"       => "商品",
            "body"          => "商品",
            "amount"        => sprintf('%.2f', $return['amount']),
            "channel"       => $type, //支付方式小写wx.scan
            "client_ip"     => $_SERVER['REMOTE_ADDR'],
            "return_url"    => $callbackurl,
            "notify_url"    => $notifyurl,
            "extparam"      => [
                "noncestr"  => $this->createNonceStr()
            ],
            "timestamp"     => time(),

        ];
      
        $unified['sign'] = $this->getSign($unified);
       
        $response =$this->curlPost($url, $unified);
        $response = json_decode($response,true);
     
        if($response["result_code"]=='OK')
        {
        $pays_url=$response["charge"]["pay_url"];
        header("Location: $pays_url");
        exit;
        }
         
		
	}


    //同步通知
    public function callbackurl()
    {
    	

    
       	file_put_contents('sszagb.txt','error2' , FILE_APPEND );
        $Order      = M("Order");
        $pays_status = $Order->where(['pays_orderid' => $_REQUEST["charge"]["out_trade_no"]])->getField("pays_status");
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
	
		
		$response= file_get_contents("php://input");
        $response = json_decode($response,true);

file_put_contents('notify.log','通知成功'.$response["charge"]["out_trade_no"]);

		if($this->verify($response))
		{
			
		$m_Order    = M("Order");
		$order_info = $m_Order->where(['pays_orderid' =>$response["charge"]["out_trade_no"]])->find(); //获取订单信息
		
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
			
			
               file_put_contents('Jiufupay.txt','success' , FILE_APPEND );
               $this->EditMoney($response["charge"]["out_trade_no"], '', 0);
               exit("success");
      }
      else
      {
      $m_Order    = M("Order");
      $time       = time(); //当前时间
      $res = $m_Order->where(['pays_orderid' => $response["charge"]["out_trade_no"], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);
      file_put_contents('Jiufupay.txt','success' , FILE_APPEND );

      	 exit("success");
      }
		
		}
       else
       {
       		file_put_contents('sszagb.txt','error2' , $response );
			exit;
       }

 		
	
	}
    
    public  function verify($return)
    {
        if(isset($return['result_code']) && $return['result_code'] == 'OK' && $return['result_msg'] == 'SUCCESS'){
            $charge = $return['charge'];
            $sign =$this->getSign($charge);
            if($sign === $charge['sign']){
                return $return;
            }else{
                return false;
            }
        }else{
            return $return;
        }
    }
    
    public function createNonceStr($length = 16)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }
	
     public function getSign($params)
    {
        ksort($params, SORT_STRING);
        $unSignParaString =$this->formatQueryParaMap($params);
        $key='u9sjMHDewG6gJba4fv0mftoTphIDi3lw';
    
        $signStr = strtoupper(md5($unSignParaString . "&key=" .$key));
        return $signStr;
    }
	protected function formatQueryParaMap($paraMap)
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
