<?php
namespace Pay\Model;
use Think\Model;
/**
 * Created by PhpStorm.
 * Date: 2018-10-30
 * Time: 12:00
 */

class IndexModel extends Model
{
    protected $channel; //

    protected $memberid; //商户ID

    protected $pays_amount; //交易金额

    protected $bankcode; //银行码

    protected $orderid; //订单号

    public function __construct($native)
    {
        parent::__construct();
        $this->requst = $native;
        var_dump($native);
        /*
        if (empty($_POST)) {
            $this->showmessage('no data!');
        }
        */
        $this->firstCheckParams(); //初步验证参数 ，设置memberid，pays_amount，bankcode属性

        $this->judgeRepeatOrder(); //验证是否可以提交重复订单

        $this->userRiskcontrol(); //用户风控检测

        $this->productIsOpen(); //判断通道是否开启

        $this->setChannelApiControl(); //判断是否开启支付渠道 ，获取并设置支付通api的id和通道风控
    }
    protected function showmessage($msg = '', $fields = array())
    {
        header('Content-Type:application/json; charset=utf-8');
        $data = array('result' => '200', 'msg' => $msg, 'data' => $fields);
        echo json_encode($data, 320);
        exit;
    }

    public function index()
    {

        //进入支付
        if ($this->channel['api']) {

            $info = M('Channel')->where(['id' => $this->channel['api'], 'status' => 1])->find();
            $this->channel['zdy_array'] = $this->requst;  //传入数组
                       //是否存在通道文件
            if (!is_file(APP_PATH . '/' . MODULE_NAME . '/Controller/' . $info['code'] . 'Controller.class.php')) {
                $this->showmessage('支付通道不存在', ['pays_bankcode' => $this->channel['api']]);
            }
            // var_dump($info);
            if (R($info['code'] . '/Pay', [$this->channel]) === false) {
                $this->showmessage('服务器维护中,请稍后再试...');
            }
        } else {
            $this->showmessage("抱歉..网络异常暂时无法完成您的请求");
        }
    }

    //======================================辅助方法===================================

    /**
     * [初步判断提交的参数是否合法并设置为属性]
     */
    protected function firstCheckParams()
    {
        $this->memberid = intval($this->requst['pays_memberid']) - 10000;
		
        // 商户编号不能为空
        if (empty($this->memberid) || $this->memberid <= 0) {
            $this->showmessage("不存在的商户编号!");
        }

        $this->pays_amount = $this->requst['pays_amount'];
        if ($this->pays_amount == 0) {
            $this->showmessage('金额不能为空');
        }

        //银行编码
        $this->bankcode = $this->requst['pays_bankcode'];
        if ($this->bankcode == 0) {
            $this->showmessage('不存在的银行编码!', ['pays_banckcode' => $this->bankcode]);
        }

        $this->orderid = $this->requst['pays_orderid'];
        if (!$this->orderid) {
            $this->showmessage('订单号不合法！');
        }

    }

    /**
     * [用户风控]
     */
    protected function userRiskcontrol()
    {
        $l_UserRiskcontrol = new \Pay\Logic\UserRiskcontrolLogic($this->pays_amount, $this->memberid); //用户风控类
        $error_msg         = $l_UserRiskcontrol->monitoringData();
        if ($error_msg !== true) {
            $this->showmessage('商户：' . $error_msg);
        }
    }

    /**
     * [productIsOpen 判断通道是否开启，并分配]
     * @return [type] [description]
     */
    protected function productIsOpen()
    {
        $count = M('Product')->where(['id' => $this->bankcode, 'status' => 1])->count();
        //通道关闭
        if (!$count) {
            $this->showmessage('通道关闭中,暂时无法连接!');
        }
        $this->channel = M('ProductUser')->where(['pid' => $this->bankcode, 'userid' => $this->memberid, 'status' => 1])->find();
        //用户未分配
        if (!$this->channel) {
            $this->showmessage('用户未分配通道,暂时无法连接!');
        }
    }

    /**
     * [判断是否开启支付渠道 ，获取并设置支付通api的id---->轮询+风控]
     */
    protected function setChannelApiControl()
    {
        $l_ChannelRiskcontrol = new \Pay\Logic\ChannelRiskcontrolLogic($this->pays_amount); //支付渠道风控类
        $m_Channel            = M('Channel');

        if ($this->channel['polling'] == 1 && $this->channel['weight']) {

            /***********************多渠道,轮询，权重随机*********************/
            $weight_item  = [];
            $error_msg    = '该通道维护中，请稍后再试';
            $temp_weights = explode('|', $this->channel['weight']);
            foreach ($temp_weights as $k => $v) {

                list($pid, $weight) = explode(':', $v);
                //检查是否开通
                $temp_info = $m_Channel->where(['id' => $pid, 'status' => 1])->find();

                //判断通道是否开启风控并上线
                if ($temp_info['offline_status'] == 1 && $temp_info['control_status'] == 1) {

                    //-------------------------进行风控-----------------
                    $l_ChannelRiskcontrol->setConfigInfo($temp_info); //设置配置属性
                    $error_msg = $l_ChannelRiskcontrol->monitoringData();
                    if ($error_msg === true) {
                        $weight_item[] = ['pid' => $pid, 'weight' => $weight];

                    }

                } else if ($temp_info['control_status'] == 0) {
                    $weight_item[] = ['pid' => $pid, 'weight' => $weight];
                }

            }

            //如果所有通道风控，提示最后一个消息
            if ($weight_item == []) {
                $this->showmessage('通道:' . $error_msg);
            }
            $weight_item          = getWeight($weight_item);
            $this->channel['api'] = $weight_item['pid'];

        } else {
            /***********************单渠道,没有轮询*********************/

            //查询通道信息
            $pid          = $this->channel['channel'];
            $channel_info = $m_Channel->where(['id' => $pid])->find();

            //通道风控
            $l_ChannelRiskcontrol->setConfigInfo($channel_info); //设置配置属性
            $error_msg = $l_ChannelRiskcontrol->monitoringData();

            if ($error_msg !== true) {
                $this->showmessage('通道:' . $error_msg);
            }
            $this->channel['api'] = $pid;
        }
    }

    /**
     * 判断是否可以重复提交订单
     * @return [type] [description]
     */
    public function judgeRepeatOrder()
    {
        $is_repeat_order = M('Websiteconfig')->getField('is_repeat_order');
        if (!$is_repeat_order) {
            //不允许同一个用户提交重复订单
            $pays_memberid = $this->memberid + 10000;
            $count = M('Order')->where(['pays_memberid' => $pays_memberid, 'out_trade_id' => $this->orderid])->count();
            if($count){
                $this->showmessage('重复订单！请尝试重新提交订单');
            }
        }
    }

}
