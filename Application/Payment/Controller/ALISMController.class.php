<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-05-18
 * Time: 11:33
 */
namespace Pay\Controller;

class ALISMController extends PayController
{
    public function __construct()
    {
        parent::__construct();
    }

    //支付
    public function Pay($array)
    {
        $orderid     = I("request.pays_orderid");
        $body        = I('request.pays_productname');
        $notifyurl   = $this->_site . 'Pay_ALISM_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_ALISM_callbackurl.html'; //返回通知

        $parameter = array(
            'code'         => 'ALISM', // 通道名称
            'title'        => '支付宝扫码',
            'exchange'     => 1, // 金额比例
            'gateway'      => '',
            'orderid'      => '',
            'out_trade_id' => $orderid,
            'body'         => $body,
            'channel'      => $array,
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);
       
		$aliapy_config['partner']      = trim($return['mch_id']);
		$aliapy_config['key']          = trim($return['signkey']);
		$aliapy_config['seller_email'] = trim($return['appid']);
		$aliapy_config['return_url']   = trim($return['callbackurl']);
		$aliapy_config['notify_url']   = trim($return['notifyurl']);
		$aliapy_config['sign_type']    = 'MD5';
		$aliapy_config['input_charset']= 'utf-8';
		$aliapy_config['transport']    = 'https';

		require_once dirname( __FILE__).'/alipay/lib/alipays_service.class.php';
		$out_trade_no = $return['orderid'];
		$subject      = $return['orderid'];
		$body         = $return['orderid'];
		$total_fee    = sprintf("%.2f", $return['amount'] );
		$paymethod    = '';
		$defaultbank  = '';
		$anti_phishing_key  = '';
		$exter_invoke_ip = $_SERVER['REMOTE_ADDR'];
		$show_url			= $return['callbackurl'];
		$extra_common_param = 'pay';
		$royalty_type		= "";			//提成类型，该值为固定值：10，不需要修改
		$royalty_parameters	= "";
		$parameter = array(
				"service"			=> "create_direct_pays_by_user",
				"payment_type"		=> "1",
				"partner"			=> trim($return['mch_id']),
				"_input_charset"	=> trim(strtolower('utf-8')),
				"seller_email"		=> trim($return['appid']),
				"return_url"		=> trim($return['callbackurl']),
				"notify_url"		=> trim($return['notifyurl']),
				"out_trade_no"		=> $out_trade_no,
				"subject"			=> $subject,
				"body"				=> $body,
				"total_fee"			=> $total_fee,
				"paymethod"			=> $paymethod,
				"defaultbank"		=> $defaultbank,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"show_url"			=> $show_url,
				"extra_common_param"=> $extra_common_param,
				"royalty_type"		=> $royalty_type,
				"royalty_parameters"=> $royalty_parameters
		);
		//构造即时到帐接口
		$alipayService = new \AlipayService($aliapy_config);
		$html_text = $alipayService->create_direct_pays_by_user($parameter);
		echo $html_text;


    
    }


    //同步通知
    public function callbackurl()
    {
        $Order      = M("Order");
       
        $pays_status = $Order->where(['pays_orderid' => $_REQUEST["out_trade_no"]])->getField("pays_status");
        if ($pays_status == 1) {
            $this->EditMoney($_REQUEST["out_trade_no"], '', 1);
        } else {
            exit("error");
        }
    }

    //异步通知
    public function notifyurl()
    {
        
		
      	error_reporting( 0 );
         file_put_contents( dirname( __FILE__ ).'/aaa_post.txt', var_export($_POST, true), FILE_APPEND );
	
		$key = getKey(  $_POST['out_trade_no'] );
		$aliapy_config['key']          = trim($key);
		$aliapy_config['sign_type']    = 'MD5';
		$aliapy_config['input_charset']= 'utf-8';
		$aliapy_config['transport']    = 'https';
		require_once dirname( __FILE__).'/alipay/lib/alipays_notify.class.php';
		

		//计算得出通知验证结果
		$alipayNotify = new \AlipayNotify($aliapy_config);
		$verify_result = $alipayNotify->verifyNotify();
		
		if($verify_result)
		{
			    $out_trade_no	= $_POST['out_trade_no'];	    //获取订单号
				$trade_no		= $_POST['trade_no'];	    	//获取支付宝交易号
				$total_fee		= $_POST['total_fee'];			//获取总价格

				if($_POST['trade_status'] == 'TRADE_FINISHED' ||$_POST['trade_status'] == 'TRADE_SUCCESS') 
				{
					$this->EditMoney($_POST['out_trade_no'], '', 0);
					exit("success");
				}
		}
		exit("success");

    }

}
