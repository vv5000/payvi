<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="shortcut icon" href="favicon.ico">
    <title>代理商登陆 - <?php echo ($sitename); ?></title>
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
    <!--<div class="navbar-header mt-5 ml-5"></div>-->
    <div class="container">
        <div class="page-wrapper m-0 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-4">
                        <h2 class="font-25 font-medium" style="text-align: center;padding-bottom: 20px;padding-top: 100px;">代理商登陆</h2>
                        <div class="card border-0">
                            <div class="card-body p-0">
                                <form class="form bordered-input" id="formlogin" name="formlogin" method="post" role="form" action="/User_Login_checklogin">
                                    <div class="p-30 pb-0">
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="username" id="username" class="form-control pl-0 font-12" type="text" placeholder="代理商用户名" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="password" id="password" class="form-control pl-0 font-12" type="password" placeholder="密码" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-6">
                                                <input name="captcha" id="captcha" class="form-control pl-0 font-12" type="text" placeholder="验证码" autocomplete="off" onfocus="if(this.value=='')reimg()">
                                            </div>
                                            <div class="col-6">
                                              <img src="<?php echo ($verifycode); ?>" style="cursor:pointer;width:100%;height:50px;margin-top: -12px;" id="vercodeImg" alt="" onclick="this.src='/agent_Login_verifycode.html?d='+Math.random();" title="点击刷新验证码"/>
                                            
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="form-group row m-b-10">
                                            <div class="col-12">
                                                <p><a href="javascript:;"  id="loginBtn" class="btn btn-rounded btn-primary m-b-20 waves-effect waves-light d-block">登录</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="form-group row m-b-20">
                                    <div class="col-6">
                                        <div class="pl-3 pt-1 pb-3 pl-3"> <a class="font-14 text-primary" href="./agent_Login_register.html">商户注册</a>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                        <div class="col-6" style="text-align: right;padding-right:2rem;"> <a href="./agent_Login_forgetpwd.html" class="font-14 text-primary">忘记密码?</a> </div>
                                    </div>
 </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="text-center mt-5">
                            <ul class="social-network">
                                <li>商户请在此处→ <a class="font-14 text-primary" href="/"  title="登录">登录</a></li>
                                    
                            </ul>
                        </div>
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
    layui.use(['laydate', 'form', 'layer', 'table', 'element'], function() {
      layer = layui.layer; //弹层

        $("#loginBtn").click(function () {

            var username = $("#username").val();
            var password = $("#password").val();
            var varification = $("#captcha").val();


            username = $.trim(username);
            password = $.trim(password);
            varification = $.trim(varification);

            if (username.length < 1) {

                layer.msg('请输入正确格式的用户名');
                return false;

            }
            else if (password.length < 6) {
                layer.msg('请输入正确格式的密码');
                return false;

            }
            else if (varification == '') {
                layer.msg('请输入正确格式的验证码');
                return false;

            }

            $.ajax({
                type:'post',
                url:'user_Login_checklogin.html',
                data: { username: username, password: password, varification:varification},
                dataType:'json',
                success:function(result){
                    if(result['status'] == 0){
                        layer.msg(result['info']);
                        if(result['info'] == '验证码输入有误！') {
                            $('#captcha').val('');
                            $('#captcha').focus();
                        }
                    } else {
                        layer.msg('登录成功，正在跳转到代理管理中心');
                        setTimeout(function() {
                            window.location.href = "<?php echo ($siteurl); ?>" + "agent_Index_index.html";
                        },3000 );


                    }
                }
            })
        })
        $(document).keyup(function(event){
            if(event.keyCode ==13){
                $("#loginBtn").trigger("click");
            }
        });
    });
    function reimg() {
        var img = document.getElementById("vercodeImg");
        img.src = "./agent_Login_verifycode.html?csrf_token=" + Math.random();
    }
</script>
</body>
</html>