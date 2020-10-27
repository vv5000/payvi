<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-08-13
 * Time: 14:02
 */
namespace Admin\Controller;
use Think\Verify;

class AuthController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->typearray=array(
            '3' => 'zfb',
            '4' => 'zfb',
            '1' => 'wx',
            '2' => 'wx',
            '7' => 'yl',
            '8' => 'yl',
            '9' => 'yl',
            '10' => 'yl',
            '11' => 'yl',
            '12' => 'yl',
            '13' => 'yl',
            '14' => 'yl'
        );
    }

    //列表
    public function index()
    {
        $admin_auth_group_model = D('AdminAuthGroup');
        $data = $admin_auth_group_model->getGroupList();
        $this->assign('list', $data['list']);
        $this->assign('page', $data['page']);
        $this->display();
    }

    /**
     * 添加角色页面显示
     */
    public function addGroup()
    {
        if(IS_POST){
            $is_manager = I('post.is_manager') == 'on' ? 1 :0;
            $params = array(
                'title' => I('title','','trim'),
                'is_manager'=>$is_manager,
                'status' => 1,
                'rules' => '',
            );

            if(!$params['title']){
               $this->ajaxReturn(['status'=>0,'msg'=>'请输入角色名称!']);
            }
            $admin_auth_group_model = D('AdminAuthGroup');
            $add_group_result = $admin_auth_group_model->add($params);
            $this->ajaxReturn(['status'=>$add_group_result]);
        }else{
            $this->display();
        }

    }

    public function paofenbudan(){
        $merReqNo = I('merReqNo','','trim');
        $where=array('pays_orderid' => $merReqNo);
        $order_info = M('Order')
            ->alias('as od')
            ->field("od.*,oc.paytype,oc.gateway")
            ->join('pays_channel as oc on od.channel_id=oc.id')
            ->where($where) ->find();


        $notifyurl   = $this->_site . 'Pay_Cqwap_notifyurl.html'; //异步通知
        $callbackurl = $this->_site . 'Pay_Cqwap_callbackurl.html'; //返回通知

        $data = array(
            "shid" =>$order_info['memberid'],   //商户ID
            "key" => $order_info['key'],
            "orderid" => $order_info['pays_orderid'],
            "amount" => sprintf('%.2f', $order_info['pays_amount']),
            "pay" => $this->typearray[$order_info['paytype']],
            "notify_url" => $notifyurl,
            "return_url" => $callbackurl
        );
//
        $gateway = $this->getTopHost($order_info['gateway']) . "budan.php";
        $result = $this->httpPost($gateway, $data);
        echo $result;
        die();
    }

    public function getTopHost($url){
        $search = '~^(([^:/?#]+):)?(//([^/?#]*))?([^?#]*)(\?([^#]*))?(#(.*))?~i';
        preg_match_all($search, $url ,$rr);
        $gateway = $rr[1][0] . $rr[3][0] . "/";
        return $gateway;
    }



    public static function httpPost($url,$sendArray){
        $ch = curl_init();
        $curl_url = $url;

        curl_setopt($ch, CURLOPT_URL, $curl_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendArray);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        return $curl_result;
    }

    /**
     * 编辑角色页面显示
     */
    public function editGroup()
    {
        if(IS_POST){
            $params = array(
                'id' => I('id', 0, 'intval'),
                'title' => I('title'),
            );
            if(!$params['id']){
                $this->ajaxReturn(['status'=>'error','msg'=>'角色不存在!']);
            }
            if(!$params['title']){
                parent::ajaxError('请输入角色名称!');
            }
            /* @var $admin_auth_group_model \Admin\Model\AdminAuthGroupModel */
            $admin_auth_group_model = D('AdminAuthGroup');
            $save_group_result = $admin_auth_group_model->save($params);
            $this->ajaxReturn(['status'=>$save_group_result,'msg'=>'修改成功!']);
        }else{
            $id = I('id', 0, 'intval');
            /* @var $admin_auth_group_model \Admin\Model\AdminAuthGroupModel */
            $admin_auth_group_model = D('AdminAuthGroup');
            $group_info = $admin_auth_group_model->findGroup($id);

            $this->assign('group_info', $group_info);
            $this->display();
        }

    }

    /**
     * 删除角色处理
     */
    public function deleteGroup()
    {
        $id = I('id', 0, 'intval');
        if(!$id){
            parent::ajaxError('角色不存在!');
        }
        /* @var $admin_auth_group_model \Admin\Model\AdminAuthGroupModel */
        $admin_auth_group_model = D('AdminAuthGroup');
        $group_info = $admin_auth_group_model->findGroup($id);
        if(!$group_info){
            $this->ajaxReturn(['status'=>0,'msg'=>'角色不存在!']);
        }
        $change_result =  $admin_auth_group_model->where(array('id'=>$id))->delete();
        $this->ajaxReturn(['status'=>$change_result]);
    }

    /**
     * 分配角色
     */
    public function giveRole()
    {
        if(IS_POST){
            $user_id = I('user_id', 0, 'intval');
            if(!$user_id){
                parent::ajaxError('用户不存在!');
            }

            $group_id = I('post.group_id');
            //html_entity_decode($string)
            /* @var $admin_auth_group_model \Admin\Model\AdminAuthGroupModel */
            $admin_auth_group_access_model = D('AdminAuthGroupAccess');

            if(!empty($group_id)){
                //删除原有角色
                $admin_auth_group_access_model->where(array('uid'=>$user_id))->delete();
                foreach($group_id as $v){
                    $add_data = array(
                        'uid' => $user_id,
                        'group_id' => $v,
                    );
                    $admin_auth_group_access_model->add($add_data);
                }
            }
            parent::ajaxSuccess('分配成功!');
        }else{
            $user_id = I('user_id', 0, 'intval');

            /* @var $admin_auth_group_model \Admin\Model\AdminAuthGroupModel */
            $admin_auth_group_model = D('AdminAuthGroup');
            $data = $admin_auth_group_model->getGroupList($user_id);

            $this->assign('list', $data['list']);
            $this->assign('user_id', $user_id);
            $this->display();
        }
    }

    /**
     * 分配权限
     */
    public function ruleGroup()
    {
        /* @var $admin_auth_group_model \Admin\Model\AdminAuthGroupModel */
        $admin_auth_group_model = D('AdminAuthGroup');
        if(IS_POST){
            $data = I('post.');
            $rule_ids = implode(",", $data['menu']);
            $role_id = $data['roleid'];
            if(!count($rule_ids)){
                $this->ajaxReturn(['status'=>0,'msg'=>'请选择需要分配的权限']);
            }
            if($admin_auth_group_model->addAuthRule($rule_ids, $role_id) !== false){
                $this->ajaxReturn(['status'=>1,'msg'=>'分配成功']);
            }else{
                $this->ajaxReturn(['status'=>0,'msg'=>'分配失败，请检查']);
            }
        }else{

            $role_id = I('get.roleid',0,'intval');
            /* @var $menu_model \Admin\Model\AdminMenuModel */
            $menu_model = D('AdminMenu');

            $menus = get_column($menu_model->selectAllMenu(2),2);
            $role_info = $admin_auth_group_model->findGroup($role_id);

            if($role_info['rules']){
                $rulesArr = explode(',',$role_info['rules']);

                $this->assign('rulesArr',$rulesArr);
            }
            $this->assign('menus',$menus);
            $this->assign('role_id',$role_id);
            $this->display();
        }
    }

    /**
     * 谷歌令牌验证
     */
    public function google()
    {
        $siteconfig = M("Websiteconfig")->find();
        /*
        if($siteconfig['google_auth'] == 0) {
            $this->error('系统未开启谷歌身份验证');
        }
        */
        if (!session('admin_auth')) {
            $this->error('未登录','/' . C("LOGINNAME"));
        }
        $google_auth = session('google_auth');
        /*
        if($google_auth) {
            $this->redirect('/' . C("LOGINNAME"));
        }
        */


        $ga = new \Org\Util\GoogleAuthenticator();
        $google_token = M('admin')->where(['id'=>session('admin_auth')['uid']])->getField('google_secret_key');
        if (!IS_POST) {
            if($google_token == '') {
                $secret = session('google_secret_key') ? session('google_secret_key') : $ga->createSecret();
                $qrCodeUrl = $ga->getQRCodeGoogleUrl($_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["HTTP_HOST"].'@'.session('admin_auth')['username'], $secret);
             //   echo $qrCodeUrl;
                session('google_secret_key', $secret);
                $this->assign('secret', $secret);
                $this->assign('qrCodeUrl', $qrCodeUrl);
            }
            $this->assign('action_type', $google_token == '' ? 0 : 1);
            $this->assign('google_token', $google_token);
            $this->display();
        } else {
            $action_type = I('action_type', 'intval', 0);
            $code = I('googlecode');
            if($code == '') {
                $this->error("请输入验证码");
            }
            if($action_type == 0) {//首次绑定
                $google_secret_key = session('google_secret_key');
                if(!$google_secret_key) {
                    $this->error("绑定失败，请刷新页面重试");
                }
                $oneCode = $ga->getCode($google_secret_key);
                if($code !== $oneCode) {
                    $this->error("验证码错误");
                } else {
                    $re = M('admin')->where(array('id'=>session('admin_auth')['uid'],'google_secret_key'=>array('eq','')))->save(['google_secret_key'=>$google_secret_key]);
                    if(FALSE !== $re) {
                        session('google_auth', $oneCode);
                        session('google_secret_key', null);
                     //   $this->success("绑定成功", U('Index/index'));
                        $this->ajaxReturn(['status' => 1, 'msg' => '绑定成功!', 'url' => U('Index/index')]);
                    } else {
                       // $this->error("绑定失败，请售后重试");
                        $this->ajaxReturn(['status' => 0, 'msg' => '绑定失败!', 'url' => U('Index/index')]);
                    }
                }
            } else {
                $google_secret_key = M('admin')->where(array('id'=> session('admin_auth')['uid']))->getField('google_secret_key');
                if($google_secret_key == '') {
                    $this->error("您未绑定谷歌身份验证器");
                }
                $captcha = I('captcha_code');
                $verify        = new Verify();
                //验证码校验
                if (!$verify->check($captcha)) {
                    $this->error('图形验证码错误!');
                }
                $oneCode = $ga->getCode($google_secret_key);
                if($code != $oneCode) {
                    $this->error("身份验证码错误");
                } else {
                    session('google_auth', $oneCode);
                    $this->success("验证通过，正在进入系统...", U('Index/index'));
                }
            }

        }
    }

    /**
     * 解绑谷歌身份验证
     */
    public function unbindGoogle()
    {
        if(IS_POST) {
            //验证短信验证码
            $code        = I('request.code');
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                if (session('send.unbindGoogle') == $code && $this->checkSessionTime('unbindGoogle', $code)) {
                    session('send', null);
                } else {
                    $this->ajaxReturn(['status' => 0, 'msg' => '短信验证码错误']);
                }
            }
            $re = M('Admin')->where(array('id'=>session('admin_auth')['uid']))->save(['google_secret_key'=>'']);
            if(FALSE !== $re) {
                session('google_auth', null);
                $this->ajaxReturn(["status"=>1, "msg"=>"解绑成功"]);
            } else {
                $this->ajaxReturn(["status"=>0, "msg"=>"解绑失败，请稍后重试"]);
            }
        } else {
            //查询是否开启短信验证
            $sms_is_open = smsStatus();
            if ($sms_is_open) {
                $this->assign('sendUrl', U('Auth/unbindGoogleSend'));
            }
            $user = M('Admin')->where(array('id'=>session('admin_auth')['uid']))->find();
            $this->assign('mobile', $user['mobile']);
            $this->assign('sms_is_open', $sms_is_open);
            $this->display();
        }
    }

    /**
     * 解绑谷歌身份验证器验证码
     */
    public function unbindGoogleSend()
    {
        $uid = session('admin_auth')['uid'];
        $user = M('admin')->where(['id'=>$uid])->find();
        $mobile = $user['mobile'];
        if (!$mobile) {
            $this->ajaxReturn(['status' => 0, 'msg' => '您未绑定手机号码！']);
        }
        $res = $this->send('unbindGoogle', $mobile, '解绑谷歌身份验证器');
        $this->ajaxReturn(['status' => $res['code']]);
    }

}