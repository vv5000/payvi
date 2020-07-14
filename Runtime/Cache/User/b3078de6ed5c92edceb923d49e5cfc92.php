<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?php echo ($sitename); ?> - 管理中心</title>
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
    <div class="col-sm-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>登录记录</h5>
        </div>
        <div class="ibox-content"><br>
               <table class="layui-table" lay-skin="line">
                   <thead>
                   <tr>
                       <th></th>
                       <th>登录时间</th>
                       <th>地点</th>
                       <th>IP</th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                       <td><?php echo ($key+1); ?></td>
                       <td><?php echo ($vo["logindatetime"]); ?></td>
                       <td><?php echo ($vo["loginaddress"]); ?></td>
                       <td><?php echo ($vo["loginip"]); ?></td>
                   </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                   </tbody>
               </table>
            <div class="page">
                <form class="layui-form" action="" method="get" id="pageForm"  autocomplete="off">
                    <?php echo ($page); ?>
                    <select name="rows" style="height: 32px; display: inline-block;" id="pageList" lay-ignore >
                        <option value="">显示条数</option>
                        <option <?php if($_GET[rows] != '' && $_GET[rows] == 15): ?>selected<?php endif; ?> value="15">15条</option>
                        <option <?php if($_GET[rows] == 30): ?>selected<?php endif; ?> value="30">30条</option>
                        <option <?php if($_GET[rows] == 50): ?>selected<?php endif; ?> value="50">50条</option>
                        <option <?php if($_GET[rows] == 80): ?>selected<?php endif; ?> value="80">80条</option>
                        <option <?php if($_GET[rows] == 100): ?>selected<?php endif; ?> value="100">100条</option>
                    </select>
                </form>
            </div>
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
<script src="/Public/Front/js/Util.js" charset="utf-8"></script>
<script>
    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
</script>
</body>
</html>