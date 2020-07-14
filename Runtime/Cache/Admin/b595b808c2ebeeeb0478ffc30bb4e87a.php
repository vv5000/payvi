<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="renderer" content="webkit">
  <title>后台管理 - <?php echo ($sitename); ?></title>
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
                       <font><?php echo ($member["username"]); ?></font>
                    </span>
                </span>
          </a>
        </div>
        <div class="logo-element">MENU</div>
      </li>
    </ul>
	-后台系统
    <ul class="nav navbar-top-links navbar-right">
      <li>  <a href="<?php echo U('/');?>" target="_blank"> <i class="fa fa-home"></i> 前台 </a></li>
      <li class="hidden-xs edtpwd">
        <a href="javascript:;" onClick="reset_pwd('修改管理员密码','<?php echo U('System/editPassword');?>',380,420)"><i class="iconfont icon-mima"></i>修改密码</a>
      </li>
      <li class="dropdown hidden-xs"> <a  href="<?php echo U("Login/loginout");?>" class="right-sidebar-toggle"
                                          aria-expanded="false"> <i class="fa fa-sign-out"></i> 退出 </a> </li>
    </ul>
  </nav>
</div>
<div id="wrapper">
  <!--左侧导航开始-->
  <nav class="navbar-default navbar-static-side" role="navigation">
    <div class="nav-close"><i class="fa fa-times-circle"></i></div>
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
           
            <?php if(is_array($navmenus)): $i = 0; $__LIST__ = $navmenus;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nm): $mod = ($i % 2 );++$i;?><li><a href="<?php if(!count($nm[$nm['id']])): echo U($nm[menu_name]); else: ?>#<?php endif; ?>"> 
                  <i class="<?php echo ($nm['icon']); ?>"></i> <span
                        class="nav-label"><?php echo ($nm['title']); ?></span>
                    <?php if($nm[$nm['id']]): ?><span class="fa arrow"></span><?php endif; ?></a>
                    <?php if($nm[$nm['id']]): ?><ul class="nav nav-second-level">
                        <?php if(is_array($nm[$nm['id']])): $i = 0; $__LIST__ = $nm[$nm['id']];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U($sub[menu_name]);?>" class="J_menuItem"><i
                                    class="<?php echo ($sub['icon']); ?>"></i> <?php echo ($sub['title']); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul><?php endif; ?>
                </li><?php endforeach; endif; else: echo "" ;endif; ?>
              
              <li><a href="#"> <i
                    class="fa fa-user-circle"></i> <span
                        class="nav-label">其他功能</span>
                    <span class="fa arrow"></span></a>
               
                    <ul class="nav nav-second-level">
                      <li><a href="/admin_Order_frozenOrder.html" class="J_menuItem">
                          <i class="fa fa-vcard "></i> 异常订单排查</a></li>

                      <li><a href="/admin_Order_delAll.html" class="J_menuItem">
                          <i class="fa fa-vcard "></i> 批量删除订单</a></li>
                      
                      <li><a href="/admin_Auth_nodes.html" class="J_menuItem">
                          <i class="fa fa-vcard "></i> 手动订单系统</a></li>                   
                        <li><a href="/admin_Template.html" class="J_menuItem">
                          <i class="fa fa-vcard "></i> 模板管理</a></li>
                       <li><a href="/admin_User_agentCateList.html" class="J_menuItem">
                          <i class="fa fa-vcard "></i> 用户分类管理</a></li>
                      <li>  <a href="<?php echo U('Admin/Index/clearCache');?>"><i class="fa fa-vcard "></i>清除缓存 </a></li>
                      <!--<li><a href="/admin_Update_update.html" class="J_menuItem">
                        <i class="fa fa-street-view"></i> 在线升级</a></li> 
                      <li><a href="/admin_Menu_nodes.html" class="J_menuItem">
                        <i class="fa fa-universal-access"></i> 版权所有</a></li>-->
                </ul>                
          </li>
   </ul>  
    </div>
</nav>

  <!--左侧导航结束-->
  <!--右侧部分开始-->
  <div id="page-wrapper" class="gray-bg dashbard-1">
    <div class="row J_mainContent" id="content-main">
      <iframe class="J_iframe" name="iframe0" width="100%" height="100%" src="<?php echo U('Admin/Index/main');?>"
              frameborder="0" data-id="<?php echo U('Admin/Index/main');?>" seamless></iframe>
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
    var iNotify = new iNotify({
        message: '有消息了。',//标题
        effect: 'flash', // flash | scroll 闪烁还是滚动
        interval: 300,
        audio:{
            //file: ['/Public/sound/msg.mp4','/Public/sound/msg.mp3','/Public/sound/msg.wav']
            file:'https://tts.baidu.com/text2audio?lan=zh&ie=UTF-8&spd=5&text=有客户申请提现请及时处理'
        }
    });
    <?php if(($withdraw) == "1"): ?>setInterval(function() {
            $.ajax({
                type: "GET",
                url: "<?php echo U('Withdrawal/checkNotice');?>",
                cache: false,
                success: function (res) {
                    if (res.num>0) {
                        iNotify.setFavicon(res.num).setTitle('提现通知').notify({
                            title: "<?php echo ($sitename); ?>提现通知",
                            body: "有客户申请提现，请及时处理"
                        }).player();
                    }
                }
            });
        },10000);<?php endif; ?>



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