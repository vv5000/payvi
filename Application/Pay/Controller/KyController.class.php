<?php

namespace Pay\Controller;

use Org\Util\WxH5Pay;

class KyController extends PayController {

    public function __construct() {
        parent::__construct();
    }

    public function Pay($array) {
        $orderid = I("request.pays_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_Ky_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Ky_callbackurl.html'; //返回通知

        $parameter = array(
            'code' => 'Ky', // 通道名称
            'title' => 'Ky',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid' => '',
            'out_trade_id' => $orderid,
            'body' => $body,
            'channel' => $array
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);

       
        $native = array(
            "price" => $return['amount'],
            "paybank" =>  $return['mch_id'],
            "orderid" => $return['orderid'],
            "notify_url" => $notifyurl,
            "return_url" => $callbackurl,
        );


        $html = '<form  name="form1" class="form-inline" method="post" action="'.$return['gateway'].'">';

        foreach ($native as $key => $val) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $html .= '</form>';

        $html .= '<script type="text/javascript">document.form1.submit()</script>';

        echo $html;
    }

    public function callbackurl() {
       
        $Order = M("Order");
        $pay_status = $Order->where(['pay_orderid' => $_REQUEST["orderid"]])->getField("pays_status");
        $callbackurl = $Order->where(['pay_orderid' => $_REQUEST["orderid"]])->getField("pays_callbackurl");
        echo $pay_status;
        header("location:$callbackurl");
        die;       
    }

    // 服务器点对点返回
    public function notifyurl() {


       // file_put_contents('1.txt', 'POST:'.json_encode($_POST) . PHP_EOL, FILE_APPEND);

        $result = $_POST;

        $data['orderid'] 	= $result['orderid'];
        $data['status']   	= $result['status'];
        $_sign				= $result['sign'];

        $Order = M("Order");
        $Order_Info = $Order->where(['pays_orderid' => $data['orderid']])->find();

        $_signed = $this->sign($Order_Info['key'], $data);

        if ($_signed != $_sign) exit('error:key');

        if($result['status'] == 1){

            $Websiteconfig = D("Websiteconfig");
            $list = $Websiteconfig->find();
            if($list['hdhd']==1) {
                $this->EditMoney($Order_Info['pays_orderid'], 'Anxin', 0);
                exit('success');
            }else{
                $time = time(); //当前时间
                $res = $Order->where(['pays_orderid' =>$Order_Info['pays_orderid'], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);
                exit("success");
            }

        }else{
            exit('FAIL');
        }
    }
    
    
     /**
     * 签名算法
     * @param string $key_id S_KEY（商户KEY）
     * @param array $array 例子：$array = array('amount'=>'1.00','out_trade_no'=>'2018123645787452');
     * @return string
     */
    function sign ($apikey, $data) {
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

}