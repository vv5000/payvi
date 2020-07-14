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
                <h5>短信模板管理</h5>
                <div class="row">
                    <div class="col-sm-2 pull-right">
                        <a href="javascript:;" class="layui-btn layui-btn-small"
                           onclick="smstemplate_add('添加短信模板','<?php echo U('System/addSmsTemplate');?>',540,440)">添加短信模板</a>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <!--短信模板列表-->
                <div class="layui-field-box">
                <p>
                    <a href="javascript:;" id="checkAll" class="layui-btn layui-btn-sm">全选</a>
                    <a href="javascript:;" id="submitAllCode" class="layui-btn layui-btn-sm layui-btn-danger">批量修改模板代码</a>
                </p>
                <table class="layui-table" lay-data="{width:'100%'}">
                    <thead>
                    <tr>
                        <th lay-data="{field:'check' , width:60}">选中</th>
                        <th lay-data="{field:'key', width:80}">#</th>
                        <th lay-data="{field:'title', width:200}">标题</th>
                        <th lay-data="{field:'template_code', width:200}">模板代码</th>
                        <th lay-data="{field:'template_content', width:650}">模板内容</th>
                        <th lay-data="{field:'op', width:100}">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($cache)): $i = 0; $__LIST__ = $cache;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr>
                            <td><input type="checkbox"  title="" value="<?php echo ($v["id"]); ?>" class='checkIds' lay-skin="primary"></td>
                            <td><?php echo ($v["id"]); ?></td>
                            <td><?php echo ($v["title"]); ?></td>
                            <td>
                                <?php echo ($v["template_code"]); ?>
                            </td>
                            <td>
                                <?php echo ($v["template_content"]); ?>
                            </td>

                            <td>

                                <a onclick="smstemplate_edit('编辑短信模板','<?php echo U('System/editSmsTemplate',['id'=>$v[id]]);?>',540,440)"
                                   class="layui-btn layui-btn-mini layui-btn-normal"><i class="layui-icon">&#xe642;</i>编辑</a>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <?php echo ($page); ?>
            </div>
                <!--短信模板列表-->
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
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            , layer = layui.layer //弹层
            , form = layui.form //表单
            , table = layui.table; //表格
    });

    /*短信模板-添加*/
    function smstemplate_add(title,url,w,h) {
        x_admin_show(title,url,w,h);
    }
    /*短信模板-编辑*/
    function smstemplate_edit(title,url,w,h) {
        x_admin_show(title,url,w,h);
    }
    /*短信模板-删除*/
    function smstemplate_del(obj,id) {
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({
                url:"<?php echo U('System/deleteAdmin');?>",
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
    function smstemplate_show(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
    $('#checkAll').on('click', function(){
        var child = $('table').next().find('tbody input[type="checkbox"]');  ;
        child.each(function(){
            $(this).attr('checked', true);
        });
        $('.layui-form-checkbox').addClass('layui-form-checked');

    });
    $('#submitAllCode').on('click', function(){
        var id = '';
        $('.checkIds').each(function(){
            var _this = $(this);
            if( _this.is(':checked')  ){
                id = id + _this.val() + ',';
            }
        });
        if(id){
            code_view('批量设置模板代码', "<?php echo U('Admin/System/smsTemplateCode');?>?ids="+id, 640 , 200);
        }else{
            layer.msg('请选择短信模板', {icon: 2, time: 1000},function () {});
        }
    });
    function code_view(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
</script>
</body>
</html>