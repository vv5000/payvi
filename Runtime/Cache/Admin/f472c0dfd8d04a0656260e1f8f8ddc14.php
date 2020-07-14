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
			<form class="layui-form" action="" id="authorize">
				<input type="hidden" name="u[userid]" value="<?php echo ($u["id"]); ?>">
				<div class="layui-form-item">
					<label class="layui-form-label">商户密钥：</label>
					<div class="layui-input-inline">
						<input type="text" name="u[apikey]" autocomplete="off" value="<?php echo ($u["apikey"]); ?>" id="md5key" placeholder="商户密钥：" class="layui-input">
					</div>&nbsp;&nbsp;
					<div class="layui-inline">
						<a href="javascript:changeKey();" class="layui-btn layui-btn-danger"><i class="layui-icon">&#x1002;</i> 换一换密钥</a>
					</div>
				</div>

				<div class="layui-form-item">
					<label class="layui-form-label">商户认证：</label>
					<div class="layui-input-inline">
						<select name="u[authorized]">
							<option value=""></option>
							<option <?php if($u['authorized'] == 0): ?>selected<?php endif; ?> value="0">未认证</option>
							<option <?php if($u['authorized'] == 2): ?>selected<?php endif; ?> value="2">等待审核</option>
							<option <?php if($u['authorized'] == 1): ?>selected<?php endif; ?> value="1">认证用户</option>
						</select>
					</div>
				</div>

				<?php if($u['authorized']): ?><div class="layui-form-item">
						<label class="layui-form-label">认证图片：</label>
						<div class="layui-input-block">
							<?php if(is_array($u['images'])): $i = 0; $__LIST__ = $u['images'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?><a class="fancybox" href="<?php echo ($img["path"]); ?>" title="">
									<img alt="image" src="<?php echo ($img["path"]); ?>" data-src="holder.js/100x100" class="img-thumbnail" alt="140x140" style="width: 100px; height: 100px;""/>
								</a><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div><?php endif; ?>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit="submit" lay-filter="add">提交保存</button>
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
<link href="/Public/Front/js/plugins/fancybox/jquery.fancybox.css" rel="stylesheet">
<script src="/Public/Front/js/plugins/fancybox/jquery.fancybox.js"></script>
<script>
    $(document).ready(function () {
        $('.fancybox').fancybox({
            openEffect: 'none',
            closeEffect: 'none'
        });
    });
</script>

<script>
    layui.use(['layer', 'form'], function(){
        var form = layui.form
            ,layer = layui.layer;

        //监听提交
        form.on('submit(add)', function(data){
            $.ajax({
                url:"<?php echo U('User/editAuthoize');?>",
                type:"post",
                data:$('#authorize').serialize(),
                success:function(res){
                    if(res.status){
                        layer.alert("编辑成功", {icon: 6},function () {
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
    //监听切换密钥
    function changeKey(){
        $.ajax({
            url:"<?php echo U('User/getRandstr');?>",
            type:"GET",
            success:function(str){
                $('#md5key').val(str);
            }
        });
        return false;
    }
</script>
</body>
</html>