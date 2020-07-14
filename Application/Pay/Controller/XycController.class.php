<?php
namespace Pay\Controller;

/**
 * @author     mselect <445712421@qq.com>
 *2019-11-9 *
 */
class XycController extends PayController{


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


       echo header("Content-type:text/html;charset=utf-8");
        $orderid     = I("request.pays_orderid");
        $body        = I('request.pays_productname');
        $notifyurl   = $this->_site . 'Pay_Xyc_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Xyc_callbackurl.html'; //返回通知



        $parameter   = array(
            'code'         => 'Xyc', // 通道名称
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
        $url       = $return['gateway'].'pay';



        $pay['merchant'] = $return['mch_id'];
        $pay['pay_type'] = $return['appid'];
        $pay['client_ip'] = $this->get_client_ip();
        $pay['sign_type'] = 'MD5';
        $pay['trade_no'] = $return['orderid'];
        $pay['amount'] = sprintf('%.2f', $return['amount']);
        $pay['goods_name'] = 'JIFUBAO';
        $pay['notify_url'] = $notifyurl;

        ksort($pay);
        $str='';
        foreach($pay as $k=>$v){
            $str .= $k."=".$v."&";
        }
        $str .= 'key=' . $key;



        $pay['return_url'] = $callbackurl;
        $pay['device'] = 'pc';


        $pay['sign']=md5($str);

        $response =  $this->curlPost($url,$pay);


        $result = json_decode($response,true);
        if ($result['data']['result_pay'] == 'SUCCESS'){
            $pays_url=$result['data']['pay_url'];
            header("Location: $pays_url");
            exit;
        }else{
           exit('error');
        }


    }

    function get_client_ip() {
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        }
        return $ip;
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

        file_put_contents('s.txt', date("Ymd His").json_encode($content) . PHP_EOL, FILE_APPEND);



        $result = $response['data'];

        if ($result['result_pay']=='SUCCESS'){
           

            $m_Order    = M("Order");
            $order_info = $m_Order->where(['pays_orderid' =>$result["trade_no"]])->find();
            var_export($order_info);
//获取订单信息

            //验签
            $sign = $result['sign'];
            unset($result['attach']);
            unset($result['sign']);



            ksort($result);
            $str='';
            foreach($result as $k=>$v){
                $str .= $k."=".$v."&";
            }
            $str .= 'key=' . $order_info['key'];
            $local_sign = md5($str);

            if($sign==$local_sign){
                

                $rows = array(
                    'out_trade_no' => $order_info['pays_orderid'],
                    'result_code' => '1',
                    'transaction_id' =>  $order_info['pays_orderid'],
                    'fromuser' => '1',
                    'time_end' => time(),
                    'total_fee' => $order_info['pays_amount'],
                    'pays_memberid' => $order_info['pays_memberid']-10000,
                    'bank_type' => '1',
                    'trade_type' => 'Xyc',
                    'payname' => $order_info['pays_zh_tongdao']
                );
                M('Paylog')->add($rows);

                $Websiteconfig = D("Websiteconfig");
                $result=json_encode(array('ret_code' => '0000', 'ret_msg' => '交易成功'));
                    
                    if($order_info['pays_status']==0){
                    $time       = time(); //当前时间
                    $res = $m_Order->where(['id' =>$order_info["id"]])->save(['pays_status' => 3, 'pays_successdate' => $time]);
                    }
                    exit($result);
            }else{//验签

                exit("验签失败");
            }   //验签


        } else {
            exit("fail");
        }




    }

    public function curlPost($url,$data){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        return $tmpInfo;
    }




}

?>
