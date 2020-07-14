<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="favicon.ico">
    <title>商户注册 - <?php echo ($sitename); ?></title>
    <!-- Bootstrap Core CSS -->
    <link href="Public/New/plugins/vendors/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="Public/New/plugins/vendors/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="Public/New/assets/css/style.css" rel="stylesheet">
    <link href="Public/Front/js/plugins/layui/css/layui.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="Public/New/js/html5shiv.js"></script>
    <script src="Public/New/js/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript">
        if (top.location !== self.location) {
            top.location = self.location;
        }
    </script>
</head>
<body class="single-page-bg">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
      <p class="loader__label"><?php echo ($sitename); ?></p>
    </div>
</div>
<div id="main-wrapper">
    <!--<div class="navbar-header mt-5 ml-5"></div> -->
    <div class="container">
        <div class="page-wrapper m-0 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-4">
                        <h2 class="font-25 font-medium" style="text-align: center;padding-bottom: 20px;padding-top: 100px;"><?php echo ($sitename); ?> - 商户注册</h2>
                        <div class="card border-0">
                            <div class="card-body p-0">
                               <form class="form bordered-input" id="formlogin" name="formlogin" method="post" role="form" action="">
                                    <div class="p-30 pb-0">
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="username" id="username" class="form-control pl-0 font-12" type="text" placeholder="请输入用户名" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="password" id="password" class="form-control pl-0 font-12" type="password" placeholder="请输入密码" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="password" id="confirmpassword" class="form-control pl-0 font-12" type="password" placeholder="请再次输入密码" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="email" id="email" class="form-control pl-0 font-12" type="text" placeholder="请输入您的Email地址" autocomplete="off">
                                            </div>
                                        </div>
                                        <?php if($siteconfig['invitecode'] == 1): ?><div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="invitecode" id="invitecode" class="form-control pl-0 font-12" type="text" placeholder="必须有邀请码才能注册"  value="<?php echo ($_GET['invitecode']); ?>" autocomplete="off">
                                            </div>
                                        </div><?php endif; ?>
                                        <div class="clearfix"></div>
                                        <div class="form-group row m-b-10">
                                            <div class="col-12">
                                                <p><a href="javascript:;"  id="regBtn" class="btn btn-rounded btn-primary m-b-20 waves-effect waves-light d-block">注册</a></p>
                                            </div>
                                        </div>
                                    </div>
    
                                    <div class="clearfix"></div>
                                    <hr>
                                   <div class="clearfix"></div>
                                    <div class="pl-3 pt-1 pb-3 pl-3"> 已有账号？ <a class="font-14 text-primary" href="./agent_Login_index.html">立即登录</a>
                                        <div class="clearfix"></div>

 </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                     
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- All Jquery -->
<!-- ============================================================== -->
<script src="Public/New/plugins/vendors/jquery/jquery.min.js"></script>
<!-- Bootstrap popper Core JavaScript -->
<script src="Public/New/plugins/vendors/bootstrap/js/popper.min.js"></script>
<script src="Public/New/plugins/vendors/bootstrap/js/bootstrap.min.js"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="Public/New/plugins/vendors/ps/perfect-scrollbar.jquery.min.js"></script>
<!--Menu sidebar -->
<script src="Public/New/assets/js/sidebarmenu.js"></script>
<!--Custom JavaScript -->
<script src="Public/New/assets/js/custom.min.js"></script>
<script src="Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>
<script>
    function checkEmail(email){
        var myReg=/^[a-zA-Z0-9_-]+@([a-zA-Z0-9]+\.)+(com|cn|net|org)$/;

        if(myReg.test(email)){
            return true;
        }else{

            return false;
        }
    }
    layui.use(['laydate', 'form', 'layer', 'table', 'element'], function() {
        layer = layui.layer; //弹层
        $("#regBtn").click(function () {
            var username = $.trim($("#username").val());
            var password =$.trim($("#password").val());
            var confirmpassword =$.trim($("#confirmpassword").val());
            var email = $.trim($("#email").val());
            var invitecode = $.trim($("#invitecode").val());

            if (username.length < 1) {
                layer.msg('请输入正确格式的用户名');
                return false;

            }
            else if (password.length < 6) {
                layer.msg('请输入正确格式的密码');
                return false;

            }
            else if (email==''||!checkEmail(email)) {
                layer.msg('请输入正确的邮箱');
                return false;
            }
            else if (password!=confirmpassword) {
                layer.msg('两次密码输入不一致');
                return false;

            }
        
            if(invitecode!='')
            {
                var checkinvitecode=true;
                $.ajax({
                    type:'post',
                    async:false,
                    url:'user_Login_checkinvitecode.html',
                    data: { invitecode: invitecode},
                    dataType:'json',
                    success:function(result){
                        checkinvitecode=result['valid'];
                    }
                })
                if(checkinvitecode==false)
                {
                    layer.msg('邀请码不正确');
                    return false;
                }
            }

            $.ajax({
                type:'post',
                url:'user_Login_checkRegister.html',
                data: { username: username, password: password, confirmpassword: confirmpassword,email:email,invitecode:invitecode},
                dataType:'json',
                success:function(result){
                    if(result['errorno'] == 0){

                        if(result['need_activate'] == 1) {
                            var TipsContent="<div style='padding: 50px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;'>" +
                                "<h3>恭喜您，注册成功！</h3>" +
                                "<p>我们已发送了一封验证邮件到 <strong>"+result['msg']['email']+"</strong>, 请注意查收您的邮箱，点击其中的链接激活您的账号！</p>" +
                                "<p>如果您未收到验证邮件，请联系管理员重新发送验证邮件或手动帮您激活账号。</p>"
                            "<br/><br/><a href='user_Login_index.html' style='color:#fff'>点击这里登录</a></div>";

                            layer.open({
                                type: 1,
                                title:'注册成功',
                                skin: 'layui-layer-rim', //加上边框
                                area: ['500px', '350px'], //宽高
                                content: TipsContent,
                                end: function () {
                                    setTimeout(function () {
                                        layer.msg('正在为您跳转到登录页面...');
                                        setTimeout(function() {
                                            window.location.href = "/" + "";
                                        },3000 );

                                    },2000);
                                }
                            });
                        } else {
                            layer.msg('注册成功！正在为您跳转到登录页面...');
                            setTimeout(function() {
                                window.location.href = "/" + "";
                            },3000 );
                        }
                    }else{
                        layer.msg(result['msg']);
                    }
                }
            })
        })
        $(document).keyup(function(event){
            if(event.keyCode ==13){
                $("#regBtn").trigger("click");
            }
        });
    });
</script>
</body>
</html>