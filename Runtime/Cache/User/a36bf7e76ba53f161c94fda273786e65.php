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
<link href="/Public/Front/css/fileinput.min.css" rel="stylesheet">
<link href="/Public/Front/css/theme.css" rel="stylesheet">
<div class="row">
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>申请认证</h5>
            </div>
            <div class="ibox-content">
                <?php if($authorized == 1): ?><p class="bg-success" style="padding:10px 0px 10px 30px">您已成功认证！</p>
                <?php elseif($authorized == 2): ?>
                    <p class="bg-info" style="padding:10px 0px 10px 30px">已提交认证，等待审核！</p>
                <?php else: ?>
                    <blockquote class="layui-elem-quote">
                        <p class="text-danger">请上传：身份证正反面、正面手持身份证、营业执照、银行卡正反面图片。</p>
                    </blockquote>
                    <input id="input-ke-1" name="auth[]" type="file" multiple class="file-loading" accept="image"><?php endif; ?>
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
<script src="/Public/Front/js/fileinput.min.js"></script>
<script src="/Public/Front/js/fileinput_locale_zh.js"></script>
<script src="/Public/Front/js/theme.js"></script>
<script>
    layui.use([ 'layer','element'], function() {
        var layer = layui.layer //弹层
            ,element = layui.element; //元素操作
    });
    $("#input-ke-1").fileinput({
        language: 'zh',
        theme: "explorer",
        uploadUrl: "<?php echo U('Account/upload');?>",
        allowedFileExtensions: ['jpg', 'png', 'gif'],
        overwriteInitial: false,
        initialPreviewAsData: true,
        maxFileCount: 6,
    }).on('filebatchuploadcomplete', function(event, data) {
        layer.confirm('现在去申请认证吗？', function (index) {
            window.location.href='<?php echo U("Account/certification");?>';
        });
    });
</script>
</body>
</html>