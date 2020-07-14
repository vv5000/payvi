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
            <!--条件查询-->
            <div class="ibox-title">
                <h5>提款管理</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->
            <div class="ibox-content">
                <form class="layui-form" action="" method="get" autocomplete="off" id="withdrawalform">

                    <?php for($i=0; $i<10; $i++){ echo '<div class="layui-form-item">
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <select name="b['.$i.'][bankname]">
                                        <option value="">选择开户行</option> 
                                        <option value="农业银行">农业银行</option> 
                                        <option value="工商银行">工商银行</option>     
                                        <option value="建设银行">建设银行</option>
                                        <option value="中国银行">中国银行</option>
                                        <option value="兴业银行">兴业银行</option>
                                        <option value="平安银行">平安银行</option>
                                        <option value="交通银行">交通银行</option>
                                        <option value="招商银行">招商银行</option>
                                        <option value="农村信用社">农村信用社</option>  
                                        <option value="邮政储蓄">邮政储蓄</option>
                                        <option value="北京银行">北京银行</option>
                                        <option value="浙商银行">浙商银行</option>
                                        <option value="广发银行">广发银行</option>
                                        <option value="民生银行">民生银行</option>
                                        <option value="东亚银行">东亚银行</option>
                                        
                                        <option value="上海银行">上海银行</option>
                                        <option value="光大银行">光大银行</option>
                                        <option value="浦东发展银行">浦东发展银行</option>
                                        <option value="深圳发展银行">深圳发展银行</option>
                                        <option value="华夏银行">华夏银行</option>
                                        <option value="南京银行">南京银行</option>
                                        <option value="宁波银行">宁波银行</option>
                                        <option value="中信银行">中信银行</option>
                                        <option value="广西北部湾银行">广西北部湾银行</option>
                                    </select>
                                </div>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <input type="text" name="b['.$i.'][accountname]" autocomplete="off" placeholder="请输入开户名" class="layui-input" value="">
                                </div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="b['.$i.'][cardnumber]" placeholder="请输入银行卡号" value="">
                                </div>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="b['.$i.'][money]" placeholder="请输入金额" value="">
                                </div>
                            </div>
                        </div>'; } ?>
                                  
                          
                          
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
                            <label class="layui-form-label">谷歌密码：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="google_code" autocomplete="off"
                                       placeholder="请输入谷歌安全码" class="layui-input" value="">
                            </div>
                            
                        </div><?php endif; ?>
                    <?php if(($verifysms) == "1"): ?><div id="auth_sms" <?php if($verifyGoogle and $verifysms): ?>style="display: none"<?php endif; ?>>
                            <script src="/Public/Front/js/jquery.min.js"></script>
<?php if($sms_is_open): ?><div class="layui-form-item">
    <label class="layui-form-label">手机验证码：</label>
<div class="layui-input-inline" >
<input type="text" name="code"  autocomplete="off"  placeholder="" class="layui-input"   value=""> </div>
  <label class="layui-form-label"><br></label>
<div class="layui-form-mid layui-word-aux"><a href="javascript:;" id="sendBtn" data-bind='<?php echo ($first_bind_mobile); ?>' class="layui-btn" data-mobile="<?php echo ($fans[mobile]); ?>">发送验证码</a>
 </div>
 
  
</div><?php endif; ?>
<script>
    $(function (){
        // 手机验证码发送
        $('#sendBtn').click(function(){
            var mobile = $(this).attr('data-mobile');
            var first_bind = $(this).data('bind');
            var sendUrl = "<?php echo ($sendUrl); ?>";
            if(!mobile){
                //判断用户是否准备绑定手机号
                if(!first_bind){
                    layer.alert('请先填写手机号码',{icon: 5}, function() {
                        location.href = "<?php echo U('Account/profile');?>";
                    });
                }else{
                    layer.alert('请先填写手机号码',{icon: 5});
                }
                return;
            }
            sendSms(this, mobile, sendUrl);
        });
    })
</script>
                        </div><?php endif; ?>
                        <div class="layui-form-item" >
                            <label class="layui-form-label">支付密码：</label>
                            <div class="layui-input-inline">
                                <input type="password" id="password"
                                       placeholder="支付密码" class="layui-input" value="" name="password">
                            </div>
                            <button type="submit" class="layui-btn"><span class="glyphicon glyphicon-search"></span> 提交</button>
                        </div>                           
                           

                            
                
                            
                            
                    <div class="layui-inline">
                        
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
<script src="/Public/Front/js/Util.js" charset="utf-8"></script>
<script>
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,form = layui.form //表单
            , table = layui.table; //表格       
    });  

    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
</script>
</body>
</html>