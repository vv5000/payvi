<?php

/**
 * @author zhangjianwei
 * @date   2018-06-01
 */

namespace Pay\Controller;

use Think\Log;


/**
 * 系统支付渠道 （上游也是自己的系统）
 
 * @package Pay\Controller
 */
class XTPAYController extends PayController
{

    private $exchange = 1;
    private $gateway  = 'http://pay.atyhdj.cn/npay/buyer';

    public function Pay($channel)
    {
        $exchange = $this->exchange;
        $return = $this->getParameter('系统支付', $channel,XTPAYController::class, $exchange);
        $osn = date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $native = [
            "version"    => "1.0", // 版本号
            "method"     => "trade", // 交易代码  trade
            "mchnt_cd"      => "10000008", // 商户代码
            "tran_seq"   => $osn,
            "timestamp"   => date("YmdHis"), // 交易时间
            "pays_type"    => "11100", // 商品编码
            "amount"   => "10.00",
            "order_info" => "支付",
            "notify_url" => "notifyurl"

        ];

        $md5key = "O9OXVwrDcToCTl41MVRpEfE2ynC0AZ7X";

        ksort($native);
        $md5str = "";
        foreach ($native as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        $native["sign"] = $sign;
        $this->setHtml($this->gateway, $native);
    }

    //异步通知地址
    public function notifyurl()
    {
        $returnArray = [
            "memberid"       => $_REQUEST["mchnt_cd"], // 商户ID
            "orderid"        => $_REQUEST["tran_seq"], // 订单号
            "amount"         => $_REQUEST["amount"], // 交易金额
            "datetime"       => $_REQUEST["timestamp"], // 交易时间
            "transaction_id" => $_REQUEST["deal_tran_seq"], // 支付流水号
            "returncode"     => $_REQUEST["return_code"],
        ];
        $order_info = M('Order')->where(['pays_orderid' => $returnArray['orderid']])->find();
        $md5key = $return['signkey'];  //商户秘钥
        ksort($returnArray);
        reset($returnArray);
        $md5str = "";
        foreach ($returnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        if ($sign !== $_REQUEST["sign"]) {
            echo "签名校验错误";
            Log::record("闪快支付异步通知：签名校验错误:\n" . json_encode($returnArray), Log::ERR);
            return false;
        }

        if ($_REQUEST["returncode"] == "00") {
            //修改订单信息
            $this->EditMoney($returnArray['orderid'], '', 0);
            Log::record("闪快支付异步通知：" . "交易成功！订单号：" . $returnArray["orderid"], Log::INFO);
            exit("ok");
        } else {
            Log::record("闪快支付异步通知：" . "交易失败！订单号：" . $returnArray["orderid"] . "，参数：". json_encode($returnArray), Log::ERR);
        }

    }

    //同步回调地址
    public function callbackurl()
    {
        $returnArray = [
            "memberid"       => $_REQUEST["memberid"], // 商户ID
            "orderid"        => $_REQUEST["orderid"], // 订单号
            "amount"         => $_REQUEST["amount"], // 交易金额
            "datetime"       => $_REQUEST["datetime"], // 交易时间
            "transaction_id" => $_REQUEST["transaction_id"], // 支付流水号
            "returncode"     => $_REQUEST["returncode"],
        ];
        $order_info = M('Order')->where(['pays_orderid' => $returnArray['orderid']])->find();
        if (!$order_info) {
            echo "订单不存在";
            return false;
        }
        $userid = intval($order_info["pays_memberid"] - 10000); // 商户ID
        $member_info = M('Member')->where(['id' => $userid])->find();
        if (!$member_info) {
            echo "商户不存在";
            return false;
        }

        $md5key = $return['signkey'];  //商户秘钥
        ksort($returnArray);
        reset($returnArray);
        $md5str = "";
        foreach ($returnArray as $key => $val) {
            $md5str = $md5str . $key . "=" . $val . "&";
        }
        $sign = strtoupper(md5($md5str . "key=" . $md5key));
        if ($sign !== $_REQUEST["sign"]) {
            echo "签名校验错误";
            Log::record("闪快支付同步通知：签名校验错误:\n" . json_encode($returnArray), Log::ERR);
            return false;
        }

        if ($_REQUEST["returncode"] == "00") {
            Log::record("闪快支付同步通知：" . "交易成功！订单号：" . $returnArray["orderid"], Log::INFO);
        } else {
            Log::record("闪快支付同步通知：" . "交易失败！订单号：" . $returnArray["orderid"] . "，参数：". json_encode($returnArray), Log::ERR);
        }


        $return_array = [ // 返回字段
                          "memberid"       => $order_info["pays_memberid"], // 商户ID
                          "orderid"        => $order_info['out_trade_id'], // 订单号
                          'transaction_id' => $order_info["pays_orderid"], //支付流水号
                          "amount"         => $returnArray["amount"], // 交易金额
                          "datetime"       => date("YmdHis"), // 交易时间
                          "returncode"     => $returnArray['returncode'], // 交易状态
        ];
        $sign = $this->createSign($member_info['apikey'], $return_array);
        $return_array["sign"] = $sign;
        $return_array["attach"] = $order_info["attach"];

        $this->setHtml($order_info["pays_callbackurl"], $return_array);
    }

}