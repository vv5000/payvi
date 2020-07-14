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
    <form class="layui-form" id="editForm">
      <div class="layui-form-item">
        <label class="layui-form-label">角色：</label>
        <div class="layui-input-inline">
          <select name="groupid" lay-verify="required" lay-search="">
            <option value=""></option>
            <?php if(is_array($groups)): $i = 0; $__LIST__ = $groups;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$g): $mod = ($i % 2 );++$i;?><option <?php if($admin_info['groupid'] == $g['id']): ?>selected<?php endif; ?>
              value="<?php echo ($g["id"]); ?>"><?php echo ($g["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">用户名：</label>
        <div class="layui-input-inline">
          <input type="text" name="username" value="<?php echo ($admin_info['username']); ?>" lay-verify="required" placeholder="请输入用户名" autocomplete="off"  id="username" class="layui-input">
          <input type="hidden" name="id" value="<?php echo ($admin_info["id"]); ?>" />
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">密码：</label>
        <div class="layui-input-inline">
          <input type="password" name="epassword"  placeholder="不修改留空" autocomplete="off"  class="layui-input">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">重复密码：</label>
        <div class="layui-input-inline">
          <input type="password" name="ereppassword"  placeholder="不修改留空" autocomplete="off"  class="layui-input">
        </div>
      </div>

      <div class="layui-form-item">
        <div class="layui-input-block">
          <button class="layui-btn" lay-submit lay-filter="user">立即提交</button>
          <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
      </div>
    </form>
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
    layui.use('form', function(){
        var form = layui.form,
            $ = layui.jquery;
        $("button[type=reset]").click();
        //监听提交
        form.on('submit(user)', function(data){

            $.ajax({
                url:"<?php echo U('Admin/editAdmin');?>",
                type:"post",
                data:$('#editForm').serialize(),
                success:function(res){
                    if(res.status){
                        layer.alert("操作成功", {icon: 6},function () {
                            parent.location.reload();
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }else{

                        layer.msg(res.msg ? res.msg : "操作失败!", {icon: 5},function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                        return false;
                    }
                }
            });
            return false;//阻止表单跳转
        });
    });
</script>
</body>
</html>