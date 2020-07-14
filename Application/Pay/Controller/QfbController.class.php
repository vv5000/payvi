<?php
namespace Pay\Controller;

use Org\Util\HttpClient;

class QfbController extends PayController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Pay($array)
    {
        $orderid = I("request.pays_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_Qfb_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Qfb_callbackurl.html'; //返回通知
    //    $error_url = $this->_site . 'https://pay.365cpb.com/Pay_Anxin_notifyurl.html'; //返回通知

        $parameter = array(
            'code' => 'Qfb', // 通道名称
            'title' => 'Qfb',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid' => '',
            'out_trade_id' => $orderid,
            'body'=>$body,
            'channel'=>$array
        );
        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);
        $price      = sprintf("%.2f",$return['amount']);


        $data = array(
            "appid" =>$return['mch_id'],   //商户ID
            "pay_type" => $return['appid'], // 子帐户设置的APPID
            "out_trade_no" => $orderid,
            "amount" => $price,
            "callback_url" => $notifyurl,
            "success_url" => $callbackurl,
            "error_url" => $callbackurl,
            "version" => 'v1.1',
            "out_uid" => $return['mch_id'],
        );



        $sign = $this->getSign($return['signkey'],$data);
        $data['sign'] = $sign;
        $url = $return['gateway'];

        $html = '<form  name="form1" class="form-inline" method="post" action="'.$url.'">';
        foreach ($data as $key => $val) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $html .= '</form>';
        $html .= '<script type="text/javascript">document.form1.submit()</script>';
        echo $html;
        exit();
    }


    public function callbackurl()
    {
        $Order = M("Order");
        $orderid=I('request.out_trade_no/s');
        $find_data = $Order->where(['out_trade_id' => $orderid])->find();
        if($find_data['pay_status'] <> 0){
            //   $this->EditMoney($_REQUEST['orderid'], 'Wechathx', 1);
            header("location:".$find_data['pay_callbackurl']);
            exit('交易成功！');
        }else{
            exit("error");
        }
    }

    // 服务器点对点返回
    public function notifyurl()
    {

        file_put_contents('1.txt', 'POST:'.json_encode($_POST) . PHP_EOL, FILE_APPEND);

        $result = $_POST;

        $data = [
            'appid'        => $result['appid'],
            'pay_type'     => $result['pay_type'],
            'out_trade_no' => $result['out_trade_no'],
            'amount'       => $result['amount'],
            'success_url'  => $result['success_url'],
            'error_url'    => $result['error_url'],
            'out_uid'      => $result['out_uid'],
            'sign'      => $result['sign'],
        ];

        $Order = M("Order");
        $Order_Info = $Order->where(['out_trade_id' => $data['out_trade_no']])->find();
        if(!$Order_Info){
            exit('error:appid');
        }

        if ($this->verifySign($result,$Order_Info['key'])){
            $Websiteconfig = D("Websiteconfig");
            $list = $Websiteconfig->find();
             if($list['hdhd']==1) {
                 $this->EditMoney($Order_Info['pays_orderid'], 'Qfb', 0);
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
     * @Note  生成签名
     * @param $secret   商户密钥
     * @param $data     参与签名的参数
     * @return string
     */
    public function getSign($secret, $data)
    {

        // 去空
        $data = array_filter($data);

        //签名步骤一：按字典序排序参数
        ksort($data);
        $string_a = http_build_query($data);
        $string_a = urldecode($string_a);

        //签名步骤二：在string后加入mch_key
        $string_sign_temp = $string_a . "&key=" . $secret;


        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);

        // 签名步骤四：所有字符转为大写
        $result = strtoupper($sign);

        return $result;
    }


    /**
     * @Note   验证签名
     * @param $data
     * @param $orderStatus
     * @return bool
     */
   public function verifySign($data, $secret) {

        // 验证参数中是否有签名
        if (!isset($data['sign']) || !$data['sign']) {
            return false;
        }
        // 要验证的签名串
        $sign = $data['sign'];
        unset($data['sign']);
        // 生成新的签名、验证传过来的签名
        $sign2 = $this->getSign($secret, $data);


        if ($sign != $sign2) {
            return false;
        }
        return true;
    }

}

?>
