<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-04-02
 * Time: 23:01
 */

namespace Admin\Controller;

use Org\Util\Str;
use Pay\Model\ComplaintsDepositModel;
use Think\Page;

class BanknumbersController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        //通道
        $channels = M('Channel')
            ->where(['status' => 1])
            ->field('id,code,title,paytype,status')
            ->select();
        $this->assign('channels', json_encode($channels));
        $this->assign('channellist', $channels);
    }

    /**
     * 银行卡管理
     */
    public function index()
    {


        $uid               = session('admin_auth')['uid'];
        $user = M('admin')->where(['id'=>$uid])->find();

        $admin_model = M('Admin');
        if($user['groupid']>1){
            $wherea = ['id' => $uid];
        }else{
            $wherea = [];
        }
        $adata = $admin_model->where($wherea)->select();

        $admin_list = array_column($adata, 'username', 'id');

        $this->assign("admlist", $adata);
        $username    = I("get.username", '', 'trim');
        $adminid    = I("get.adminid", '', 'trim');


        if (!empty($username) && !is_numeric($username)) {
            $where['bank_name'] = ['like', "%" . $username . "%"];
        }elseif (intval($username) - 10000 > 0) {
            $where['mid'] = intval($username) - 10000;
        }

        if (!empty($adminid)) {
            $where['adminid'] = intval($adminid);
        }



        $count = M('banknumbers')->where($where)->count();
        $size  = 15;
        $rows  = I('get.rows', $size);
        if (!$rows) {
            $rows = $size;
        }

        $page = new Page($count, $rows);
        $list = M('banknumbers')
            ->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();
        foreach ($list as $k=>$v){
            $v['admin_name'] = $admin_list[$v['adminid']];
            $list[$k]=$v;
        }
        $this->assign('rows', $rows);
        $this->assign("list", $list);
        $this->assign("count", $count);
        $this->assign('page', $page->show());
        //取消令牌
        C('TOKEN_ON', false);
        $this->display();
    }


    public function batchset()
    {
        $ids = I('request.ids');
        if (!$ids) {
            $this->ajaxReturn(['status' => 0, 'msg' => "请选择！"]);
        }
        if (IS_POST) {
            $ids  = I("post.ids");
            $adminid  = I("post.adminid");
            $ids = explode(',', $ids);
            foreach ($ids as $id) {
                if (!$id) {
                    continue;   //如果ID为空直接跳入下一个！
                }
                M("Banknumbers")->where(['id' => $id])->save(['adminid'=>$adminid]);
            }
            $this->ajaxReturn(['status' => 1]);
        }
        $admin_model = D('Admin');
        $data = $admin_model->getAdminList();
        $admlist=$data['list'];
        $this->assign('admlist', $admlist);
        $this->display();
    }

//编辑
    public function bankcard()
    {
        $id = I('get.id', 0, 'intval');
        if ($id) {
            $data = M('Banknumbers')
                ->where(['id' => $id])->find();
            $this->assign('u', $data);
        }

        $admin_model = D('Admin');
        $data = $admin_model->getAdminList();
        $admlist=$data['list'];
        $this->assign('admlist', $admlist);

        $this->display();
    }


    public function del()
    {
        if (IS_POST) {
            $id  = I('post.id', 0, 'intval');
            $res = M('Banknumbers')->where(['id' => $id])->delete();
            $this->ajaxReturn(['status' => $res]);
        }
    }

    public function editbankcard()
    {
        if (IS_POST) {
            $id   = I('post.id');
            $rows = [
                'adminid'     => I('post.adminid', '', 'trim'),
                'bank_title'  => I('post.bank_title', '', 'trim'),
                'bank_number'   => I('post.bank_number', '', 'trim'),
                'bank_name' => I('post.bank_name', '', 'trim'),
                'update_time'   => date("Y-m-d H:i:s"),
            ];
            if ($id) {
                $returnstr = M("Banknumbers")->where(['id' => $id])->save($rows);
            }else{
                $returnstr = M("Banknumbers")->add($rows);
            }

            if ($returnstr) {
                $this->ajaxReturn(['status' => 1]);
            } else {
                $this->ajaxReturn(['status' => 0]);
            }
        }
    }



}
