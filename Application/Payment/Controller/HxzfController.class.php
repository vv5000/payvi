<?php
/**
 * Created by PhpStorm.
 * User: win 10
 * Date: 2018/6/11
 * Time: 11:41
 */

namespace Payment\Controller;

use think\Log;

/**
 * 易数据代付
 *
 * Class XunjiefuController
 * @package Payment\Controller
 */
class HxzfController extends PaymentController
{
    //代付状态
    const PAYMENT_SUBMIT_SUCCESS = 1; //处理中
    const PAYMENT_PAY_SUCCESS = 2; //已打款
    const PAYMENT_PAY_FAILED = 3; //已驳回
    const PAYMENT_PAY_UNKNOWN = 4; //待确认


    public function __construct()
    {
        parent::__construct();
    }

    public function test()
    {
        echo "HEK";
//        $config = M('pays_for_another')->where([
//            'id' => 21
//        ])->find();
//
//
//        $data = M('wttklist')->where([
//            'id' => 66,
//        ])->find();

//        $res = $this->PaymentQuery($data, $config);
//       $res = $this->PaymentExec($data, $config);
//        var_dump($res);exit;
    }

    public function PaymentExec($data, $config)
    {
        $execGateway = $config['exec_gateway'];
        $notifyurl = $this->_site . 'Payment_Hxzf_notifyurl.html'; //异步通知
        $priKey = $this->deal_prikey($config['signkey']);

        $datas = array(
            'merchantNumber'=>$config['mch_id'] //商户号
        );
        //获取预处理字符串
        $signString = $this->getSignString($datas);
        $sign = $this->getSign($signString,$priKey);
        $datas['sign'] = $sign;
        $content = json_encode($datas);
        $balanceJson = $this->http_request($execGateway."/queryBalance",$content);
        $balance = json_decode($balanceJson, true);
        if($balance['status']!=1){
            return  ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "余额查询失败"];
        }
        if($data['money']>$balance['data']['balance']){
            return  ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "上级余额不足，请充值！"];
        }
        //代付余额查询



        $datas = array(
            'amount'=>$data['money'],//前端提交过来金钱
            'merchantNumber'=>$config['mch_id'],//商户号
            'merchantOrderNumber'=>$data['out_trade_no'],//商户订单号
            'receiveCard'=>$data['banknumber'],//卡号
            'receiveName'=>$data['bankfullname'],//姓名
            'callBackUrl'=>$notifyurl,//回调url
        );

        $priKey = $this->deal_prikey($config['signkey']);
//获取预处理字符串
        $signString = $this->getSignString($datas);
        $sign = $this->getSign($signString,$priKey);
        $datas['sign'] = $sign;
        $content = json_encode($datas);
        $response = $this->http_request($execGateway.'/execute',$content);   //代付

        //file_put_contents('easy.txt', '发送地址：'.$execGateway . PHP_EOL, FILE_APPEND);
        //   file_put_contents('easy.txt', '发送参数：'.json_encode($parameter) . PHP_EOL, FILE_APPEND);

        $res_array = json_decode($response, true);


        if (empty($res_array)) {
            $return = ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "错误：服务不可用"];
        } else {
            if ($res_array['status'] === 1) {
                $return = ['status' => self::PAYMENT_SUBMIT_SUCCESS, 'msg' => '提交成功'];
            } else {
                $return = [
                    'status' => self::PAYMENT_PAY_FAILED,
                    'msg' => "错误：{$response['msg']}：{$response['responseTime']}"
                ];
            }
        }

        return $return;
    }

    public function PaymentQuery($data, $config)
    {
        $execGateway = $config['query_gateway'];
        $priKey = $this->deal_prikey($config['signkey']);

        $datas = array(
            'merchantNumber'=>$config['mch_id'],//商户号
            'merchantOrderNumber'=>$data['out_trade_no']//商户订单号
        );
        $signString = $this->getSignString($datas);
        $sign = $this->getSign($signString,$priKey);
        $datas['sign'] = $sign;
        $content = json_encode($datas);
        $response2 = http_request($execGateway.'/query',$content);
        $response = json_decode($response2, true);
        if (empty($response)) {
            $return = ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "错误：服务不可用"];
        } else {

            if ($response['status'] == 1) {
               $return = ['status' => self::PAYMENT_PAY_SUCCESS, 'msg' => '付款成功'];
            } else {
                $return = [
                    'status' => self::PAYMENT_PAY_FAILED,
                    'msg' => "错误：{$response['msg']}}"
                ];
            }
        }

        return $return;
    }

    public function notifyurl()
    {
        file_put_contents('easy.txt','回调数据:'.json_encode($_REQUEST).',返回结果：'.PHP_EOL, FILE_APPEND);
        $datas = json_decode($_REQUEST,true);

        $info = M('wttklist')->where(['out_trade_no' => $datas['merchantOrderNumber']])->find();
        $map['id'] = $info['id'];
        $channel = $this->findPaymentType($info['df_id']);
        $priKey = $this->deal_prikey($channel['signkey']);


        $signString = $datas['sign'];
        $deSign=$this->PrivateDecrypt($signString,$priKey);//用私钥解密sign字段
        parse_str($deSign,$myAry);//解析各字段到数组

        $data = $myAry;

        if ($myAry['payStatus'] == 1) {   //状态是成功的
            //签名验证成功
            $cost = $channel['rate_type'] ? bcmul($info['money'], $channel['cost_rate'], 2) : $channel['cost_rate'];
            $data2 = [
                'memo' => $data['payStatus'],
                'df_id' => $channel['id'],
                'code' => $channel['code'],
                'df_name' => $channel['title'],
                'channel_mch_id' => $channel['mch_id'],
                'cost_rate' => $channel['cost_rate'],
                'cost' => $cost,
                'rate_type' => $channel['rate_type'],
            ];

             //成功标记
                $this->handle($info['id'], self::PAYMENT_PAY_SUCCESS, $data2);
                $apiid = $info['df_api_id'];
                $useridd = $info['userid'];
                $Order = M("df_api_order");
                $ma = M("member");
                $apikey = $ma->where(['id' => $useridd])->getField("apikey");
                $notifyurl = $Order->where(['id' => $apiid])->getField("notifyurl");

                if($notifyurl){
                    $out_trade_no = $Order->where(['id' => $apiid])->getField("out_trade_no");
                    $trade_no = $Order->where(['id' => $apiid])->getField("trade_no");
                    $money = $Order->where(['id' => $apiid])->getField("money");
                    $arr['out_trade_no'] =  $out_trade_no;
                    $arr['amount'] =  $money;
                    $arr['transaction_id'] =  $trade_no;
                    $arr['status'] =  'success';
                    $arr['msg'] =  'success';
                    ksort($arr);
                    $md5str = "";
                    foreach ($arr as $key => $val) {
                        $md5str = $md5str . $key . "=" . $val . "&";
                    }

                    $sign = strtoupper(md5($md5str . "key=" . $apikey));
                    $arr["pays_md5sign"] = $sign;
                    $postData = http_build_query($arr);
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $notifyurl);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // stop verifying certificate
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                    $res = curl_exec($curl);
                    file_put_contents('easy.txt','回调数据:'.$postData.',返回结果：'.$res.PHP_EOL, FILE_APPEND);
                    curl_close($curl);
                }
            exit('SUCCESS');
        }else{
            exit('验签错误');
        }

    }

    /**
     * 生成签名
     * @param    string     $signString 待签名字符串
     * @param    [type]     $priKey     私钥
     * @return   string     base64结果值
     */
    private function getSign($signString,$priKey){
        $privKeyId = openssl_pkey_get_private($priKey);
        $signature = '';
        $algo = "SHA256";
        openssl_sign($signString, $signature, $privKeyId,$algo);
        openssl_free_key($privKeyId);
        return bin2hex($signature);
    }

    /**
     * 校验签名
     * @param    string     $pubKey 公钥
     * @param    string     $sign   签名
     * @param    string     $toSign 待签名字符串
     * @return   bool
     */
    private function checkSign($pubKey,$sign,$toSign){
        $publicKeyId = openssl_pkey_get_public($pubKey);
        $result = openssl_verify($toSign, base64_decode($sign), $publicKeyId);
        openssl_free_key($publicKeyId);
        return $result === 1 ? true : false;
    }

    /**
     * 获取待签名字符串
     * @param    array     $params 参数数组
     * @return   string
     */
    private  function getSignString($params){
        unset($params['sign']);
        ksort($params);
        reset($params);

        $pairs = array();
        foreach ($params as $k => $v) {
            if(!empty($v)){
                $pairs[] = "$k=$v";
            }
        }

        return implode('&', $pairs);

    }
    private function http_request($url, $data = null)//模拟post提交数据
    {
        // print_r($data);die();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     *@函数说明： 处理密钥
     * @param $sourcekey
     * @return string
     * @author: 秋枫红叶
     * @date: 2020/7/21  15:47
     */
    private function deal_prikey($sourcekey)
    {
        $sourcekey=str_replace(array(" ","\r\n", "\r", "\n","\t"),"",$sourcekey);
        $priKey = "-----BEGIN RSA PRIVATE KEY-----\n" . wordwrap($sourcekey, 64, "\n", true) . "\n-----END RSA PRIVATE KEY-----";
        return $priKey;
    }


    /**
     *@函数说明：私钥解密
     * @param $encrypted
     * @param $pKey
     * @return string
     * @author: 秋枫红叶
     * @date: 2020/7/21  16:10
     */
    private function PrivateDecrypt($encrypted,$pKey)
    {
        $crypto = '';
        $encrypted = hex2bin($encrypted);
        foreach (str_split($encrypted, 256) as $chunk) {
            openssl_private_decrypt($chunk, $decryptData,$pKey);
            $crypto .= $decryptData;
        }
        return $crypto;
    }
}