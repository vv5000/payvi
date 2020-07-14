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
                <h5>编辑个人资料</h5>
            </div>
            <div class="ibox-content">
                <!--用户信息-->
                <form class="layui-form" action="" autocomplete="off" id="profile">
                    <input type="hidden" name="id" value="<?php echo ($p["id"]); ?>">
                    <div class="layui-form-item">
                        <label class="layui-form-label">上级代理：</label>
                        <div class="layui-input-block">
                            <input type="text" name="p[agentname]" lay-verify="title" autocomplete="off"
                                   placeholder="上级代理名称" class="layui-input"  value="<?php echo (htmlentities($p["agentname"])); ?>">
                          <span style="color: red;font-size: 15px;">*谨慎修改用户信息，胡乱填写一律禁封账号处理</span>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">姓名：</label>
                        <div class="layui-input-block">
                            <input type="text" name="p[realname]" lay-verify="title" autocomplete="off"
                                   placeholder="姓名" class="layui-input" value="<?php echo (htmlentities($p["realname"])); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">身份证号码：</label>
                        <div class="layui-input-block">
                            <input type="text" name="p[sfznumber]" lay-verify="identity" placeholder="身份证号码" autocomplete="off" class="layui-input" value="<?php echo (htmlentities($p["sfznumber"])); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <?php if($sms_is_open): ?><label class="layui-form-label">手机：</label>
                                <div class="layui-input-inline">
                                    <input type="text"  lay-verify="phone" disabled autocomplete="off"
                                           class="layui-input" value="<?php echo (htmlentities($p["mobile"])); ?>">
                                </div>
                              <span style="color: red;font-size: 15px;">
                                    <?php if($p['mobile']): ?><a href="javascript:;" style="color:blue;" class="editMobile" data-id="<?php echo ($p["id"]); ?>">点击修改手机号码</a>
                                    <?php else: ?>
                                        <a href="javascript:;" style="color:blue;" class="bindMobile" data-id="<?php echo ($p["id"]); ?>">点击绑定手机号码</a><?php endif; ?>               
                               </span>
                            <?php else: ?>
                                 <label class="layui-form-label">手机：</label>
                                <div class="layui-input-inline">
                                    <input type="text"  lay-verify="phone" name="p[mobile]" autocomplete="off"
                                           class="layui-input" value="<?php echo (htmlentities($p["mobile"])); ?>">
                                </div><?php endif; ?>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">QQ：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="p[qq]" lay-verify="" autocomplete="off"
                                       class="layui-input" value="<?php echo (htmlentities($p["qq"])); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">生日：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="p[birthday]" id="date" lay-verify="date" placeholder="YYYY-MM-dd" autocomplete="off" class="layui-input" value="<?php echo (date('Y-m-d',$p["birthday"])); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">性别：</label>
                        <div class="layui-input-block">
                            <input type="radio" <?php if($p[sex] == 1): ?>checked<?php endif; ?> name="p[sex]" value="1"
                            title="男" checked="">
                            <input type="radio" <?php if($p[sex] == 0): ?>checked<?php endif; ?> name="p[sex]" value="0"
                            title="女">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">联系地址：</label>
                        <div class="layui-input-block">
                            <input type="text" name="p[address]" lay-verify="title" autocomplete="off"
                                   placeholder="联系地址" class="layui-input" value="<?php echo (htmlentities($p["address"])); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">登录IP  </label>
                        <div class="layui-input-block">
                            <textarea name="p[login_ip]" placeholder="请输入内容" class="layui-textarea"><?php echo ($p[login_ip]); ?></textarea>
                            <span style="color: red;font-size: 15px;">*输入多个IP请换行，如果不输入默认所有IP可登录</span>
                        </div>

                    </div>
                    <?php if(($df_api) == "1"): ?><div class="layui-form-item">
                        <label class="layui-form-label">代付API接口状态：</label>
                        <div class="layui-input-block">
                            <input type="radio" name="p[df_api]" lay-filter="df_api" value="1" title="开启" <?php if($p['df_api'] == 1): ?>checked<?php endif; ?>>
                            <input type="radio" name="p[df_api]" lay-filter="df_api" value="0" title="关闭" <?php if($p['df_api'] == 0): ?>checked<?php endif; ?>>
                        </div>
                    </div>
                        <div class="layui-form-item" id="df_auto_check" <?php if($p['df_api'] == 0): ?>style="display: none;"<?php endif; ?>>
                            <label class="layui-form-label">代付API自动审核：</label>
                            <div class="layui-input-block">
                                <input type="radio" name="p[df_auto_check]" lay-filter="df_auto_check" value="1" title="开启" <?php if($p['df_auto_check'] == 1): ?>checked<?php endif; ?>>
                                <input type="radio" name="p[df_auto_check]" lay-filter="df_auto_check" value="0" title="关闭" <?php if($p['df_auto_check'] == 0): ?>checked<?php endif; ?>>
                            </div>
                        </div>
                        <div class="layui-form-item" id="df_domain" <?php if($p['df_api'] == 0): ?>style="display: none;"<?php endif; ?>>
                            <label class="layui-form-label">代付域名报备：</label>
                            <div class="layui-input-block">
                                <textarea name="p[df_domain]" placeholder="如：www.baidu.com，多个域名请换行，一行一个域名" class="layui-textarea"><?php echo ($p[df_domain]); ?></textarea>
                            </div>
                        <label class="layui-form-label"></label><div class="layui-form-mid layui-word-aux">注意：无需填写http://或https://，也不需要填写具体路径，结尾不加 /，一行一个域名。下级商户只能从这些域名发起代付请求！</div>
                        </div>
                        <div class="layui-form-item" id="df_ip" <?php if($p['df_api'] == 0): ?>style="display: none;"<?php endif; ?>>
                            <label class="layui-form-label">代付IP报备：</label>
                            <div class="layui-input-block">
                                <textarea name="p[df_ip]" placeholder="如：127.0.0.1，多个IP请换行，一行一个IP" class="layui-textarea"><?php echo ($p[df_ip]); ?></textarea>
                            </div>
                        <label class="layui-form-label"></label><div class="layui-form-mid layui-word-aux">注意：请填写发起代付请求的服务器公网IP一行一个IP地址。</div>
                        </div><?php endif; ?>
                    
                    
                    
                    
                    
                   
                    
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

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="profile">立即提交</button>
                            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                        </div>
                    </div>
                </form>
                <!--用户信息-->
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
layui.use(['laydate', 'laypage', 'layer', 'form', 'element'], function() {
    var laydate = layui.laydate //日期
        ,layer = layui.layer //弹层
        ,form = layui.form //弹层
        , element = layui.element; //元素操作
    //日期
    laydate.render({
        elem: '#date'
    });
    //监听提交
    form.on('submit(profile)', function(data){
        $.ajax({
            url:"<?php echo U('Account/saveProfile');?>",
            type:"post",
            data:$('#profile').serialize(),
            success:function(res){
                if(res.status){
                    layer.alert(res.msg, {icon: 6},function () {
                        location.reload();
                    });
                }else{
                    layer.alert(res.msg, {icon: 5});
                }
            }
        });
        return false;
    });
    //绑定手机
    $('.bindMobile').click(function(){
        var id = $(this).data('id');
        m_admin_show('绑定手机', "<?php echo U('Account/bindMobileShow');?>?id=" + id);
        
    })
        //修改手机
    $('.editMobile').click(function(){
        var id = $(this).data('id');
        m_admin_show('绑定手机', "<?php echo U('Account/editMobileShow');?>?id=" + id);
        
    })
    form.on('radio(df_api)',function(data){
        if(data.value == 1) {
            $('#df_auto_check').show();
            $('#df_domain').show();
            $('#df_ip').show();
        } else {
            $('#df_auto_check').hide();
            $('#df_domain').hide();
            $('#df_ip').hide();
        }
    });
});
function m_admin_show(title,url,w,h){
    if (title == null || title == '') {
        title=false;
    };
    if (url == null || url == '') {
        url="404.html";
    };
    if (w == null || w == '') {
        w=($(window).width());
    };
    if (h == null || h == '') {
        h=($(window).height());
    };
    layer.open({
        type: 2,
        area: [w+'px', h +'px'],
        fix: false, //不固定
        maxmin: true,
        shadeClose: true,
        shade:0.4,
        title: title,
        content: url
    });
}
</script>
</body>
</html>