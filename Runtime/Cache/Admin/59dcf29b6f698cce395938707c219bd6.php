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
		<div class="ibox-title">权限管理</div>
		<div class="ibox-content">
			<form class="layui-form" autocomplete="off">
				<div class="layui-form-item">
					<label class="layui-form-label">权限列表：</label>
					<?php if(is_array($menus)): foreach($menus as $k=>$vo): ?><div class="layui-input-block">
							<input type="checkbox" name="menu[<?php echo ($vo["id"]); ?>]" lay-skin="primary" value="<?php echo ($vo['id']); ?>" title="<?php echo ($vo["title"]); ?>" class="level_one" <?php if(in_array($vo['id'],$rulesArr)){echo "checked";} ?>>
							<?php if(is_array($vo[$vo['id']])): foreach($vo[$vo['id']] as $key=>$v): ?><div class="layui-input-block">
									<input type="checkbox" name="menu[<?php echo ($v["id"]); ?>]" lay-skin="primary" title="<?php echo ($v["title"]); ?>" value="<?php echo ($v['id']); ?>" class="level_two" <?php if(in_array($v['id'],$rulesArr)){echo "checked";} ?>>
									<div class="layui-input-block">
										<?php if(is_array($v[$v['id']])): foreach($v[$v['id']] as $key=>$v1): ?><input type="checkbox" name="menu[<?php echo ($v1["id"]); ?>]" lay-skin="primary" title="<?php echo ($v1["title"]); ?>" value="<?php echo ($v1['id']); ?>" class="level_three" <?php if(in_array($v1['id'],$rulesArr)){echo "checked";} ?>><?php endforeach; endif; ?>
									</div>
								</div><?php endforeach; endif; ?>
						</div><?php endforeach; endif; ?>
				</div>
				<div class="layui-form-item">
					<div class="layui-input-block">
						<button class="layui-btn" lay-submit lay-filter="auth">立即提交</button>
					</div>
				</div>
				<input type="hidden" name="roleid" value="<?php echo (htmlspecialchars($_GET['roleid'])); ?>">
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
    layui.use(['layer','form'], function() {
        var form = layui.form,
            $ = layui.jquery;
        //选中
        $('.layui-form-checkbox').on('click', function (e){
            var children = $(this).parent('.layui-input-block').find('.layui-form-checkbox');
            var input = $(this).parent('.layui-input-block').find('input');

            if($(this).prev('input').hasClass('level_three')){
                if($(this).hasClass('layui-form-checked') == true){
                    $(this).addClass('layui-form-checked')
                    $(this).prev('input').prop('checked',true);
                }else{
                    $(this).removeClass('layui-form-checked');
                    $(this).prev('input').prop('checked',false);
                }
            }else{
                if($(this).hasClass('layui-form-checked') == true){
                    children.addClass('layui-form-checked')
                    input.prop('checked',true);
                }else{
                    children.removeClass('layui-form-checked');
                    input.prop('checked',false);
                }
            }
        })
        //监听提交
        form.on('submit(auth)', function(data){
            var menu_ids = data.field;
            var url = "<?php echo U('Auth/ruleGroup');?>";
            $.post(url,menu_ids,function(data){
                if(data.status == 'error'){
                    layer.msg(data.msg,{icon: 5});//失败的表情
                    return;
                }else{
                    layer.msg(data.msg, {
                        icon: 6,//成功的表情
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        history.go(-1);
                    });
                }
            })
            return false;//阻止表单跳转
        });
    });
</script>
</body>
</html>