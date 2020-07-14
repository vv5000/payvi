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
                <!-- Content -->
                <div class="ibox-content">
					<blockquote class="layui-elem-quote layui-quote-nm">
					<p>可用余额：<span class="text-danger"><?php echo ($info["balance"]); ?></span>元</p>
					<p>冻结余额：<span class="text-danger"><?php echo ($info["blockedbalance"]); ?></span>元</p>
					</blockquote>
                    <form class="layui-form" action="" autocomplete="off" id="incr">
                        <input type="hidden" name="uid" value="<?php echo ($info['id']); ?>">
                        <input type="hidden" name="date" value="<?php echo ($date); ?>">
                        <div class="layui-form-item">
                            <label class="layui-form-label">操作：</label>
                            <div class="layui-input-block">
                                <input type="radio" name="cztype" value="3" title="增加金额" checked="">
                                <input type="radio" name="cztype" value="4" title="减少金额">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">变动金额：</label>
                            <div class="layui-input-block">
                                <input type="text" name="bgmoney" lay-verify="required" autocomplete="off"
                                       placeholder="请输入金额" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item layui-form-text">
                            <label class="layui-form-label">备注：</label>
                            <div class="layui-input-block">
                                <textarea placeholder="请输入内容" lay-verify="required" class="layui-textarea" name="memo"></textarea>
                            </div>
                        </div>
                        <?php if($verifyGoogle and $verifysms): ?><div class="layui-form-item" id="df_auto_check">
                            <label class="layui-form-label">验证方式：</label>
                            <div class="layui-input-block">
                                <input type="radio" name="auth_type" lay-filter="auth_type" value="1" title="谷歌安全码" checked>
                                <input type="radio" name="auth_type" lay-filter="auth_type" value="0" title="短信验证码">
                            </div>
                        </div>
                         <?php else: ?>
                            <input type="hidden" name="auth_type" value="<?php echo ($auth_type); ?>"><?php endif; ?>
                        <?php if(($verifyGoogle) == "1"): ?><div class="layui-form-item" id="auth_google">
                            <label class="layui-form-label">谷歌安全码：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="google_code" autocomplete="off"
                                       placeholder="请输入谷歌安全码" class="layui-input" value="">
                            </div>
                        </div><?php endif; ?>
                        <?php if(($verifysms) == "1"): ?><div class="layui-form-item" id="auth_sms" <?php if($verifyGoogle and $verifysms): ?>style="display: none"<?php endif; ?>>
                            <label class="layui-form-label">手机验证码：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="code"  autocomplete="off"
                                       placeholder="请输入短信验证码" class="layui-input" value="">
                            </div>
                            <div class="layui-input-inline">
                                <a href="javascript:;" id="sendBtn" data-mobile="<?php echo ($mobile); ?>" class="layui-btn">发送验证码</a>
                            </div>
                        </div><?php endif; ?>
                        <div class="layui-form-item">
                            <div class="layui-input-block">
                                <button class="layui-btn" lay-submit="" lay-filter="save">立即提交</button>
                                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Content -->
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
layui.use(['form','layer'], function(){
        var form = layui.form
            ,layer = layui.layer;
    //监听提交
    form.on('submit(save)', function(data){
        $.ajax({
            url:"<?php echo U('User/incrMoney');?>",
            type:"post",
            data:$('#incr').serialize(),
            success:function(res){
                if(res.status){
                    layer.alert(res.msg, {icon: 6},function () {
                        parent.location.reload();
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                    });
                }else{
                    layer.alert(res.msg?res.msg:"操作失败", {icon: 5});
                }
            }
        });
        return false;
    });
    form.on('radio(auth_type)',function(data){
        if(data.value == 1) {
            $('#auth_google').show();
            $('#auth_sms').hide();
        } else {
            $('#auth_google').hide();
            $('#auth_sms').show();
        }
    });
});
</script>
<script>
    $(function (){
        var sendUrl = "<?php echo U('User/adjustUserMoney');?>";
        // 手机验证码发送
        $('#sendBtn').click(function(){
            var mobile = $(this).data('mobile');
            if(mobile == '') {
                layer.alert('请先绑定手机号码',{icon: 5}, function() {
                    location.href = "<?php echo U('System/bindMobileShow');?>";
                });
                return;
            }
            sendSms(this, mobile, sendUrl);
        });
    })
</script>
</body>
</html>