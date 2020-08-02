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
class MnhddController extends PaymentController
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

        $bankCode= $this->getBankCode('招商银行');
        echo $bankCode;
    }

    public function PaymentExec($data, $config)
    {
        $execGateway = $config['exec_gateway'];
        $this->_site = ((is_https()) ? 'https' : 'http') . '://' . C("DOMAIN") . '/';
        $notifyurl = $this->_site . 'Payment_Mnhdd_notifyurl.html'; //异步通知

        $datas = array(
            'mchid'=>$config['mch_id'] //商户号
        );
        $sign = $this->getSign($datas,$config['signkey']);
        $datas['pay_md5sign'] = $sign;
        $content = json_encode($datas);
        $balanceJson = $this->http_request($execGateway."/Payment_Dfpay_balance.html",$content);
        file_put_contents('easy.txt', '查询余额回调结果：'.$balanceJson . PHP_EOL, FILE_APPEND);

        $balance = json_decode($balanceJson, true);
        if($balance['status']!='success'){
            return  ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "余额查询失败"];
        }
        if($data['money']>$balance['balance']){
            return  ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "上级余额不足，请充值！"];
        }
        //代付余额查询


        $datas = array(
            "mchid"=> $config['mch_id'],//商户号
            "out_trade_no"=> $data['out_trade_no'],//商户订单号
            "money"=>$data['money'],//前端提交过来金钱
            "bankCode"=> $this->getBankCode($data['bankname']),
            "subbranch"=> $data['bankname'],//银行名称
            "accountname"=> $data['bankfullname'],//姓名
            "cardnumber"=> $data['banknumber'],//银行卡号
            "dfpay_notifyurl"=> $notifyurl
        );
        $sign = $this->getSign($datas,$config['signkey']);
        $datas['pay_md5sign'] = $sign;
        $content = json_encode($datas);
        $response = $this->http_request($execGateway.'/Payment_Dfpay_add.html',$content);   //代付


        $res_array = json_decode($response, true);

        file_put_contents('easy.txt', '提付返回结果：'.$response . PHP_EOL, FILE_APPEND);

        if (empty($res_array)) {
            $return = ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "错误：服务不可用"];
        } else {
            if ($res_array['status'] === 'success') {
                $return = ['status' => self::PAYMENT_SUBMIT_SUCCESS, 'msg' => '提交成功'];
            } else {
                $return = [
                    'status' => self::PAYMENT_PAY_FAILED,
                    'msg' => "错误：{$response['msg']}"
                ];
            }
        }

        return $return;
    }


    public function getBankCode($bankname){

            $banklist = [
                "工商银行" => "ICBC",
                "农业银行" => "ABC",
                "招商银行" => "CMB",
                "建设银行" => "CCB",
                "北京银行" => "BCCB",
                "中国银行" => "BOC",
                "交通银行" => "COMM",
                "民生银行" => "CMBC",
                "上海银行" => "BOS",
                "渤海银行" => "CBHB",
                "光大银行" => "CEB",
                "兴业银行" => "CIB",
                "中信银行" => "CITIC",
                "浙商银行" => "CZB",
                "广发银行" => "GDB",
                "华夏银行" => "HXB",
                "杭州银行" => "HZCB",
                "南京银行" => "NJCB",
                "平安银行" => "PINGAN",
                "邮政储蓄银行" => "PSBC",
                "浦发银行" => "SPDB",
                "宁波银行" => "NINGBO",
                "花旗银行" => "CITI",
                "江苏银行" => "JIANGSU"
            ];
            $bank = trim($bankname);
            if(!empty($banklist[$bankname])){
                return $banklist[$bankname];
            }else{
                return "";
            }
    }

    public function PaymentQuery($data, $config)
    {
        $execGateway = $config['query_gateway'];
        $datas = array(
            "mchid" => $config['mch_id'],
            "out_trade_no" => $data['out_trade_no']//商户订单号,
        );
        $sign = $this->getSign($datas,$config['signkey']);
        $datas['pay_md5sign'] = $sign;
        $content = json_encode($datas);
        $response2 = $this->http_request($execGateway.'/Payment_Dfpay_query.html',$content);
        file_put_contents('easy.txt', '查询返回结果：'.$response2 . PHP_EOL, FILE_APPEND);

        $response = json_decode($response2, true);
        if (empty($response)) {
            $return = ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "错误：服务不可用"];
        } else {

            if ($response['refCode'] == 1) {
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
        $data=file_get_contents('php://input');

   //     $data='{"transaction_id":"689632350","amount":"10.00","pay_md5sign":"2EF4963B238F08DBF1A71783314C1B22","mchid":10898818,"out_trade_no":"202008021730584736","refMsg":"\u4ee3\u4ed8\u4ea4\u6613\u6210\u529f","success_time":"2020-08-02 17:44:01","refCode":"1","status":"success"}';

        $datas=json_decode($data,true);        //返回的数组
    //    var_export($datas);

        file_put_contents('easy.txt','回调数据:'.json_encode($datas).',返回结果2：'.PHP_EOL, FILE_APPEND);


        $info = M('wttklist')->where(['out_trade_no' => $datas['out_trade_no']])->find();
        $map['id'] = $info['id'];
        $channel = $this->findPaymentType($info['df_id']);
        $priKey = $channel['signkey'];
        $sign = $datas['pay_md5sign'];//返回的 sign
        $local_sign = $this->getSign($datas, $priKey);  //本地sign

        if ($sign==$local_sign && $datas['status'] == 'success') {   //状态是成功的
            //签名验证成功
            $cost = $channel['rate_type'] ? bcmul($info['money'], $channel['cost_rate'], 2) : $channel['cost_rate'];
            $data2 = [
                'memo' => $datas['transaction_id'],
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
    private function getSign($datas,$priKey){
        $signString = $this->getSignString($datas);
        $sign = strtoupper(md5($signString . "&key=" . $priKey));
        return $sign;
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
        unset($params['pay_md5sign']);
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
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json','charset=UTF-8'));
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }



}