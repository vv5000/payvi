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
            <div class="ibox-content">
                <form class="layui-form" action="" autocomplete="off" id="editwtstatus">
                    <input type="hidden" name="id" value="<?php echo ($info["id"]); ?>">
                    <input type="hidden" name="userid" value="<?php echo ($info["userid"]); ?>">
                    <input type="hidden" name="tkmoney" value="<?php echo ($info["tkmoney"]); ?>">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <select name="status">
                                    <option value="">选择操作</option>
                                    <option <?php if($info['status'] == 1): ?>selected<?php endif; ?> value="1">变更为处理中</option>
                                    <option <?php if($info['status'] == 2): ?>selected<?php endif; ?> value="2">变更为已处理</option>
                                    <option <?php if($info['status'] == 3): ?>selected<?php endif; ?> value="3">驳回结算</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <button class="layui-btn" lay-submit="" lay-filter="wtsave">立即提交</button>
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item layui-form-text">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <textarea placeholder="驳回理由" class="layui-textarea" name="memo"></textarea>
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
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function () {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            , layer = layui.layer //弹层
            , form = layui.form //表单
            , table = layui.table; //表格

        //监听提交
        form.on('submit(wtsave)', function (data) {
            $.ajax({
                url: "<?php echo U('Admin/Withdrawal/editwtStatus');?>",
                type: "post",
                data: $('#editwtstatus').serialize(),
                success: function (res) {
                    if (res.status) {
                        layer.alert("编辑成功", {icon: 6}, function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    } else {
                        layer.msg(res.msg ? res.msg : "操作失败!", {icon: 5}, function () {
                            parent.location.reload();
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
</script>
</body>
</html>