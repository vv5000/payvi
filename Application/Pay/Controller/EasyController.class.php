<?php
/**
 * Created by PhpStorm.
 * User: win 10
 * Date: 2018/6/11
 * Time: 11:41
 */

namespace Pay\Controller;

use Org\Util\WxH5Pay;

/**
 * 易数据代付
 *
 * Class XunjiefuController
 * @package Payment\Controller
 */
class EasyController extends PayController
{
    public function __construct() {
        parent::__construct();
    }

    public function Pay($array) {
        $orderid = I("request.pays_orderid");
        $body = I('request.pay_productname');
        $notifyurl = $this->_site . 'Pay_Easy_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Easy_callbackurl.html'; //返回通知

        $parameter = array(
            'code' => 'Easy', // 通道名称
            'title' => 'Easy',
            'exchange' => 1, // 金额比例
            'gateway' => '',
            'orderid' => '',
            'out_trade_id' => $orderid,
            'body' => $body,
            'channel' => $array
        );

        // 订单号，可以为空，如果为空，由系统统一的生成
        $return = $this->orderadd($parameter);

        $parameter = [
            'version' => '3.0',//版本号
            'method' => 'Gt.online.interface',//名称
            'partner' => $return['mch_id'],//商户ID
            'banktype' => $return['appid'],//通道
            'paymoney' => $return['amount'],//金额
            'ordernumber' => $orderid,//订单号
            'callbackurl' => $notifyurl,
            'hrefbackurl' => $callbackurl,
        ];

        $parameter['sign']=$this->_createSign($parameter,$return['signkey']);

        $html = '<form  name="form1" class="form-inline" method="post" action="'.$return['gateway'].'">';
        foreach ($parameter as $key => $val) {
            $html .= '<input type="hidden" name="' . $key . '" value="' . $val . '">';
        }
        $html .= '</form>';

        $html .= '<script type="text/javascript">document.form1.submit()</script>';

        echo $html;
    }

    public function callbackurl()
    {
        $Order = M("Order");
        $orderid=I('request.ordernumber/s');
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
            'partner'      => $result['partner'],
            'ordernumber'     => $result['ordernumber'],
            'orderstatus' => $result['orderstatus'],
            'paymoney'       => $result['paymoney'],
            'sysnumber'      => $result['sysnumber'],
            'sign'      => $result['sign'],
        ];

        $Order = M("Order");
        $Order_Info = $Order->where(['out_trade_id' => $data['ordernumber']])->find();
        if(!$Order_Info){
            exit('error:appid');
        }

        if ($this->verifySign($data,$Order_Info['key']) && $data['orderstatus']==1){
            $Websiteconfig = D("Websiteconfig");
            $list = $Websiteconfig->find();
            if($list['hdhd']==1) {
                $this->EditMoney($Order_Info['pays_orderid'], 'Easy', 0);
                exit('ok');
            }else{
                $time = time(); //当前时间
                $res = $Order->where(['pays_orderid' =>$Order_Info['pays_orderid'], 'pays_status' => 0])->save(['pays_status' => 3, 'pays_successdate' => $time]);
                exit("ok");
            }

        }else{
            exit('FAIL');
        }

    }
    /**
     * 规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
     */
    private function _createSign($data, $key)
    {
        $str = 'version=3.0&method='.$data['method'].'&partner=' . $data['partner'];
        $str .= '&banktype=' . $data['banktype'];
        $str .= '&paymoney=' . $data['paymoney'];
        $str .= '&ordernumber=' . $data['ordernumber'];
        $str .= '&callbackurl=' . $data['callbackurl'].$key;
        return md5($str);
    }
 /**
     * 规则是:按参数名称a-z排序,遇到空值的参数不参加签名。
     */
    private function verifySign($data, $key)
    {
        $str = 'partner=' . $data['partner'];
        $str .= '&ordernumber=' . $data['ordernumber'];
        $str .= '&orderstatus=' . $data['orderstatus'];
        $str .= '&paymoney=' . $data['paymoney'];
        $str .= '&sysnumber=' . $data['sysnumber'].$key;
        $sign= md5($str);

        if ($sign != $data['sign']) {
            return false;
        }
        return true;

    }




}