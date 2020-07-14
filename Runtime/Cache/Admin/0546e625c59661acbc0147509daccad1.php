<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
 <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?php echo ($sitename); ?>---管理</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/Front/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Front/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/Front/css/animate.css" rel="stylesheet">
    <link href="/Public/Front/css/style.css" rel="stylesheet">
      <link href="/Public/Front/css/zuy.css" rel="stylesheet">
    <link rel="stylesheet" href="/Public/Front/js/plugins/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/Public/Front/iconfont/iconfont.css"/>
<style>
        .layui-form-label {width:110px;padding:4px}
        .layui-form-item .layui-form-checkbox[lay-skin="primary"]{margin-top:0;}
        .layui-form-switch {width:54px;margin-top:0px;}
    </style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated">


    <div class="row">
        <?php if(is_array($list)): foreach($list as $key=>$v): ?><div class="col-sm-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                  <h5 style="width: 100%;"><?php echo ($v["name"]); ?>-<?php echo ($v["id"]); ?>   <a href="<?php echo U('Admin/Statistics/productChannelFinance',['id'=>$v[id]]);?>" style="float:right;color:red;">支付通道统计</a></h5>
                </div>  
                <div class="ibox-content">
                    <p>交易金额：<?php echo ($v["pays_amount"]); ?>元</p>
                    <p>手续费  &nbsp;&nbsp;&nbsp;：<?php echo ($v["pays_poundage"]); ?>元</p>
                    <p>入金总额：<?php echo ($v["pays_actualamount"]); ?>元</p>
                </div>
                <div class="ibox-content">
                    <p>
                        <span style="font-size: 16px;color:#0087ffbf;">交易笔数：<strong><?php echo ($v["count"]); ?></strong></span>
                        <span style="margin-left: 80px;color:#008000b0;font-size: 16px;">成功率：<strong><?php echo ($v["success_rate"]); ?>%</strong></span>
                    </p>
                    <p>
                    <p>
                        <span style="font-size: 16px;color:#008000b0;">成功笔数：<strong><?php echo ($v["success_count"]); ?></strong></span>
                        <span style="margin-left: 80px;font-size: 16px;color: #ff0000b3;">失败笔数：<strong><?php echo ($v["fail_count"]); ?></strong></span>
                    </p>
                </div>
            </div>
        </div><?php endforeach; endif; ?>
        <div class="page"><?php echo ($page); ?>
            <div class="layui-input-inline">
                <form class="layui-form" action="" method="get" id="pageForm" autocomplete="off">

                    <select name="rows" style="height: 32px;" id="pageList" lay-ignore >
                        <option value="">显示条数</option>
                        <option <?php if($_GET[rows] != '' && $_GET[rows] == 15): ?>selected<?php endif; ?> value="15">15条</option>
                        <option <?php if($_GET[rows] == 30): ?>selected<?php endif; ?> value="30">30条</option>
                        <option <?php if($_GET[rows] == 50): ?>selected<?php endif; ?> value="50">50条</option>
                        <option <?php if($_GET[rows] == 80): ?>selected<?php endif; ?> value="80">80条</option>
                        <option <?php if($_GET[rows] == 100): ?>selected<?php endif; ?> value="100">100条</option>
                        <option <?php if($_GET[rows] == 1000): ?>selected<?php endif; ?> value="1000">1000条</option>
                    </select>

                </form>
            </div>
        </div>
    </div>
    </div>

</div>
<script src="/Public/Front/js/jquery.min.js"></script>
<script src="/Public/Front/js/bootstrap.min.js"></script>
<script src="/Public/Front/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/Public/Front/js/content.js"></script>
<script src="/Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/x-layui.js" charset="utf-8"></script>

</body>
</html>