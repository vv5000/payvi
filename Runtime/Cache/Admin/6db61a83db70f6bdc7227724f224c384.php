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
    <div class="ibox-content">
    <form class="layui-form" action="" id="profile">
      <input type="hidden" name="userid" value="<?php echo ($u["id"]); ?>">
      <input type="hidden" name="salt" value="<?php echo ($u["salt"]); ?>">
      <input type="hidden" name="groupid" value="<?php echo ($u["groupid"]); ?>">
      <div class="layui-form-item">
        <label class="layui-form-label">登录密码：</label>
        <div class="layui-input-inline">
          <input type="text" name="u[password]" autocomplete="off" value="" placeholder="修改登录密码" readonly onfocus="this.removeAttribute('readonly');" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">不修改密码，请留空.</div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">支付密码：</label>
        <div class="layui-input-inline">
          <input type="text" name="u[paypassword]" lay-verify="" autocomplete="off" value="" placeholder="修改支付密码" readonly onfocus="this.removeAttribute('readonly');" class="layui-input">
        </div>
        <div class="layui-form-mid layui-word-aux">不修改密码，请留空.</div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit="submit" lay-filter="save">提交保存</button>
        </div>
      </div>
    </form>
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
    layui.use(['layer', 'form','laydate'], function(){
        var form = layui.form
            ,laydate = layui.laydate
            ,layer = layui.layer;

        //监听提交
        form.on('submit(save)', function(data){
            $.ajax({
                url:"<?php echo U('User/editPassword');?>",
                type:"post",
                data:$('#profile').serialize(),
                success:function(res){
                    if(res.status){
                        layer.alert("编辑成功", {icon: 6},function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }else{
                        layer.alert("操作失败", {icon: 5},function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }
                }
            });
            return false;
        });
    });
</script>
<!--统计代码，可删除-->
</body>
</html>