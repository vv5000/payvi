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
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>跑分系统 补单系统</h5>
            </div>
            <div class="ibox-content">
                <form class="layui-form" action="/admin_Auth_paofenbudan.html" target="_blank">

                    <div class="layui-form-item">
                        <label class="layui-form-label">平台订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="merReqNo"  autocomplete="off" lay-verify="merReqNo" lay-verify="required" class="layui-input">
                        </div>
                    </div>

                  
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                  
                </div>
          </form>
        </div>
    </div>
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>支付宝通道1 补单系统</h5>
            </div>
            <div class="ibox-content">
                <form class="layui-form" action="/Supplement/index.php" target="_blank">
                  <input type="hidden" name="tradeNo" value="<?php echo $tradeNo?>">
                      <input type="hidden" name="merReqNo" value="<?php echo $merReqNo?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付宝订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="tradeNo" autocomplete="off" class="layui-input" lay-verify="title" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">平台订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="merReqNo"  autocomplete="off" lay-verify="merReqNo" lay-verify="required" class="layui-input">
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>

                </div>
          </form>
        </div>
    </div>


 <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5> 支付宝通道2补单系统</h5>
            </div>
            <div class="ibox-content">
                <form class="layui-form" action="/Supplement/bd.php" target="_blank">
                  <input type="hidden" name="tradeNo" value="<?php echo $tradeNo?>">
                      <input type="hidden" name="merReqNo" value="<?php echo $merReqNo?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付宝订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="tradeNo" autocomplete="off" class="layui-input" lay-verify="title" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">平台订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="merReqNo"  autocomplete="off" lay-verify="merReqNo" lay-verify="required" class="layui-input">
                        </div>
                    </div>

                  
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                  
                </div>
          </form>
        </div>
    </div>
  
   <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>支付宝通道3 补单系统</h5>
            </div>
            <div class="ibox-content">
                <form class="layui-form" action="/Supplement/zlbd.php" target="_blank">
                  <input type="hidden" name="tradeNo" value="<?php echo $tradeNo?>">
                      <input type="hidden" name="merReqNo" value="<?php echo $merReqNo?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label">支付宝订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="tradeNo" autocomplete="off" class="layui-input" lay-verify="title" lay-verify="required">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">平台订单号：</label>
                        <div class="layui-input-block">
                            <input type="text" name="merReqNo"  autocomplete="off" lay-verify="merReqNo" lay-verify="required" class="layui-input">
                        </div>
                    </div>

                  
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                  
                </div>
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
<script>
    layui.use(['form', 'laydate'], function(){
        var form = layui.form
            ,layer = layui.layer
            ,laydate = layui.laydate;
        //自定义验证规则
        form.verify({
            title: function(value){
                if(value.length < 28){
                    return '请输入支付宝订单号<br>或者订单号不足28位';
                }
            }
        });
       form.verify({
            merReqNo: function(value){
                if(value.length < 19){
                    return '请输入平台订单号<br>或者订单号不足19位';
                }
            }
      });
        //监听提交
        form.on('submit(adda)', function(data){
            $.ajax({
                url:"/Supplement",
                type:"post",
                data:$('#baseForm').serialize(),
                success:function(res){
                    if(res.status){
                        layer.alert("操作成功", {icon: 6},function () {
                            location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }else{
                        layer.msg("操作失败!", {icon: 5},function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                        return false;
                    }
                }
            });
            return false;
        });
    });
    function sendEmail() {
        $.ajax({
            url:"/admin_System_testEmail.html",
            type:"post",
            data:'cs_text='+$('#cs_text').val(),
            success:function(res){
                if(res.status){
                    layer.alert(res.msg, {icon: 6},function () {
                        location.reload();
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    });
                }else{
                    layer.msg(res.msg, {icon: 6},function () {
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    });
                    return false;
                }
            }
        });
    };
</script>
</body>
</html>