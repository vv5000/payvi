<?php
/*
 * 代付API
 */
namespace Payment\Controller;

use Think\Controller;
use Think\Page;

class DfpayController extends Controller
{
    //商家信息
    protected $merchants;
    //网站地址
    protected $_site;
    //通道信息
    protected $channel;

    public function __construct()
    {
        parent::__construct();
        $this->_site = ((is_https()) ? 'https' : 'http') . '://' . C("DOMAIN") . '/';
    }
    /**
     * 创建代付申请
     * @param $parameter
     * @return array
     */
    public function add()
    {
        if (empty($_POST)) {
            $this->showmessage('no data!');
        }
        $siteconfig = M("Websiteconfig")->find();
        if(!$siteconfig['df_api']) {
            $this->showmessage('代付API未开启！');
        }
        $sign = I('request.pays_md5sign');
        $paypassword = I('request.paypassword');
        if(!$sign) {
            $this->showmessage("缺少签名参数");
        }

        $mchid = I("post.mchid", 0);
        if(!$mchid) {
            $this->showmessage('商户ID不能为空！');
        }
        $user_id =  $mchid - 10000;

        $fans = M('Member')->where(['id'=>$user_id])->find();


        //用户信息
        $this->merchants = D('Member')->where(array('id'=>$user_id))->find();
        if(empty($this->merchants)) {
            $this->showmessage('商户不存在！');
        }
        if(!$this->merchants['df_api']) {
            $this->showmessage('商户未开启此功能！');
        }
        if($paypassword != $this->merchants['paypassword']){
             $this->showmessage('支付密码错误！');
        }
        if($this->merchants['df_domain'] != '') {
            $referer = getHttpReferer();
            if(!checkDfDomain($referer, $this->merchants['df_domain'])) {
                $this->showmessage('请求来源域名与报备域名不一致！');
            }
        }
        if($this->merchants['df_ip'] != '' && !checkDfIp($this->merchants['df_ip'])) {
            $this->showmessage('IP地址与报备IP不一致！');
        }
        //判断是否设置了节假日不能提现
        $tkHolidayList = M('Tikuanholiday')->limit(366)->getField('datetime', true);
        if ($tkHolidayList) {
            $today = date('Ymd');
            foreach ($tkHolidayList as $k => $v) {
                if ($today == date('Ymd', $v)) {
                    $this->showmessage('节假日暂时无法提款！');
                }
            }
        }
        //结算方式：
        $Tikuanconfig = M('Tikuanconfig');
        $tkConfig     = $Tikuanconfig->where(['userid' => $user_id, 'tkzt' => 1])->find();

        $defaultConfig = $Tikuanconfig->where(['issystem' => 1, 'tkzt' => 1])->find();

        //判断是否开启提款设置
        if (!$defaultConfig) {
            $this->showmessage('提款已关闭！');
        }

        //判断是否设置个人规则
        if (!$tkConfig || $tkConfig['tkzt'] != 1 || $tkConfig['systemxz'] != 1) {
            $tkConfig = $defaultConfig;
        } else {
            //个人规则，但是提现时间规则要按照系统规则
            $tkConfig['allowstart'] = $defaultConfig['allowstart'];
            $tkConfig['allowend']   = $defaultConfig['allowend'];
        }
        //是否在许可的提现时间
        $hour = date('H');
        //判断提现时间是否合法
        if ($tkConfig['allowend'] != 0) {
            if ($tkConfig['allowstart'] > $hour || $tkConfig['allowend'] <= $hour) {
                $this->showmessage('不在提现时间，请换个时间再来!');
            }
        }

        $money = I("post.money", 0);
        if($money<=0) {
            $this->showmessage('金额错误！');
        }
        //单笔最小提款金额
        if ($tkConfig['tkzxmoney'] > $money) {
            $this->showmessage('单笔最低提款额度：' . $tkConfig['tkzxmoney']);
        }
        //单笔最大提款金额
        if ($tkConfig['tkzdmoney'] < $money) {
            $this->showmessage('单笔最大提款额度：' . $tkConfig['tkzdmoney']);
        }
        $bankname = I("post.bankname", '', 'trim');
        if(!$bankname) {
            $this->showmessage('银行名称不能为空！');
        }
        $subbranch = I("post.subbranch", '', 'trim');
        if(!$subbranch) {
            $this->showmessage('支行名称不能为空');
        }
        $accountname = I("post.accountname", '', 'trim');
        if(!$accountname) {
            $this->showmessage('开户名不能为空！');
        }
        $cardnumber = I("post.cardnumber", '', 'trim');
        if(!$cardnumber) {
            $this->showmessage('银行卡号不能为空！');
        }
        $province = I("post.province", '', 'trim');
        if(!$province) {
            $this->showmessage('省份不能为空！');
        }
        $city = I("post.city", '', 'trim');
        if(!$city) {
            $this->showmessage('城市不能为空！');
        }
        $out_trade_no = I("post.out_trade_no", '', 'trim');
        if(!$out_trade_no) {
            $this->showmessage('订单号不能为空！');
        }
        $Order = M("df_api_order");
        $count = $Order->where(['out_trade_no'=>$out_trade_no, 'userid'=>$user_id])->count();
        if($count>0) {
            $this->showmessage('存在重复订单号！');
        }
        $notifyurl = I("post.notifyurl", '');
        $extends = I("post.extends", '');
        //当前可用代付渠道
        $channel_ids = M('pays_for_another')->where(['status' => 1])->getField('id', true);
        if($channel_ids) {
            //获取渠道扩展字段
            $fields = M('pays_channel_extend_fields')->where(['channel_id'=>['in',$channel_ids]])->select();
            if(!empty($fields)) {
                if(!$extends) {
                    $this->showmessage('扩展字段不能为空！');
                }
                $extend_fields_array = json_decode(base64_decode($extends), true);
                foreach($fields as $k => $v) {
                    if(!isset($extend_fields_array[$v['name']]) || $extend_fields_array[$v['name']]=='') {
                        $this->showmessage('扩展字段【'.$v['alias'].'】不能为空！');
                    }
                }
            }
        }
        //验签
        if ($this->verify($_POST)) {
            M()->startTrans();
            $data['userid']        = $user_id;
            $data['trade_no']      = $this->getOrderId();
            $data['out_trade_no']  = $out_trade_no;
            $data['money']         = $money;
            $data['bankname']      = $bankname;
            $data['subbranch']     = $subbranch;
            $data['accountname']   = $accountname;
            $data['cardnumber']    = $cardnumber;
            $data['province']      = $province;
            $data['city']          = $city;
            $data['ip']            = get_client_ip();
            $data['check_status']  = 0;
            $data['extends']       = base64_decode($extends);
            $data['notifyurl']     = $notifyurl;
            $data['create_time'] = time();
            //添加订单
            $res = $Order->add($data);
            if ($res) {
                if($fans['df_auto_check']) {
                    $result = $this->dfPass($data, $res);
                    if ($result['status'] == 0) {
                        M()->rollback();
                        $this->showmessage($result['msg']);
                    } else {
                        M()->commit();
                    }
                }else{
                    M()->commit();
                }
                header('Content-Type:application/json; charset=utf-8');
                $data = array('status' => 'success', 'msg' => '代付申请成功', 'transaction_id'=>$data['trade_no']);
                echo json_encode($data);
                exit;
            } else {
                $this->showmessage('系统错误');
            }
        } else {
            $this->showmessage('签名验证失败', $_POST);
        }
    }

    //代付查询
    public function query()
    {
        $out_trade_no = I('request.out_trade_no', '', 'trim');
        $sign = I('request.pays_md5sign');
        if(!$sign) {
            $this->showmessage("缺少签名参数");
        }
        if(!$out_trade_no){
            $this->showmessage("缺少订单号");
        }
        $mchid = I("request.mchid");
        if(!$mchid) {
            $this->showmessage("缺少商户号");
        }
        $user_id = $mchid - 10000;
        //用户信息
        $this->merchants = D('Member')->where(array('id'=>$user_id))->find();
        if(empty($this->merchants)) {
            $this->showmessage('商户不存在！');
        }
        if(!$this->merchants['df_api']) {
            $this->showmessage('商户未开启此功能！');
        }
        if($this->merchants['df_domain'] != '') {
            $referer = getHttpReferer();
            if(!checkDfDomain($referer, $this->merchants['df_domain'])) {
                $this->showmessage('请求来源域名与报备域名不一致！');
            }
        }
        if($this->merchants['df_ip'] != '' && !checkDfIp($this->merchants['df_ip'])) {
            $this->showmessage('IP地址与报备IP不一致！');
        }
        $request = [
            'mchid'=>$mchid,
            'out_trade_no'=>$out_trade_no
        ];

        $signature = $this->createSign($this->merchants['apikey'],$request);
        if($signature != $sign){
            $this->showmessage('验签失败!');
        }
        $order = M('df_api_order')->where(['out_trade_no'=>$out_trade_no,
            'userid'=>$user_id])->find();
        if(!$order){
			$return = [
				'status'=>'error',
				'msg'=>'请求成功',
				'refCode'=>'7',
				'refMsg'=>'交易不存在',
			];
			echo json_encode($return);exit;
        }elseif($order['check_status']==0){
            $refCode = '6';
            $refMsg = "待审核";
        }elseif($order['check_status']==2) {
            $refCode = '5';
            $refMsg = "审核驳回";

        }else{
            if($order['df_id'] > 0) {
                $df_order = M('wttklist')->where(['id'=>$order['df_id'], 'userid'=>$user_id])->find();
                if($df_order['status'] == 0) {
                    $refCode = '4';
                    $refMsg = "待处理";
                } elseif($df_order['status'] == 1) {
                    $refCode = '3';
                    $refMsg = "处理中";
                } elseif($df_order['status'] == 2) {
                    $refCode = '1';
                    $refMsg = "成功";
                } elseif($df_order['status'] == 3) {
                    $refCode = '2';
                    $refMsg = "失败";
                } elseif($df_order['status'] == 4) {
                    $refCode = '3';
                    $refMsg = "待确认";
                } else {
                    $refCode = '8';
                    $refMsg = "未知状态";
                }
            }
        }
        $return = [
            'status'=>'success',
            'msg'=>'请求成功',
            'mchid'=>$mchid,
            'out_trade_no'=>$order['out_trade_no'],
            'amount'=>$order['money'],
            'transaction_id'=>$order['trade_no'],
            'refCode'=>$refCode,
            'refMsg'=>$refMsg,
        ];
        if($refCode == 1) {
            $return['success_time'] = $df_order['cldatetime'];
        }
        $return['sign'] = $this->createSign($this->merchants['apikey'],$return);
        echo json_encode($return);
    }

    public function adminQuery()
    {

        $admin_id = I("request.admin_id");
        if(!$admin_id) {
            $this->showmessage("缺少管理员ID");
        }
        $status = I("request.status");

        //用户信息
        $this->merchants = D('admin')->where(array('id'=>$admin_id))->find();
        if(empty($this->merchants)) {
            $this->showmessage('管理员不存在！');
        }

        $where=['lxdf_uid'=>$admin_id,'status'=>$status];

        $count = M('Wttklist')->where($where)->count();
        $size  = 5000;
        $rows  = I('request.rows', $size, 'intval');
        if (!$rows) {
            $rows = $size;
        }
        $fields = 'id,userid,bankname,bankzhiname,banknumber,bankfullname,sheng,shi,sqdatetime,cldatetime,status,tkmoney,sxfmoney,money,memo,code,df_id,df_name,orderid,out_trade_no,df_api_id,lxdf_uid';
        $page = new Page($count, $rows);
        $list = M('Wttklist')
            ->field($fields)
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();

        if(!$list){
            $return = [
                'status'=>'error',
                'msg'=>'请求成功',
                'refCode'=>'7',
                'refMsg'=>'交易不存在',
            ];
            echo json_encode($return);exit;
        }else{

            $df_api_ids = array_column($list, 'df_api_id');
            $df_notify=M('df_api_order')->field('id,notifyurl')->where(['id'=>array('in',$df_api_ids)])->select();
            $df_notifyurl=array_column($df_notify,'notifyurl','id');
            $df_ipy=M('df_api_order')->field('id,ip')->where(['id'=>array('in',$df_api_ids)])->select();
            $df_ipys=array_column($df_ipy,'ip','id');
            foreach ($list as $k=>$v){
                $v['notifyurl']=$df_notifyurl[$v['df_api_id']];
                $v['df_ip']=$df_ipys[$v['df_api_id']];
                $list[$k] = $v;
            }
        }
        $return = [
            'status'=>'success',
            'msg'=>'请求成功',
            'data' => $list,
        ];
        echo json_encode($return);
    }

    public function adminDoStatus()
    {
        $id = I("request.id", 0, 'intval');
        $status  = I("request.status", 0, 'intval');
        $userid  = I('request.userid', 0, 'intval');
        $tkmoney = I('request.tkmoney');
        if (!$id) {
            $this->showmessage('操作失败,ID不能空');
        }
        if(!$status) {
            $this->showmessage("状态不能为空");
        }
        if (!$userid) {
            $this->showmessage('商户ID不能为空');
        }
        if(!$tkmoney) {
            $this->showmessage("提款金额不能为空");
        }

        //开启事物
        M()->startTrans();
        $Wttklist  = M("Wttklist");
        $map['id'] = $id;
        $withdraw  = $Wttklist->where($map)->lock(true)->find();
        if (empty($withdraw)) {
            $this->showmessage('提款申请不存在');
        }
        $data           = [];
        $data["status"] = $status;
        $wtStatus       = $Wttklist->where(['id' => $id])->getField('status');
        if ($wtStatus == 2 || $wtStatus == 3) {
            M()->rollback();
            $this->showmessage('错误操作,状态为2或是3');
        }

        //判断状态
        switch ($status) {
            case '2':
                $data["cldatetime"] = date("Y-m-d H:i:s");
                $apiid = $withdraw['df_api_id'];
                $useridd = $withdraw['userid'];
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
                    curl_setopt($curl, CURLOPT_TIMEOUT, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
                    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
                    $res = curl_exec($curl);
                    file_put_contents('0.txt','回调数据:'.$postData.',返回结果：'.$res.PHP_EOL, FILE_APPEND);
                    curl_close($curl);
                }
                break;
            case '3':

                // if($withdraw['status'] == 1){
                //     $this->ajaxReturn(['status' => 0, 'msg' => '提款申请处理中，不能驳回']);
                // } else
                if ($withdraw['status'] == 2) {
                    $this->showmessage('提款申请已打款，不能驳回');
                } elseif ($withdraw['status'] == 3) {
                    $this->showmessage('提款申请已驳回，不能驳回');
                }
                $map['_string'] = 'status=0 OR status=1 OR status=4';
                //驳回操作
                //1,将金额返回给商户
                $Member     = M('Member');
                $memberInfo = $Member->where(['id' => $userid])->lock(true)->find();
                $res        = $Member->where(['id' => $userid])->save(['balance' => array('exp', "balance+{$tkmoney}")]);

                if (!$res) {
                    M()->rollback();
                    $this->ajaxReturn(['status' => 0]);
                }

                //2,记录流水订单号
                $arrayField = array(
                    "userid"     => $userid,
                    "ymoney"     => $memberInfo['balance'],
                    "money"      => $tkmoney,
                    "gmoney"     => $memberInfo['balance'] + $tkmoney,
                    "datetime"   => date("Y-m-d H:i:s"),
                    "tongdao"    => 0,
                    "transid"    => $id,
                    "orderid"    => $id,
                    "lx"         => 12,
                    'contentstr' => '代付驳回',
                );
                $res = M('Moneychange')->add($arrayField);

                if (!$res) {
                    M()->rollback();
                    $this->ajaxReturn(['status' => 0]);
                }
                //代付驳回退回手续费
                if ($withdraw['df_charge_type']) {
                    $res = $Member->where(['id' => $withdraw['userid']])->save(['balance' => array('exp', "balance+{$withdraw['sxfmoney']}")]);
                    if (!$res) {
                        M()->rollback();
                        $this->showmessage('代付驳回');
                    }
                    $chargeField = array(
                        "userid"     => $withdraw['userid'],
                        "ymoney"     => $memberInfo['balance'] + $tkmoney,
                        "money"      => $withdraw['sxfmoney'],
                        "gmoney"     => $memberInfo['balance'] + $tkmoney + $withdraw['sxfmoney'],
                        "datetime"   => date("Y-m-d H:i:s"),
                        "tongdao"    => 0,
                        "transid"    => $id,
                        "orderid"    => $id,
                        "lx"         => 15,
                        'contentstr' => '代付结算驳回退回手续费',
                    );
                    $res = M('Moneychange')->add($chargeField);
                    if (!$res) {
                        M()->rollback();
                        $this->showmessage('代付驳回');
                    }
                }
                $data["cldatetime"] = date("Y-m-d H:i:s");
                $data["memo"]       = I('post.memo');
                break;
            default:
                # code...
                break;
        }



        $res = $Wttklist->where($map)->save($data);

        if ($res) {
            M()->commit();
            $return = [
                'status'=>'success',
                'msg'=>'请求成功',
                'data' => $res,
            ];
            echo json_encode($return);
        }else{
            M()->rollback();
            $this->showmessage('操作错误333');
        }

    }


    /**
     * 自动审核提交代付请求到后台
     *
     * @return string
     */
    public function dfPass($data, $df_api_id) {
        M()->startTrans();
        $Member = M('Member');
        $info   = $Member->where(['id' => $data['userid']])->lock(true)->find();

        //判断是否设置了节假日不能提现
        $tkHolidayList = M('Tikuanholiday')->limit(366)->getField('datetime', true);
        if ($tkHolidayList) {
            $today = date('Ymd');
            foreach ($tkHolidayList as $k => $v) {
                if ($today == date('Ymd', $v)) {
                    return ['status' => 0 ,'msg'=>'节假日暂时无法提款！'];
                }
            }
        }
        //结算方式：
        $Tikuanconfig = M('Tikuanconfig');
        $tkConfig     = $Tikuanconfig->where(['userid' => $data['userid'], 'tkzt' => 1])->find();

        $defaultConfig = $Tikuanconfig->where(['issystem' => 1, 'tkzt' => 1])->find();

        //判断是否开启提款设置
        if (!$defaultConfig) {
            return ['status' => 0 ,'msg'=>'提款已关闭！'];
        }

        //判断是否设置个人规则
        if (!$tkConfig || $tkConfig['tkzt'] != 1 || $tkConfig['systemxz'] != 1) {
            $tkConfig = $defaultConfig;
        } else {
            //个人规则，但是提现时间规则要按照系统规则
            $tkConfig['allowstart'] = $defaultConfig['allowstart'];
            $tkConfig['allowend']   = $defaultConfig['allowend'];
        }

        //判断是t1还是t0
        $t = $tkConfig['t1zt'] ? 1 : 0;

        //是否在许可的提现时间
        $hour = date('H');
        //判断提现时间是否合法
        if ($tkConfig['allowend'] != 0) {
            if ($tkConfig['allowstart'] > $hour || $tkConfig['allowend'] <= $hour) {
                return ['status' => 0 ,'msg'=>'不在提现时间，请换个时间再来!'];
            }
        }

        //单笔最小提款金额
        $tkzxmoney = $tkConfig['tkzxmoney'];
        //单笔最大提款金额
        $tkzdmoney = $tkConfig['tkzdmoney'];

        //查询代付表跟提现表的条件
        $map['userid']     = $data['userid'];
        $map['sqdatetime'] = ['between', [date('Y-m-d').' 00:00:00', date('Y-m-d').' 23:59:59']];

        //统计提现表的数据
        $Tklist = M('Tklist');
        $tkNum  = $Tklist->where($map)->count();
        $tkSum  = $Tklist->where($map)->sum('tkmoney');

        //统计代付表的数据
        $Wttklist = M('Wttklist');
        $wttkNum  = $Wttklist->where($map)->count();
        $wttkSum  = $Wttklist->where($map)->sum('tkmoney');

        //判断是否超过当天次数
        $dayzdnum = $tkNum + $wttkNum + 1;
        if ($dayzdnum >= $tkConfig['dayzdnum']) {
            $errorTxt = "超出商户当日提款次数！";
        }

        //判断提款额度
        $dayzdmoney = bcadd($wttkSum, $tkSum, 2);
        if ($dayzdmoney >= $tkConfig['dayzdmoney']) {
            $errorTxt = "超出商户当日提款额度！";
        }
        $balance = $info['balance'];
        if (!isset($errorTxt)) {
            if ($balance < $data['money']) {
                $errorTxt = '金额错误，可用余额不足!';
            }
            if ($data['money'] < $tkzxmoney || $data['money'] > $tkzdmoney) {
                $errorTxt = '提款金额不符合提款额度要求!';
            }
            $dayzdmoney = bcadd($data['money'], $dayzdmoney, 2);
            if ($dayzdmoney >= $tkConfig['dayzdmoney']) {
                $errorTxt = "超出当日提款额度！";
            }
            //计算手续费
            $sxfmoney = $tkConfig['tktype'] ? $tkConfig['sxffixed'] : bcdiv(bcmul($data['money'], $tkConfig['sxfrate'], 2), 100, 2);
            //额外的手续费
            $extra_sxmoney = getRank($data['money']);
            $sxfmoney += $extra_sxmoney;
            //增加额外的手续费

            if($tkConfig['tk_charge_type']) {
                //实际提现的金额
                $money = $data['money'];
            } else {
                //实际提现的金额
                $money = bcsub($data['money'], $sxfmoney, 2);
            }



            //获取订单号
            $orderid = $this->getOrderId();

            //提现时间
            $time = date("Y-m-d H:i:s");

            //提现记录
            $wttkData = [
                'orderid'      => $orderid,
                "bankname"     => trim($data["bankname"]),
                "bankzhiname"  => trim($data["subbranch"]),
                "banknumber"   => trim($data["cardnumber"]),
                "bankfullname" => trim($data['accountname']),
                "sheng"        => trim($data["province"]),
                "shi"          => trim($data["city"]),
                "userid"       => $data['userid'],
                "sqdatetime"   => $time,
                "status"       => 0,
                "t"            => $t,
                'tkmoney'      => $data['money'],
                'sxfmoney'     => $sxfmoney,
                "money"        => $money,
                "additional"   => '',
                "out_trade_no" => trim($data['out_trade_no']),
                "df_api_id"    => $df_api_id,
                "extends"      => trim($data['extends']),
                "df_charge_type" => $tkConfig['tk_charge_type'],
            ];


            //查找系统配置
            $Websiteconfig = D("Websiteconfig");
            $list= $Websiteconfig->find();
            $autodf = $list['autodf'];  //自动代付额度
            if($list['lxdf']){
                $lxdf_uids = json_decode($list['lxdf_uids'],true);  //解
                $lxdf_uid = getLxuid($lxdf_uids,$money,trim($data['out_trade_no']),$data['userid']);    //查找轮巡代付，返回ID
                $wttkData['lxdf_uid'] = intval($lxdf_uid);
            }
            //22000000000000000000000000000000


            $tkmoney = abs(floatval($data['money']));
            $ymoney  = $balance;
            $balance = bcsub($balance, $tkmoney, 2);
            $mcData = [
                "userid"     => $data['userid'],
                'ymoney'     => $ymoney,
                "money"      => $data['money'],
                'gmoney'     => $balance,
                "datetime"   => $time,
                "transid"    => $orderid,
                "orderid"    => $orderid,
                "lx"         => 6,
                'contentstr' => date("Y-m-d H:i:s") . '委托提现操作',
            ];
            if($tkConfig['tk_charge_type']) {
                $balance = bcsub($balance, $sxfmoney, 2);
                $chargeData = [
                    "userid"     => $data['userid'],
                    'ymoney'     => $ymoney-$data['money'],
                    "money"      => $sxfmoney,
                    'gmoney'     => $balance,
                    "datetime"   => $time,
                    "transid"    => $orderid,
                    "orderid"    => $orderid,
                    "lx"         => 14,
                    'contentstr' => date("Y-m-d H:i:s") . '委托提现扣除手续费',
                ];
            }
        }
        if (!isset($errorTxt)) {
            $res1 = $Member->where(['id' => $data['userid']])->save(['balance' => $balance]);
            $res2 = $Wttklist->add($wttkData);
            $res3 = M("df_api_order")->where(['check_status'=>0,'userid'=>$data['userid'],'id'=> $df_api_id])->save(['df_id'=>$res2, 'check_status'=>1,'check_time'=>time()]);
            $res4 = M('Moneychange')->add($mcData);
            if($tkConfig['tk_charge_type']) {
                $res5 = M('Moneychange')->add($chargeData);
            } else {
                $res5 = true;
            }
            if ($res1 && $res2 && $res3 && $res4 && $res5) {
                M()->commit();
                 if($data['money']<$autodf) { //进入自动代付程序
                     $_REQUEST = [
                         'code' => 'default',
                         'id'   => intval($res2),   //wttk的唯一ID
                         'opt'  => 'exec',
                     ];
                     R('Payment/Index/indexauto');
                     /*$result= R('Payment/Index/indexauto');
                     if($result['status']!=1){  //自动失败
                         M()->rollback();
                         return (['status' => 0, 'msg' => '提交失败'.$result['msg']]);
                     }*/
                 }
                return ['status' => 1,'msg'=>'提交成功'];
            }
            M()->rollback();
            return (['status' => 0, 'msg' => '提交失败']);
        } else {
            return ['status' => 0, 'msg' => $errorTxt];
        }


    }

    /**
     * 获得订单号
     *
     * @return string
     */
    public function getOrderId()
    {
        $year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $i         = intval(date('Y')) - 2010 - 1;

        return $year_code[$i] . date('md') .
            substr(time(), -5) . substr(microtime(), 2, 5) . str_pad(mt_rand(1, 99), 2, '0', STR_PAD_LEFT);
    }


    /**
     *  验证签名
     * @return bool
     */
    protected function verify($param)
    {
        $md5key        = $this->merchants['apikey'];
        $md5keysignstr = $this->createSign($md5key, $param);
        $pays_md5sign   = I('request.pays_md5sign');
        if ($pays_md5sign == $md5keysignstr) {
            return true;
        } else {
            return false;
        }
    }



    /**
     * 创建签名
     * @param $Md5key
     * @param $list
     * @return string
     */
    protected function createSign($Md5key, $list)
    {
        ksort($list);
        $md5str = "";
        foreach ($list as $key => $val) {
            if (!empty($val) && $key != 'pays_md5sign') {
                $md5str = $md5str . $key . "=" . $val . "&";
            }
        }
      //  file_put_contents('sign.txt',$md5str."key=" . $Md5key.PHP_EOL,FILE_APPEND);
        $sign = strtoupper(md5($md5str . "key=" . $Md5key));
        return $sign;
    }

    /**
     * 错误返回
     * @param string $msg
     * @param array $fields
     */
    public function showmessage($msg = '', $fields = array())
    {
        header('Content-Type:application/json; charset=utf-8');
        $data = array('status' => 'error', 'msg' => $msg, 'data' => $fields);
        echo json_encode($data, 320);
        exit;
    }
}