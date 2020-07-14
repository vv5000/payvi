<?php
namespace Pay\Controller;

use Org\Util\HttpClient;

class AnxinController extends PayController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Pay($array)
    {
        $orderid = I("request.pays_orderid");
        $orderid = I("request.pays_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_Anxin_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Anxin_callbackurl.html'; //返回通知
        $error_url = $this->_site . 'https://pay.365cpb.com/Pay_Anxin_notifyurl.html'; //返回通知

        $parameter = array(
            'code' => 'Anxin', // 通道名称
            'title' => '安信科技',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid' => '',
            'out_trade_id' => $orderid,
            'body'=>$body,
            'channel'=>$array
        );
        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);
        $price      = intval($return['amount']*100);


        $data = array(
            "merchat_id" =>$return['mch_id'],   //商户ID
            "order_sn" => $orderid,
            "amount" => $price,
            "channel" => $return['appid'], // 子帐户设置的APPID
            "notify_url" => $notifyurl,
            "payway" => 1,
            "content" => '安信科技',
        );
        $sign = $this->sign($return['signkey'],$data);
        $data['sign'] = $sign;
        //第四方接口地址 （网关）
      //  var_export($data);
        //   die();
        $url = $return['gateway'];
        $pay_result = $this->phpcurl($url, $data);

      //  var_export($pay_result);
        if ($pay_result['result']==1) {
            header("Location:".$pay_result['pay_url']);
        }else{
            print_r($pay_result);
        }
        exit();
    }


    public function callbackurl()
    {
        $Order = M("Order");
        $orderid=I('request.out_trade_no/s');
        $find_data = $Order->where(['pay_orderid' => $orderid])->find();
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

     //   $this->check_black(__CLASS__);
        file_put_contents('1.txt', 'POST:'.json_encode($_POST) . PHP_EOL, FILE_APPEND);

        $result = $_POST;

        $data['merchat_id'] 	= $result['merchat_id'];
        $data['order_sn']   	= $result['order_sn'];
        $data['amount']     	= $result['amount'];
        $data['content']    	= $result['content'];
        $data['payway']     	= $result['payway'];
        $data['finished_time'] 	= $result['finished_time'];
        $data['status']			= $result['status'];
        $_sign					= $result['sign'];

        $Order = M("Order");
        $Order_Info = $Order->where(['out_trade_id' => $data['order_sn']])->find();

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


    //发送请求
    function phpcurl($url,$data)
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


}

?>
