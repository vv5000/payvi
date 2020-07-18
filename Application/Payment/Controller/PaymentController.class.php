<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-08-22
 * Time: 14:34
 */
namespace Payment\Controller;

/**
 * 用户中心首页控制器
 * Class IndexController
 * @package User\Controller
 */
use Think\Controller;

class PaymentController extends Controller
{
	protected $verify_data_ = [
				'code'=>'请选择代付方式！',
				'id'=>'请选择代付订单！', 
				'opt' => '操作方式错误！',
			];



	public function __construct(){
	    parent::__construct();
	}

	protected function findPaymentType($code='default'){
		$where['status'] = 1;
		if($code == 'default'){
			$where['is_default'] = 1;
		}else{
			$where['id'] = $code;
		}		
		$list = M('PayForAnother')->where($where)->find();
		$list || showError('支付方式错误');
		return $list;
	}

	protected function selectOrder($where){
		
		$lists = M('Wttklist')->where($where)->select();
		$lists || showError('无该代付订单或订单当前状态不允许该操作！');
		foreach($lists as $k => $v){
			$lists[$k]['additional'] = json_decode($v['additional'],true);
		}
		return $lists;
	}



	protected function checkMoney($uid,$money){
		$where = ['id' => $uid];
		$balance = M('Member')->where($where)->getField('balance');
		$balance < $money && showError('支付金额错误'); 
	}

	protected function handle($id, $status=1, $return){
	    
	    //处理成功返回的数据
        $data = array();
        if($status == 1){
           $data['status'] = 1;
           $data['memo'] = '申请成功！';
        }else if ($status == 2) {
           $data['status'] = 2;
           $data['cldatetime'] = date('Y-m-d H:i:s', time());
           $data['memo'] = '代付成功';
        }else if($status == 3){
            $data['status'] = 4;
			$data['memo'] = isset($return['memo'])?$return['memo']:'代付失败！';
        }
        if(in_array($status, [1,2,3])){
        	$data = array_merge($data, $return);
        	$where = ['id'=>$id];
        	M('Wttklist')->where($where)->save($data);
        }
   
	}


    protected function push_notify($info)
    {
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
            $arr["pay_md5sign"] = $sign;
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
    }

}