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
    <div class="col-sm-12">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5>登录记录</h5>
            <div class="ibox-tools">
                <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                   style="cursor:pointer;">ဂ</i>
            </div>
        </div>
        <div class="ibox-content">
            <form class="layui-form" action="" method="get" autocomplete="off">
                <input type="hidden" name="m" value="<?php echo ($model); ?>">
                <input type="hidden" name="c" value="User">
                <input type="hidden" name="a" value="index">
                <input type="hidden" name="p" value="1">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <div class="layui-input-inline">
                            <input type="text" name="userid" autocomplete="off" placeholder="商户号/管理员ID" class="layui-input" value="<?php echo ($username); ?>">
                        </div>
                        <div class="layui-input-inline">
                            <select name="type">
                                <option value="">用户类型</option>
                                <option value="0" <?php if($type != '' && $type == 0): ?>selected<?php endif; ?>>商户</option>
                                <option value="1" <?php if($type == 1): ?>selected<?php endif; ?>>后台管理员</option>
                            </select>
                        </div>
                        <div class="layui-input-inline" style="width: 300px">
                            <input type="text" class="layui-input" name="logindatetime" id="logindatetime" placeholder="时间范围" value="<?php echo ($logindatetime); ?>">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="loginip" autocomplete="off" placeholder="登录IP" class="layui-input" value="<?php echo ($loginip); ?>">
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn"><span
                                    class="glyphicon glyphicon-search"></span> 搜索
                            </button>
                            <a href="javascript:;" id="export"
                               class="layui-btn layui-btn-danger"><span
                                    class="glyphicon glyphicon-export"></span> 导出数据</a>
                        </div>
                    </div>
                </div>
            </form>
               <table class="layui-table" lay-skin="line">
                   <thead>
                   <tr>
                       <th>ID</th>
                       <th>用户类型</th>
                       <th>用户编号</th>
                       <th>登录时间</th>
                       <th>地点</th>
                       <th>IP</th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                       <td><?php echo ($vo["id"]); ?></td>
                       <td><?php if(($vo["type"]) == "0"): ?>商户<?php else: ?>后台管理员<?php endif; ?></td>
                       <td><?php echo ($vo["userid"]); ?></td>
                       <td><?php echo ($vo["logindatetime"]); ?></td>
                       <td><?php echo ($vo["loginaddress"]); ?></td>
                       <td><?php echo ($vo["loginip"]); ?></td>
                   </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                   </tbody>
               </table>
          <div class="page">
              <form class="layui-form" action="" method="get" id="pageForm"  autocomplete="off">
                  <?php echo ($page); ?>
                  <select name="rows" style="height: 29px;" id="pageList" lay-ignore >
                      <option value="">显示条数</option>
                      <option <?php if($rows != '' && $rows == 15): ?>selected<?php endif; ?> value="15">15条</option>
                      <option <?php if($rows == 30): ?>selected<?php endif; ?> value="30">30条</option>
                      <option <?php if($rows == 50): ?>selected<?php endif; ?> value="50">50条</option>
                      <option <?php if($rows == 80): ?>selected<?php endif; ?> value="80">80条</option>
                      <option <?php if($rows == 100): ?>selected<?php endif; ?> value="100">100条</option>
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
<script>
    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,form = layui.form //表单
            , table = layui.table; //表格
        //日期时间范围
        laydate.render({
            elem: '#logindatetime'
            , type: 'datetime'
            ,theme: 'molv'
            , range: '|'
        });
    });
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('Admin/User/exportloginrecord',array('userid'=>$userid,'type'=>$type,'logindatetime'=>$logindatetime,'loginip'=>$loginip));?>";
    });
</script>
</body>
</html>