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
                <h5>公告列表</h5>
                <div class="row">
                    <div class="col-sm-2 pull-right">
                        <a href="javascript:article_add('发表文章','<?php echo U('Content/addArticle');?>');"
                           class="layui-btn layui-btn-small">发表文章</a>

                        <a href="javascript:;" class="layui-btn layui-btn-small" onclick="location.replace(location.href);"><i class="layui-icon" title="刷新" style="cursor:pointer;">ဂ</i></a>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <!--文章列表-->
              <br><br>
                <table class="layui-table" lay-even="" lay-skin="nob">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>标题</th>
                        <th>分组</th>
                        <th>发表时间</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$a): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($a["id"]); ?></td>
                            <td><?php echo (msubstr($a["title"],0,48)); ?></td>
                            <td><?php echo ($groups[$a['groupid']]); ?></td>
                            <td><?php echo (time_format($a['createtime'])); ?></td>
                            <td>
                                <?php switch($a[status]): case "1": ?>显示<?php break;?>
                                    <?php case "0": ?>隐藏<?php break; endswitch;?>
                            </td>
                            <td>
                                <a class="layui-btn layui-btn-small" title="编辑"
                                        onclick="article_edit('编辑文章','<?php echo U('Content/editArticle',['id'=>$a['id']]);?>')"><i class="layui-icon"></i></a>
                                <a class="layui-btn layui-btn-small" title="删除"
                                        onclick="article_del(this,'<?php echo ($a['id']); ?>')"><i class="layui-icon"></i></a>
                                <a class="layui-btn layui-btn-small" title="预览"
                                        onclick="article_show('预览文章','<?php echo U('Content/show',['id'=>$a['id']]);?>')"><i class="layui-icon"></i></a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <!--文章列表-->
                <div class="pagex"><?php echo ($page); ?></div>
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
layui.use(['laydate', 'laypage', 'layer', 'table','element'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            , table = layui.table //表格
            , element = layui.element; //元素操作
    });
/* 添加文章*/
function article_add(title,url,w,h){
    x_admin_show(title,url,w,h);
}
/* 预览文章*/
function article_show(title,url,w,h){
    x_admin_show(title,url,w,h);
}
/*编辑文章*/
function article_edit(title,url,w,h){
    x_admin_show(title,url,w,h);
}
/*删除文章*/
function article_del(obj,id){
    layer.confirm('确认要删除吗？',function(index){
        $.ajax({
            url:"<?php echo U('Content/delArticle');?>",
            type:'post',
            data:'id='+id,
            success:function(res){
                if(res.status){
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!',{icon:1,time:1000});
                }
            }
        });
    });
}
</script>
</body>
</html>