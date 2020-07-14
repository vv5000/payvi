<?php
/**
 * Created by PhpStorm.
 * User: gaoxi
 * Date: 2017-04-02
 * Time: 23:01
 */

namespace Admin\Controller;

use Think\Page;

/**
 * 订单管理控制器
 * Class OrderController
 * @package Admin\Controller
 */
class HuidiaoController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

  
    public function index(){
        //银行
       
        $where    = array();
      
        $count = M('paylog')->where($where)->count();

        $size = 15;
        $rows = I('get.rows', $size, 'intval');
        if (!$rows) {
            $rows = $size;
        }

        $page = new Page($count, $rows);
        $list = M('paylog')->where($where)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->order('id desc')
            ->select();

        $this->assign('rows', $rows);
        $this->assign("list", $list);
        $this->assign('page', $page->show());
        C('TOKEN_ON', false);
        $this->display();
    }
}
