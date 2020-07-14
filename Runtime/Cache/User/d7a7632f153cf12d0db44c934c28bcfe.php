<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="renderer" content="webkit">
<title><?php echo ($sitename); ?> - <?php if(($fans[groupid]) == "5"): ?>代理中心<?php endif; ?> 
   <?php if(($fans[groupid]) == "6"): ?>中级代理中心<?php endif; ?> 
   <?php if(($fans[groupid]) == "7"): ?>高级代理中心<?php endif; ?> 
                       <?php if(($fans[groupid]) == "4"): ?>商户中心<?php endif; ?> </title>
  <!--[if lt IE 9]>
  <meta http-equiv="refresh" content="0;ie.html" />
  <![endif]-->
  <link rel="shortcut icon" href="favicon.ico">
  <link href="/Public/Front/css/bootstrap.min.css" rel="stylesheet">
  <link href="/Public/Front/css/font-awesome.min.css" rel="stylesheet">
  <link href="/Public/Front/css/animate.css" rel="stylesheet">
  <link href="/Public/Front/css/style.css" rel="stylesheet">
  <link href="/Public/Front/css/zuy.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="/Public/Front/iconfont/iconfont.css"/>
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">

<div class=" zuy-header">
  <nav class="navbar navbar-static-top" role="navigation" >
    <!--<div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>

    </div>-->
    <ul class="nav navbar-left">
      <li class="nav-header zuy-user">
        <div class="dropdown profile-element">

          <span><i class="iconfont icon-mine_fill"></i></span>
          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                <span >
                    <span class=" m-t-xs">
                       <?php if(($fans[groupid]) == "5"): ?><font>代理中心</font><?php endif; ?> 
                      <?php if(($fans[groupid]) == "6"): ?><font>中级代理中心</font><?php endif; ?> 
                      <?php if(($fans[groupid]) == "7"): ?><font>高级代理中心</font><?php endif; ?> 
                       <?php if(($fans[groupid]) == "4"): ?><font>商户中心</font><?php endif; ?> 
                    </span>
                </span>
          </a>
        </div>
        <div class="logo-element">MENU</div>
      </li>
    </ul>
    <ul class="nav navbar-top-links navbar-right">
      <li>  <a href="<?php echo U('/');?>" target="_blank"> <i class="fa fa-home"></i> 首页 </a></li>
      <!--<li class="hidden-xs edtpwd">
        <a href="javascript:;" onClick="reset_pwd('修改密码','<?php echo U('System/editPassword');?>',360,320)"><i class="iconfont icon-mima"></i>修改密码</a>
      </li> -->
      <li class="dropdown hidden-xs"> <a  href="<?php echo U("Login/loginout");?>" class="right-sidebar-toggle"
                                          aria-expanded="false"> <i class="fa fa-sign-out"></i> 退出</a> </li>
    </ul>
  </nav>
</div>
<div id="wrapper">
  <!--左侧导航开始-->
  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i></div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
           
            <li>
               <a href="<?php echo U('Index/index');?>"> <i class="fa fa-home"></i>
                <span class="nav-label">管理中心</span></a>
                    

            </li>

         <?php if($fans[groupid] > 4): ?><li><a href="#"> <i class="fa fa-dollar"></i> <span
                    class="nav-label">我要收款</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                   <!-- <li><a href="<?php echo U("Account/qrcode");?>" class="J_menuItem"><span class="nav-label">收款二维码</span></a></a> </li> -->

                    <li><a href="<?php echo U('Account/link');?>" class="J_menuItem"> <span class="nav-label">收款链接</span> </a></li>

                </ul>
            </li><?php endif; ?>
          
            <?php if($fans[groupid] == 4 and $fans[open_charge] == 1): ?><li><a href="#"> <i class="fa fa-user-secret"></i>
                  <span class="nav-label">我要收款</span> <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                       <!--<li><a href="<?php echo U("Account/qrcode");?>" class="J_menuItem"><strong>收款二维码</strong></a></a> </li> -->
                        <li><a href="<?php echo U('Account/link');?>" class="J_menuItem"> <span class="nav-label">收款链接</span> </a></li>

                    </ul>
                </li><?php endif; ?>
  
            <li><a href="#"> <i class="fa fa-volume-up"></i> 
              <span  class="nav-label">平台公告</span> <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U("Index/gonggao");?>" class="J_menuItem"><span class="nav-label">站内公告</span></a></li>
                </ul>
            </li>
  
            <li><a href="#"> <i class="fa fa fa-user"></i> 
              <span class="nav-label">账户管理</span> <span class="fa arrow"></span> </a>
            
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U("Account/profile");?>" class="J_menuItem"><span class="nav-label">基本信息</span></a> </li>
                    <li><a href="<?php echo U("Account/bankcard");?>" class="J_menuItem"><span class="nav-label">银行卡管理</span></a> </li>
                    <li><a href="<?php echo U("Account/authorized");?>" class="J_menuItem"><span class="nav-label">认证信息</span></a> </li>
                    <li><a href="<?php echo U("Account/editPassword");?>" class="J_menuItem"><span class="nav-label">登录密码</span></a> </li>
                    <li><a href="<?php echo U("Account/editPaypassword");?>" class="J_menuItem"><span class="nav-label">支付密码</span></a> </li>
                    <li><a href="<?php echo U("Account/loginrecord");?>" class="J_menuItem"><span class="nav-label">登录记录</span></a> </li>
                    <li><a href="<?php echo U("Account/google");?>" class="J_menuItem"><span class="nav-label">Google身份验证</span></a> </li>
                </ul>
            </li>
  
            <li><a href="#"> <i class="fa fa-money"></i> <span class="nav-label">财务管理</span> <span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U("Account/changeRecord");?>" class="J_menuItem"><span class="nav-label">资金记录</span></a> </li>
                    <li><a href="<?php echo U("Account/channelFinance");?>" class="J_menuItem"><span class="nav-label">通道分析</span></a> </li>
                    <li><a href="<?php echo U("Account/complaintsDeposit");?>" class="J_menuItem"><span class="nav-label">证金明细</span></a> </li>
                    <li><a href="<?php echo U("Account/frozenMoney");?>" class="J_menuItem"><span class="nav-label">冻结资金明细</span></a> </li>
                    <?php if(($fans[groupid]) == "4"): ?><li><a href="<?php echo U("Account/reconciliation");?>" class="J_menuItem"><span class="nav-label">商户对账单</span></a> </li><?php endif; ?>
                   <?php if(($fans[groupid]) == "5"): ?><li><a href="javascript:alert('功能开发中' + '\n' + '还未完善');" class="J_menuItem"><span class="nav-label">代理对账单</span></a> </li><?php endif; ?>
                </ul>
            </li>
  
            <li><a href="#"> <i class="fa fa fa-check"></i> <span class="nav-label">结算管理</span> <span
                    class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U("Withdrawal/clearing");?>" class="J_menuItem"><span class="nav-label">结算申请</span></a>     </li>
                    <?php if($siteconfig['payingservice']): ?><li><a href="<?php echo U("Withdrawal/dfapply");?>" class="J_menuItem"><span class="nav-label">代付申请</span></a>     </li><?php endif; ?>
                    <li><a href="<?php echo U("Withdrawal/index");?>" class="J_menuItem"><span class="nav-label">结算记录</span></a>     </li>
                    <li><a href="<?php echo U("Withdrawal/payment");?>" class="J_menuItem"><span class="nav-label">代付记录</span></a>     </li>
                    <li><a href="<?php echo U("agent_Withdrawal/batchdf");?>" class="J_menuItem"><span class="nav-label">批量代付</span></a>     </li>
                    <?php if($siteconfig['df_api'] and $fans[df_api]): ?><li><a href="<?php echo U("Withdrawal/check");?>" class="J_menuItem"><span class="nav-label">商户代付管理</span></a>     </li><?php endif; ?>
                </ul>
            </li>
            <?php if(($fans[groupid]) != "4"): ?><li><a href="#"> <i class="fa fa fa-gears"></i> <span class="nav-label">订单管理</span> <span
                        class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <li><a href="<?php echo U("Agent/order");?>" class="J_menuItem"><span class="nav-label">所有订单</strong></a></li>
                    </ul>
                </li><?php endif; ?>
            <?php if(($fans[groupid]) == "4"): ?><li><a href="#"> <i class="fa fa fa-sellsy"></i> <span class="nav-label">订单管理</span> <span
                    class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U("Order/index");?>" class="J_menuItem"><span class="nav-label">所有订单</span></a>
                    </li>
                    <li><a href="<?php echo U("Order/index",['status'=>2]);?>" class="J_menuItem"><span class="nav-label">成功订单</span></a>
                    </li>
                    <li><a href="<?php echo U("Order/index",['status'=>1]);?>" class="J_menuItem"><span class="nav-label">手工补发</span></a>
                    </li>
                    <li><a href="<?php echo U("Order/index",['status'=>0]);?>" class="J_menuItem"><span class="nav-label">未支付订单</span></a>
                    </li>
                </ul>
            </li><?php endif; ?>
         
             <?php if(($fans[groupid]) != "4"): ?><li><a href="#"> <i class="fa fa fa-gears"></i> <span class="nav-label">代理管理</span> <span
                        class="fa arrow"></span> </a>
                    <ul class="nav nav-second-level">
                        <!--<?php if($siteconfig['invitecode']): ?><li><a href="<?php echo U("Agent/invitecode");?>" class="J_menuItem"><span class="nav-label">注册邀请码</span></a> </li><?php endif; ?>-->
                         <li><a href="<?php echo U("Agent/member");?>" class="J_menuItem"><span class="nav-label">下级商户管理</span> </a> </li>
                        <!--<li><a href="javascript:alert('正在开发完善中...');" class="J_menuItem" style="display:none;"><span class="nav-label">成记录</span> </a>
                        </li>-->
                    </ul>
                </li><?php endif; ?>
  

            <li><a href="#"> <i class="fa fa fa-bank"></i> <span class="nav-label">API管理</span> <span class="fa arrow"></span> </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U("Channel/index");?>" class="J_menuItem"><span class="nav-label">查看通道费率</span> </a>  </li>
                    <?php if(($fans[groupid]) == "4"): ?><li><a href="<?php echo U("Channel/apidocumnet");?>" class="J_menuItem"><span class="nav-label">API开发文档</span> </a>  </li><?php endif; ?>
                </ul>
            </li>

  

        </ul>
    </div>
</nav>
    
  <!--左侧导航结束-->
  <!--右侧部分开始-->
  <div id="page-wrapper" class="gray-bg dashbard-1">
    <div class="row J_mainContent" id="content-main">
      <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?php echo U('Index/main');?>"
              frameborder="0" data-id="<?php echo U('Index/main');?>" seamless></iframe>
    </div>
 <!-- <div class="layui-footer">版本：<?php echo C('SOFT_VERSION');?>
    </div>-->
 <div class="footer">
      <div class="pull-right">&copy;2018 <?php echo ($sitename); ?> 版权所有</div>
    </div>

  </div>
  <!--右侧部分结束-->
</div>
<!-- 全局js -->
</div>
<script src="/Public/Front/js/jquery.min.js"></script>
<script src="/Public/Front/js/bootstrap.min.js"></script>
<script src="/Public/Front/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/Public/Front/js/content.js"></script>
<script src="/Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/x-layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/Util.js" charset="utf-8"></script>
<script src="/Public/Front/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/Public/Front/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/Public/Front/js/hplus.js"></script>
<script type="text/javascript" src="/Public/Front/js/contabs.js"></script>
<script src="/Public/Front/js/iNotify.js"></script>
<script>
    layui.use(['laypage', 'layer', 'form'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;
    });
    function reset_pwd(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
</script>
  <script>


//side
$(function(){
    	 $('.logo-element').click(function(){
    	 	 if($('.navbar-static-side').hasClass('show')){
    	 	 	$('.navbar-static-side').removeClass('show');
    	 	 }
    	 	 else{
    	 	 	$('.navbar-static-side').addClass('show');
    	 	 }
    	 })
		 
		 
		 $('.navbar-static-side li>ul a').click(function(){
		 	$('.navbar-static-side').removeClass('show');
		 })
    })
</script>
</body>
</html>