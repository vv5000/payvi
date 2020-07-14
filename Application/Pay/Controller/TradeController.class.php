<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-10-30
 * Time: 21:24
 */
namespace Pay\Controller;

class TradeController extends PayController
{
    private $userid;
    private $apikey;
    public function __construct()
    {
        parent::__construct();
        $memberid = I("request.pays_memberid",0,'intval') - 10000;
        if (empty($memberid) || $memberid<=0) {
            $this->showmessage("不存在的商户编号!");
        }
        $this->userid = $memberid;
        $fans = M('Member')->where(['id'=>$this->userid])->find();
        if(!$fans){
            $this->showmessage('商户不存在');
        }
        $this->apikey = $fans['apikey'];
    }

    //订单查询
    public function query()
    {
        $out_trade_id = I('request.pays_orderid');
        $sign = I('request.pays_md5sign');
        if(empty($out_trade_id)){
            $this->showmessage("不存在的交易订单号.");
        }
        $request = [
            'pays_memberid'=>I("request.pays_memberid"),
            'pays_orderid'=>$out_trade_id
        ];
        $signature = $this->createSign($this->apikey,$request);
        if($signature != $sign){
            $this->showmessage('验签失败!');
        }
        $order = M('Order')->where(['pays_memberid'=>$request['pays_memberid'],
            'out_trade_id'=>$request['pays_orderid']])->find();
        if(!$order){
            $this->showmessage('不存在的交易订单.');
        }
        if($order['pays_status']==0){
            $msg = "NOTPAY";
        }elseif ($order['pays_status'] ==1 || $order['pays_status'] == 2){
            $msg = "SUCCESS";
        }
        $return = [
            'memberid'=>$order['pays_memberid'],
            'orderid'=>$order['out_trade_id'],
            'amount'=>$order['pays_amount'],
            'time_end'=>date('Y-m-d H:i:s',$order['pays_successdate']),
            'transaction_id'=>$order['pays_orderid'],
            'returncode'=>"00",
            'trade_state'=>$msg
        ];
        $return['sign'] = $this->createSign($this->apikey,$return);
        echo json_encode($return);
    }
}