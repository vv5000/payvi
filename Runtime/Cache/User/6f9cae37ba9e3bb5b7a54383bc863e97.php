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
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-content">
                <form class="layui-form" action="" autocomplete="off" id="df_form">
                    <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
                    <div class="layui-form-item layui-form-text">
                        <div class="layui-inline">
                            <div class="layui-inline">
                                <div class="layui-input-block">
                                    金额：<?php echo ($info["money"]); ?>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-block">
                                    银行卡号：<?php echo ($info["cardnumber"]); ?>
                                </div>
                            </div>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    开户名：<?php echo ($info["accountname"]); ?>
                                </div>
                            </div>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    申请时间：<?php echo (date('Y-m-d H:i:s',$info["create_time"])); ?>
                                </div>
                            </div>
                            <div class="layui-input-block" style="height:50px">
                                <div class="layui-input-inline">
                                    <input type="password" name="password" lay-verify="pass" placeholder="请输入支付密码" autocomplete="off" class="layui-input">
                                </div>
                            </div>
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="dfsave">审核通过</button>
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
<script src="/Public/Front/js/Util.js" charset="utf-8"></script>
<script>
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function () {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            , layer = layui.layer //弹层
            , form = layui.form //表单
            , table = layui.table; //表格

        //监听提交
        form.on('submit(dfsave)', function (data) {
            if($("input[name='password']").val() == '') {
                layer.alert('请输入支付密码');
                return false;
            }
            layer.confirm('审核通过后，后台审核后钱款将会打到对应账号，请谨慎操作！确认要将提款申请提交到后台吗？', {
                    btn: ['确定','取消'] //按钮
                }, function(){
                    $.ajax({
                        url: "<?php echo U('User/Withdrawal/dfPass');?>",
                        type: "post",
                        data: $('#df_form').serialize(),
                        success: function (res) {
                            if (res.status) {
                                layer.alert(res.msg, {icon: 6}, function () {
                                    parent.location.reload();
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.layer.close(index);
                                });
                            } else {
                                layer.msg(res.msg ? res.msg : "操作失败!");
                                return false;
                            }
                        }
                    })
            }, function(){

            });
            return false;
        });
    });
</script>
</body>
</html>