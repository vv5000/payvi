<?php
defined('PP_PATH') or exit;
class pay_control extends control{
    public $_cfg = array();	// 全站参数

    var $paydetail = NULL;
    public function __construct(){
        $this->_cfg = $this->runtime->xget('cfg');
        $_ENV['_hometheme']	= $this->_cfg['theme'];
        $this->id 	= (int)R('id', 'G');
        //支付宝
        $this->return_url 		= $this->_cfg['weburl']."index.php?u=pay-returns";
        $this->notify_url 		= $this->_cfg['weburl']."notify.php"; //必须不带参数，所以必须伪静态下使用
        //微信
        $this->weixinnotify_url 	= $this->_cfg['weburl']."index.php?u=pay-weixinnotify";
        //商派天工 ecshop
        $this->passpay_returnurl 	= $this->_cfg['weburl']."index.php?u=pay-passpay_return&";
        $this->passpay_notifyurl 	= $this->_cfg['weburl']."index.php?u=pay-passpay_notify&";
        //铭翼支付
        $this->mingyi_returnurl 	= $this->_cfg['weburl']."index.php?u=pay-mingyi_return&";	//！！！
        $this->mingyi_notifyurl 	= $this->_cfg['weburl']."index.php?u=pay-mingyi_notify";
        $this->member_url 	= $this->_cfg['weburl']."member/";

        //重组
        $alipay_config = array(
            'partner'			=> $this->_cfg['alipay']['partner'],
            'key'				=> $this->_cfg['alipay']['key'],
            'sign_type'    		=> strtoupper('MD5'),
            'input_charset'		=> strtolower('utf-8'),
            'cacert'    		=> getcwd().'\cacert.pem',
            'transport'    		=> 'http',
        );
        $this->alipay_config 	= $alipay_config;
    }
    public function lifealipay(){
        $this->assign_value('navkey', 'record-order');
        $this->getPaydetail($this->id);
        $submitdata['optEmail'] 	= $this->_cfg['lifealipay']['id'];
        $submitdata['payAmount'] 	= $this->paydetail['money'];
        $submitdata['title'] 		= $this->paydetail['order_id'];
        $submitdata['id'] 			= $this->paydetail['order_id'];
        $submitdata['memo'] 		= '';
        if(!empty($this->_cfg['lifealipay']['isSend']) && $submitdata['payAmount']>=1){
            $submitdata['isSend'] 		= $this->_cfg['lifealipay']['isSend'];
            $submitdata['smsNo'] 		= $this->_cfg['lifealipay']['smsNo'];//17091619036
        }
        $this->assign('pp', $this->_cfg);
        $this->assign('data', $submitdata);
        $this->display('alipay.htm');
    }
    public function alipay(){
        if(empty($this->_cfg['alipay']['off'])) exit("对不起，该支付方式被关闭，暂时不能使用!");
        $this->getPaydetail($this->id);
        $submitdata = array(
            "service" => "create_direct_pay_by_user",
            "partner" => $this->_cfg['alipay']['partner'],
            "seller_email" => $this->_cfg['alipay']['id'],
            "payment_type"	=> '1',
            "notify_url"	=> $this->notify_url,
            "return_url"	=> $this->return_url,
            //	"return_url"=>"https://www.taoy168.com/Pay_Ky_callbackurl.html?orderid=". $this->paydetail['order_id'],
            "out_trade_no"	=> $this->paydetail['order_id'],
            "subject"		=> $this->_cfg['webname'].'('.$this->_cfg['webdomain'].')'.'充值',
            "total_fee"		=> $this->paydetail['money'],
            "_input_charset"	=> trim(strtolower('utf-8')),
        );

        $alipaySubmit = new AlipaySubmit($this->alipay_config);
        $html_text = $alipaySubmit->buildRequestForm($submitdata,"get", "确认");
        echo $html_text;
    }
    public function notify(){

        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        file_put_contents('1.txt', json_encode($_POST) . PHP_EOL, FILE_APPEND);
        file_put_contents('1.txt', json_encode($verify_result) . PHP_EOL, FILE_APPEND);

        if($verify_result||true) {//验证成功
            $out_trade_no 	= R('out_trade_no', 'P');
            $trade_no 		= R('trade_no', 'P');
            $trade_status 	= R('trade_status', 'P');
            file_put_contents('1.txt', json_encode($out_trade_no) . PHP_EOL, FILE_APPEND);
            file_put_contents('1.txt', json_encode($trade_no) . PHP_EOL, FILE_APPEND);
            file_put_contents('1.txt', json_encode($trade_status) . PHP_EOL, FILE_APPEND);
            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'){
                $record = &$this->record;
                $data 	= $record->getByOid($out_trade_no);
                file_put_contents('1.txt', json_encode($data) . PHP_EOL, FILE_APPEND);
                $this->payDone($data);
                //模拟推送
                $this->cnotify($data);

                //file_get_contents("https://www.taoy168.com/Pay_Ky_notifyurl.html?orderid=".$out_trade_no);
                //$this->message(1, '成功 ，充值:'.$data['money'].'元',$this->member_url);
            }
            echo "success";		//请不要修改或删除
        }else{
            //验证失败
            echo "fail";
        }
    }
    public function returns(){
        $alipayNotify = new AlipayNotify($this->alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result){
            $out_trade_no	= R('out_trade_no');
            $trade_no 		= R('trade_no');
            $trade_status  	= R('trade_status');

            if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS'){
                $record = &$this->record;
                $data 	= $record->getByOid($out_trade_no);
                //	$this->payDone($data);

                //返回URL不为空，直接跳转
                if(!empty($data['return_url'])){
                    $this->creturn($data);
                }

                $this->message(1, '成功 ，充值:'.$data['money'].'元',$this->member_url);

            }else{
                echo "trade_status=".$trade_status;
            }
            echo "验证成功<br />";
        }else{
            echo "验证失败";
        }
    }

    //微信扫码支付
    public function weixin(){
        if(empty($this->_cfg['weixin']['off'])) exit("对不起，该支付方式被关闭，暂时不能使用!");
        $this->getPaydetail($this->id);

        new weixinpay();//使用统一支付接口，获取prepay_id
        $unifiedOrder = new UnifiedOrder_pub();

        $body = "订单编号：".$this->paydetail['order_id'];
        $unifiedOrder->setParameter("body", $body);									//商品描述
        $unifiedOrder->setParameter("out_trade_no", $this->paydetail['order_id']);	//商户订单号
        $unifiedOrder->setParameter("total_fee", $this->paydetail['money']*100);		//总金额 ……
        $unifiedOrder->setParameter("notify_url", $this->weixinnotify_url);			//通知地址
        $unifiedOrder->setParameter("trade_type", "NATIVE");						//交易类型

        //获取统一支付接口结果
        $result = $unifiedOrder->getResult();
        //print_r($result);

        if(isset($result["code_url"]) && !empty($result["code_url"])) { //二维码图片链接
            $code_url = $result["code_url"];
        }else{
            //log::write_mini("【下单】".json_encode($result),'weixin_err.log');
        }
        $this->assign('out_trade_no',$this->paydetail['order_id']);
        $this->assign('code_url',$code_url);
        $this->assign('result',$result);
        $this->assign('price',$this->paydetail['money']);
        $this->assign('pp',$this->_cfg);
        $this->display('qrcode.htm');
    }
    //微信订单 手动查询
    public function weixin_check(){



        $out_trade_no = R('orderno','P');
        new weixinpay();
        $Order = new OrderQuery_pub();
        $Order->setParameter("out_trade_no",$out_trade_no);	//商户订单号
        $result = $Order->getResult();
        //echo json_encode($result);
        //商户根据实际情况设置相应的处理流程,此处仅作举例
        if ($result["return_code"] == "FAIL") {
            ME(1, "【查询：通信出错】".$result['return_msg']);
        }elseif($result["result_code"] == "FAIL"){
            ME(1, "【查询：业务出错】".$result['err_code']."：".$result['err_code_des']);
        }else{
            switch($result["trade_state"]){
                case 'SUCCESS':
                    //业务处理
                    $record = &$this->record;
                    $data 	= $record->getByOid($out_trade_no);
                    $this->payDone($data);

                    //file_get_contents("https://www.taoy168.com/Pay_Ky_notifyurl.html?orderid=".$out_trade_no);
                    //模拟推送
                    $this->cnotify($data);

                    ME(0, "【查询：支付成功】".$result['trade_state']);
                    break;
                case 'REFUND':
                    ME(1, "【查询：转退款】".$result['trade_state']);
                    break;
                case 'NOTPAY':
                    ME(1, "【查询：未支付】".$result['trade_state']);
                    break;
                case 'CLOSED':
                    ME(1, "【查询：超时关闭订单】".$result['trade_state']);
                    break;
                case 'PAYERROR':
                    ME(1, "【查询：支付失败】".$result['trade_state']);
                    break;
                default:
                    ME(1, "【查询：未知失败】".$result['trade_state']);
                    break;
            }
        }
    }
    public function weixinnotify(){
        $notify = new Notify_pub();
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if($notify->checkSign() == FALSE){
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }else{
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        $returnXml = $notify->returnXml();







        echo $returnXml;
        //==商户根据实际情况设置相应的处理流程，此处仅作举例=======
        //以log文件形式记录回调信息
        if($notify->checkSign() == TRUE){
            if($notify->data["return_code"] == "FAIL") {
                //log::write_mini("【回调：通信出错】".$xml,'weixin_err.log');
            }elseif($notify->data["result_code"] == "FAIL"){
                //log::write_mini("【回调：业务出错】".$xml,'weixin_err.log');
            }else{
                //log::write_mini("【回调：支付成功】".$xml,'weixin_ok.log');
                $record = &$this->record;
                $data 	= $record->getByOid($notify->data['out_trade_no']);

                //	file_get_contents("https://www.taoy168.com/Pay_Ky_notifyurl.html?orderid=".$notify->data['out_trade_no']);

                $this->payDone($data);
                //模拟推送
                $this->cnotify($data);
                $this->message(1, '成功 ，充值:'.$amount.'元',$this->member_url);
            }
            //商户自行增加处理流程,
        }
    }

    //云通付
    function passpay(){
        if(empty($this->_cfg['passpay']['off'])) exit("对不起，该支付方式被关闭，暂时不能使用!");
        $this->getPaydetail($this->id);
        $parameter = array(
            "partner" 		=> $this->_cfg['passpay']['partner'],
            "user_seller"  	=> $this->_cfg['passpay']['user_seller'],
            "out_order_no"	=> $this->paydetail['order_id'],
            "subject"		=> "充值,订单：".$this->paydetail['order_id'],
            "total_fee"		=> $this->paydetail['money'],
            //"body"			=> '',
            "notify_url"	=> $this->passpay_notifyurl,
            "return_url"	=> $this->passpay_returnurl
        );

        //建立请求
        $passpay = new passpay();
        $html_text = $passpay->buildRequestFormShan($parameter, $this->_cfg['passpay']['key']);
        echo $html_text;
    }
    function passpay_return(){
        $passpay = new passpay();

        $order_sn = R('out_order_no');
        $total_fee = R('total_fee');
        $trade_status = R('trade_status');
        $sign = R('sign');

        //log::write_mini(json_encode($_REQUEST),'passpay_return.log');

        $shanNotify = $passpay->md5VerifyShan($order_sn,$total_fee,$trade_status,$sign,$this->_cfg['passpay']['key'],$this->_cfg['passpay']['partner']);
        if($shanNotify) {//验证成功
            if($trade_status=='TRADE_SUCCESS'){
                $record = &$this->record;
                $data 	= $record->getByOid($order_sn);
                if($data['money'] != $total_fee){
                    exit('付款金额与订单金额不符！');
                }
                //$this->payDone($data);
                //返回URL不为空，直接跳转
                if(!empty($data['return_url'])){
                    $this->creturn($data);
                }
                $this->message(1, '成功 ，充值:'.$total_fee.'元',$this->member_url);
            }else{
                $this->message(0, '支付失败！',$this->member_url);
            }
        }else {
            $this->message(0, '验证失败！',$this->member_url);
        }
    }
    function passpay_notify(){
        $passpay = new passpay();

        $order_sn = R('out_order_no');
        $total_fee = R('total_fee');
        $trade_status = R('trade_status');
        $sign = R('sign');

        //log::write_mini(json_encode($_REQUEST),'passpay_notify.log');

        $shanNotify = $passpay->md5VerifyShan($order_sn,$total_fee,$trade_status,$sign,$this->_cfg['passpay']['key'],$this->_cfg['passpay']['partner']);
        if($shanNotify) {//验证成功
            if($trade_status=='TRADE_SUCCESS'){
                $record = &$this->record;
                $data 	= $record->getByOid($order_sn);
                $this->payDone($data,1);
                //模拟推送
                $this->cnotify($data);
            }
            echo 'success';
        }else {
            echo "fail";//请不要修改或删除
        }
    }
    public function mingyi(){
        if(empty($this->_cfg['mingyi']['off'])) exit("对不起，该支付方式被关闭，暂时不能使用!");
        $this->getPaydetail($this->id);

        $signa	=  md5(md5(($this->paydetail['money']*100).$this->mingyi_notifyurl.$this->paydetail['order_id']).$this->_cfg['mingyi']['key']);

        $param = array (
            'notify_url' 	=> $this->mingyi_notifyurl,
            'return_url' 	=> $this->mingyi_returnurl,
            'order_sn' 		=> $this->paydetail['order_id'],//商户订单号
            'money' 		=> $this->paydetail['money'] * 100,//商户订单金额
            'user_id' 		=> $this->_cfg['mingyi']['id'],//商户会员ID
            'sign' 			=> $signa,//签名
        );
        //发送请求地址
        $url = 'https://www.mingyie.com/api/pal/post';

        $sHtml = '';
        $sHtml .= "<h3>正在跳转到铭翼支付....</h3>";
        $sHtml .= "<form id='cbjpaysubmit' name='cbjpaysubmit' action='".$url."' method='POST'>";
        while(list ($key, $val) = each($param)){
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        $sHtml = $sHtml."<script>document.forms['cbjpaysubmit'].submit();</script>";
        echo $sHtml;
    }
    //http://127.0.0.1/newzhan_new/index.php?u=pay-mingyi_return&?order_sn=12345
    function mingyi_return(){	////返回时自动增加?order_sn=170220650806565901，造成前台无法访问
        $order_sn 	= R('?order_sn');

        $data 	= $this->record->getByOid($order_sn);
        if($data['status']>0){
            $this->message(0, '支付未成功，请联系客服处理！',$this->member_url);
        }else{
            //返回URL不为空，直接跳转
            if(!empty($data['return_url'])){
                $this->creturn($data);
            }
            $this->message(1, '支付成功！',$this->member_url);
        }
        //$this->payDone($data);
    }
    function mingyi_notify(){
        if(empty($_POST)){
            exit;
        }
        $signa = md5(md5($_POST['order_sn'].$_POST['money'].$_POST['trade_no'].$_POST['user_id']).$this->_cfg['mingyi']['key']);
        if($signa != $_POST['sign']){
            log::write_mini("二次验证签名失败！".json_encode($_POST),'mingyi_notify.log');
            exit();
        }
        if ($_POST['trade_status'] == 10000){	//成功
            $record = &$this->record;
            $data 	= $record->getByOid($_POST['order_sn']);
            if($data['money'] != $_POST['money']){
                log::write_mini("付款金额与订单金额不符！".json_encode($_POST),'mingyi_notify.log');
                exit();
            }
            $this->payDone($data);
            //模拟推送
            $this->cnotify($data);
        }
    }
    private function payDone($data,$type=0){
        //写入交易　更新用户余额　发送充值短信
        $update['id'] 		= $data['id'];
        if($data['status']>0){ //未支付成功,已支付成功的不作处理
            $update['status'] 	= 0;
            $this->record->update($update);

            $user =  $this->user->get($data['uid']);
            $user['money']	= $user['money'] + $data['money'] ;
            $this->user->update($user);

            if($user['mobile_staus']){
                $sms_cfg = $this->kv->xget('sms_cfg');
                if(!empty($sms_cfg['payok_off'])){
                    try{
                        other::send_sms($sms_cfg,$user['mobile'],str_replace(array("{username}","{money}"),array($user['username'],$data['money']),$sms_cfg['payok']));
                    }catch(Exception $e){}
                }
            }
        }else{
            if($type) return false;
            $this->message(0, '此订单已经处理过了！',$this->member_url);
        }
    }
    private function getPaydetail($id){
        $data = $this->record->get($id);
        $this->paydetail 	= $data;
    }
    private function create($data,$submitUrl){
        $inputstr = "";
        foreach($data as $key=>$v){
            $inputstr .= '
			<input type="hidden"  id="'.$key.'" name="'.$key.'" value="'.$v.'"/>
		';
        }

        $form = '
			<form action="'.$submitUrl.'" name="pay" id="pay" method="POST">
		';
        $form.=	$inputstr;
        $form.=	'
			</form>
		';

        $html = '
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
			<html xmlns="http://www.w3.org/1999/xhtml">
			<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>请不要关闭页面,支付跳转中.....</title>
			</head>
			<body>
        ';
        $html.=	$form;
        $html.=	'
			<script type="text/javascript">
				document.getElementById("pay").submit();
			</script>
        ';
        $html.= '
			</body>
			</html>
		';
        echo $html;
        exit;
    }
    private function md5Verify($i1, $i2, $i3, $key, $pid) {
        $prestr = $i1 . $i2 . $pid . $key;
        $mysgin = md5($prestr);
        if ($mysgin == $i3) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * 模拟发送
     */
    public function phpcurl($url,$data)
    {
        //发送 POST 请求
        $ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
        $ch = curl_init();
        $opt = array(
            CURLOPT_URL     => $url,
            CURLOPT_POST    => 1,
            CURLOPT_HEADER  => 0,
            CURLOPT_POSTFIELDS      => (array)$data,
            CURLOPT_RETURNTRANSFER  => 1,
            CURLOPT_TIMEOUT         => 30,
        );
        if ($ssl)
        {
            $opt[CURLOPT_SSL_VERIFYHOST] = 1;
            $opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
        }
        curl_setopt_array($ch, $opt);
        $datas = curl_exec($ch);
        curl_close($ch);
        $datas_arr = json_decode($datas, true);
        return $datas_arr;
    }

    /**
     * 签名算法
     */
    public function sign ($apikey, $data) {
        ksort($data);
        $_unsigned_str = '';
        foreach ($data as $key => $value) {
            if ($value!='') {
                $_unsigned_str .= $key."=".$value."&";
            }
        }
        $_unsigned_str .= "apikey=".$apikey;
        $_sign = md5($_unsigned_str);
        return $_sign;
    }


    //通知到四方平台
    public function cnotify($data)
    {
        $postdata=array(
            'orderid' => $data['order_id'],
            'status' => 1,
        );
        $key = '20148b4bba686fd7f37dceeb8a20bf36';
        $sign=$this->sign($key, $postdata);
        $postdata['sign'] = $sign;
        $this->phpcurl($data['notify_url'], $postdata);
    }

    public function creturn($data)
    {
        $url = $data['return_url'] . "?orderid=" . $data['order_id'];
        header('location:'.$url);
        exit();
    }

}