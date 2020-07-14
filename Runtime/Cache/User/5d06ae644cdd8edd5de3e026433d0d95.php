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
            <div class="ibox-title">
               
            </div>
            <!--条件查询-->
            <div class="ibox-content">
                <address>
                    <strong>API对接参数： <font  color="red">如需要添加白名单 请添加下面2个IP地址即可。</font></a></strong><br><br>
                    商户名称：<?php echo ($sitename); ?><br><br>
                    商户号：<?php echo ($fans['uid']+10000); ?><br><br>
                    网关地址：<?php echo ($siteurl); ?>Pay_Index.html<br><br>
                    商户APIKEY：<button id="apikey-query" onclick="apikey(this)" data-mobile="<?php echo ($mobile); ?>" class="layui-btn layui-btn-small layui-btn-normal">点击查看密钥</button><span id='apikey' class="hide"></span>
                </address>
                <address>
                    <strong>对接Demo下载</strong><br><br>
                    API文档：<a href="./Uploads/demo-api.rar" "><font  color="red">点击下载</font></a><br><br>
                    白名单ip：请查看后台公告信息<br><br>
　
                                    
                  <br><br>
                  
                </address>
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
</body>
<script>
    var apikey;
    var index;
    layui.use('layer', function(layer){
        apikey = function (obj) {
            index = layer.prompt({
                        formType: 1,
                        title: '为了安全起见，请输入支付密码',
                        close: false,
                        btn: ['确定','取消']
                    }, function (value){
                        if(!value){
                            layer.msg('请输入支付密码', {icon: 5});
                            return false;
                        }
                        show(value)
                    });
        };
    });
    

    function show(code){
        var data = {};
        if(code){
            data = {code:code};
        }
        $.ajax({
            url:"<?php echo U('User/Channel/Apikey');?>",
            type:"post",
            data: data,
            success:function(res){
                if(res.status){
                    $('#apikey-query').hide();
                    $('#apikey').text(res.apikey).removeClass('hide')
                    layer.close(index);
                }else {
                    layer.msg(res.msg, {icon: 5});
                    return false;
                }
            }
        });
    }
</script>
</html>