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
class EasyController extends PaymentController
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
        $notifyurl = $this->_site . 'Payment_Easy_notifyurl.html'; //异步通知

        $parameter = [
            'version' => '3.0',//版本号
            'method' => 'Gt.online.pay',//名称
            'batchnumber' => $data['out_trade_no'],//商户号
            'paymoney' => $data['money'],//金额
            'cardNumber' => $data['banknumber'],//银行卡号
            'cardName' => $data['bankfullname'],//银行卡开户姓名
            'bankName' => $data['bankname'],//银行名称
            'notifyUrl' => $notifyurl,//异步通知
            'remarks' => $data['out_trade_no'],//银行名称
            'partner' => $config['mch_id'],//商户ID
        ];
        $sign = $this->_createSign($parameter, $config['signkey']);
        $parameter['sign'] = $sign;


        //file_put_contents('easy.txt', '发送地址：'.$execGateway . PHP_EOL, FILE_APPEND);
        //   file_put_contents('easy.txt', '发送参数：'.json_encode($parameter) . PHP_EOL, FILE_APPEND);


        $response = curlPost($execGateway, $parameter, "", true);
        //   file_put_contents('easy.txt', $response . PHP_EOL, FILE_APPEND);
        $res_array = json_decode($response, true);


        if (empty($res_array)) {
            $return = ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "错误：服务不可用"];
        } else {
            if ($res_array['code'] === '1000') {
                $return = ['status' => self::PAYMENT_SUBMIT_SUCCESS, 'msg' => '提交成功'];
            } else {
                $return = [
                    'status' => self::PAYMENT_PAY_FAILED,
                    'msg' => "错误：{$response['status']}：{$response['message']}"
                ];
            }
        }

        return $return;
    }

    public function PaymentQuery($data, $config)
    {
        $execGateway = $config['query_gateway'];
        $parameter = [
            'version' => '3.0',//版本号
            'method' => 'Gt.online.payquery',//名称
            'batchnumber' => $data['out_trade_no'],//商户号
            'partner' => $config['mch_id'],//商户ID
        ];


        $sign = $this->_createSign2($parameter, $config['signkey']);
        $parameter['sign'] = $sign;

        $response2 = file_get_contents($execGateway .'?'.http_build_query($parameter));

        $response = json_decode($response2, true);
        if (empty($response)) {
            $return = ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "错误：服务不可用"];
        } else {

            if ($response['code'] == 1000) {

                if ($response['data']['status'] == '0' || $response['data']['status'] == '3') {
                    $return = ['status' => self::PAYMENT_SUBMIT_SUCCESS, 'msg' => '处理中'];
                }elseif ($response['data']['status'] == '1') {
                    $return = ['status' => self::PAYMENT_PAY_SUCCESS, 'msg' => '付款成功'];
                } else {
                    $return =
                        ['status' => self::PAYMENT_PAY_FAILED, 'msg' => "{$response['data']['status']}：{$response['data']['message']}"];
                }

            } else {
                $return = [
                    'status' => self::PAYMENT_PAY_FAILED,
                    'msg' => "错误：{$response['status']}：{$response['message']}"
                ];
            }
        }

        return $return;
    }

    public function notifyurl()
    {
        file_put_contents('easy.txt','回调数据:'.json_encode($_REQUEST).',返回结果：'.PHP_EOL, FILE_APPEND);
        $datas = $_REQUEST;
        $data=[
            'version' => $datas['version'],
            'partner' => $datas['partner'],
            'batchnumber' => $datas['batchnumber'],
            'status' => $datas['status'],
            'message' => $datas['message'],
            'paymoney' => $datas['paymoney'],
        ];
        $sign = $datas['sign'];

        $info = M('wttklist')->where(['out_trade_no' => $datas['batchnumber']])->find();
        $map['id'] = $info['id'];

        $channel = $this->findPaymentType($info['df_id']);

        $local_sign = $this->_createSign3($data, $channel['signkey']);

        if ($local_sign == $sign) {
            //签名验证成功
            $cost = $channel['rate_type'] ? bcmul($info['money'], $channel['cost_rate'], 2) : $channel['cost_rate'];
            $data2 = [
                'memo' => $data['message'],
                'df_id' => $channel['id'],
                'code' => $channel['code'],
                'df_name' => $channel['title'],
                'channel_mch_id' => $channel['mch_id'],
                'cost_rate' => $channel['cost_rate'],
                'cost' => $cost,
                'rate_type' => $channel['rate_type'],
            ];

            if($datas['status']==0 || $datas['status']==3) {
                $this->handle($info['id'], self::PAYMENT_SUBMIT_SUCCESS, $data2);
            }elseif($datas['status']==1){   //成功标记
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
            }elseif($datas['status']==4 || $datas['status']==2){
                $this->handle($info['id'], self::PAYMENT_PAY_FAILED, $data2);
            }
            exit('ok');
        }else{
            exit('验签错误');
        }

    }


    /**
     * 规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
     */
    private function _createSign($data, $key)
    {

        $str = 'batchnumber=' . $data['batchnumber'];
        $str .= '&cardNumber=' . $data['cardNumber'];
        $str .= '&method=Gt.online.pay&partner=' . $data['partner'];
        $str .= '&paymoney=' . $data['paymoney'];
        $str .= '&version=3.0' . $key;
        return md5($str);
    }

    /**
     * 规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
     */
    private function _createSign2($data, $key)
    {
        $str = 'version=3.0&method=Gt.online.payquery&partner=' . $data['partner'];
        $str .= '&batchnumber=' . $data['batchnumber'] . $key;
        return md5($str);
    }
    /**
     * 规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
     */
    private function _createSign3($data, $key)
    {
        $str ="version=1.0&partner=".$data['partner'];
        $str .="&batchnumber=".$data['batchnumber'];
        $str .="&status=".$data['status'];
        $str .="&paymoney=".$data['paymoney'].$key;
        return md5($str);
    }


    private function _curlpost($url,$data)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded",
                "cache-control: no-cache"
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        return $response;
    }
}