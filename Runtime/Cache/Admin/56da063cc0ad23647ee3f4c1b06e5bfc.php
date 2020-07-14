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
            <!--条件查询-->
            <div class="ibox-title">
                <h5>交易设置</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->
            <div class="ibox-content">
              <br>  <br>
                <div class="layui-tab">
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" action="" id="profile">

                                <input type="hidden" name="data[id]" value="1">

                                <div class="layui-form-item">
                                    <label class="layui-form-label">保证金比例：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" max="100" step="0.1" name="data[ratio]" autocomplete="off" value="<?php echo ($info["ratio"]); ?>" placeholder="0" class="layui-input">
                                    </div>
                                    <div class="layui-input-inline" style="line-height: 38px;">
                                        %
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">冻结周期：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" max="100" step="1" name="data[freeze_time]" autocomplete="off" value="<?php echo ($info["freeze_time"]); ?>" placeholder="0" class="layui-input">
                                    </div>
                                    <div class="layui-input-inline" style="line-height: 38px;">
                                        小时
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">规则状态：</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="data[status]" <?php if($info['status'] == 0): ?>checked<?php endif; ?> value="0" title="关闭" checked="">
                                        <input type="radio" name="data[status]" <?php if($info['status'] == 1): ?>checked<?php endif; ?> value="1" title="开通">
                                    </div>
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
    layui.use(['layer', 'form', 'laydate','element'], function () {
        var form = layui.form
            ,$ = layui.jquery
            , laydate = layui.laydate
            ,element = layui.element
            , layer = layui.layer;
        //监听提交
        form.on('submit(save)', function (data) {
            $.ajax({
                url: "<?php echo U('Deposit/editConfig');?>",
                type: "post",
                data: $('#profile').serialize(),
                success: function (res) {
                    if (res.status) {
                        layer.alert("编辑成功", {icon: 6}, function () {
                            location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }else{
                            layer.alert(res['msg'], {icon: 2});
                    }
                }
            });
            return false;
        });
    });

</script>
</body>
</html>