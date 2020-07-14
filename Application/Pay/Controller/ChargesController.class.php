<?php
namespace Pay\Controller;

use Think\Controller;
use Pay\Model\IndexModel;
/**
 * Created by PhpStorm.
 * Date: 2018-10-30
 * Time: 12:00
 */
class ChargesController extends PayController{
    public function index(){
        $mchid= I("mid",0,"intval");

        if(!$mchid){
            redirect(U('Pay/Charges/msgshow'));
        }
        $where['id'] = intval($mchid) - 10000;
        $member=M("member")->where($where)->find();
        if(!$member){
            redirect(U('Pay/Charges/msgshow'));
        }
        //支付产品
        $products = M('Product')->where(['isdisplay'=>1, 'status'=>1])->select();
  
        $finalProducts = [];
        if(isMobile() && !is_weixin()){
            foreach ($products as $key => $product) {
                if(strstr($product['code'], 'SCAN')){
                    continue;
                }

                if(strstr($product['code'], 'WXJSAPI')){
                    continue;
                }
                $product['icon'] = $this->getIcon($product['code']);
                $finalProducts[] = $product; 
            }
        }else if(isMobile() && is_weixin()){
            foreach ($products as $key => $product) {
                if(strstr($product['code'], 'WXJSAPI')){
                    $product['icon'] = $this->getIcon($product['code']);
                    $finalProducts[] = $product; 
                    break;
                }
            }
        }else{
            foreach ($products as $key => $product) {
                if(strstr($product['code'], 'WXJSAPI')){
                    continue;
                }
                $product['icon'] = $this->getIcon($product['code']);
                $finalProducts[] = $product; 
            }
        }
        
        $pays_orderid = date("YmdHis").rand(100000,999999);    //订单号
        
        $this->assign('pays_orderid', $pays_orderid);
        $this->assign('products', $finalProducts);
        $this->assign("cache",$member);
        $this->assign("mchid",$mchid);
        $this->assign("posturl",$this->_site."Pay_Charges_checkout.html");
        if(isMobile()) {
            $this->display();
        } else {
            $this->display('index_pc');
        }
    }
   //测试
	public function  test33(){
		
		$param = array(
		'amount'=>'0.01',
		'mchid'=>'180772223',
		'bankcode'=>'914',
		
		);

		$html = "<form action='https://pay.365cpb.com/pays_charges_checkout' method='post' id='fm'>";

		foreach($param as $k => $v){
			$html .= "<input type='hidden' name='$k' value='$v'/>";
		}

		$html .= "</form><script>document.getElementById('fm').submit();</script>";
		echo $html;
	}
    protected function getIcon($product_code)
    {
        $weixin = ['WXSCAN', 'WXJSAPI'];
        $alipay = ['ALIWAP', 'ALISCAN'];
        $qq     = ['QQWAP'];
        if(in_array($product_code, $weixin)){
            $icon = 'wechat-icon';
        }
        if(in_array($product_code, $alipay)){
            $icon = 'alipay-icon';
        }
        if(in_array($product_code, $qq)){
            $icon = 'qq-icon';
        }
        return $icon;
    }
    public function checkout(){
        if(IS_POST){

            $pays_amount =  I("amount");    //交易金额
            if($pays_amount<=0){
                exit("交易金额不正确");
            }
            $mchid= I("mchid",0,"intval");

            if(!$mchid){
                exit("缺少商户号");
            }
            $where['id'] = intval($mchid) - 10000;
            $member=M("member")->where($where)->find();
            if(!$member){
                exit("商户不存在");
            }
            if($member['groupid'] == 4 && !$member['open_charge']) {
                exit("商户未开通充值功能");
            }
            $pays_memberid = ($member["id"]+10000);   //商户ID
            $pays_orderid = 'C'.date("YmdHis").rand(100000,999999);    //订单号


            $pays_bankcode =I("bankcode");   //银行编码
            if(empty($pays_memberid)||empty($pays_amount)||empty($pays_bankcode)){
                die("信息不完整！");
            }

            $pays_applydate = date("Y-m-d H:i:s");  //订单时间
            $pays_notifyurl = $this->_site."Pay_Charges_notify.html";   //服务端返回地址
            $pays_callbackurl = $this->_site."Pay_Charges_callback.html";  //页面跳转返回地址
            $Md5key = $member["apikey"];   //密钥
           // $tjurl = $this->_site."Pay_Index.html";   //提交地址
            $tjurl = "/Pay_Index.html";   //提交地址



            $native = array(
                "pays_memberid" => $pays_memberid,
                "pays_orderid" => $pays_orderid,
                "pays_amount" => $pays_amount,
                "pays_applydate" => $pays_applydate,
                "pays_bankcode" => $pays_bankcode,
                "pays_notifyurl" => $pays_notifyurl,
                "pays_callbackurl" => $pays_callbackurl,
            );
            ksort($native);
            $md5str = "";
            foreach ($native as $key => $val) {
                $md5str = $md5str . $key . "=" . $val . "&";
            }

            $sign = strtoupper(md5($md5str . "key=" . $Md5key));
            $native["pays_md5sign"] = $sign;
            $native['pays_attach'] = $_POST["remarks"];
            $native['pays_productname'] ='收款';
            $native['ddlx'] = 1;

            $this->setHtml($tjurl,$native);


        }
    }

    public function notify()
    {
        $ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "transaction_id" =>  $_REQUEST["transaction_id"], // 支付流水号
            "returncode" => $_REQUEST["returncode"],
        );
        if(!$ReturnArray["memberid"]){
            die;
        }

        $where['id'] = intval($ReturnArray["memberid"]) - 10000;
        $member=M("member")->where($where)->find();
        if(!$member){
            die;
        }
        $Md5key =$member["apikey"];

        ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));
        if ($sign == $_REQUEST["sign"]) {

            if ($_REQUEST["returncode"] == "00") {
                exit("OK");
            }
        }
    }

    public function callback()
    {
        $ReturnArray = array( // 返回字段
            "memberid" => $_REQUEST["memberid"], // 商户ID
            "orderid" =>  $_REQUEST["orderid"], // 订单号
            "amount" =>  $_REQUEST["amount"], // 交易金额
            "datetime" =>  $_REQUEST["datetime"], // 交易时间
            "transaction_id" =>  $_REQUEST["transaction_id"], // 流水号
            "returncode" => $_REQUEST["returncode"]
        );

        if(!$ReturnArray["memberid"]){
            die;
        }

        $where['id'] = intval($ReturnArray["memberid"]) - 10000;
        $member=M("member")->where($where)->find();
        if(!$member){
            die;
        }
        $Md5key =$member["apikey"];

        ksort($ReturnArray);
        reset($ReturnArray);
        $md5str = "";
        foreach ($ReturnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));

        if ($sign == $_REQUEST["sign"]) {
            if ($_REQUEST["returncode"] == "00") {
                $this->assign("cache",$ReturnArray);
                $this->assign("goback",U('Pay/Charges/index',array('mchid'=>$ReturnArray["memberid"])));
               $this->display("success");

            }
        }
    }
    public function msgshow(){
        $msg=I("msg")?I("msg"):"非法操作";
        $this->assign("msg",$msg);
        $this->display();
    }
}
