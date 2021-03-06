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
    <title>找回密码 - <?php echo ($sitename); ?></title>
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
   <!-- <div class="navbar-header mt-5 ml-5"></div> -->
    <div class="container">
        <div class="page-wrapper m-0 pt-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-4">
                        <h2 class="font-25 font-medium" style="text-align: center;padding-bottom: 20px;padding-top: 100px;"><?php echo ($sitename); ?> - 找回密码</h2>
                        <div class="card border-0">
                            <div class="card-body p-0">
                               <form class="form bordered-input" id="formlogin" name="formlogin" method="post" role="form" action="<?php echo U("Login/forgetpwd");?>">
                                    <div class="p-30 pb-0">
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="username" id="username" class="form-control pl-0 font-12" type="text" placeholder="请输入用户名" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="email" id="email"  class="form-control pl-0 font-12" type="text" placeholder="邮箱" required="" minlength="2" aria-required="true" autocomplete="off">
                                            </div>
                                        </div>
                                       <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="password" id="password"  class="form-control pl-0 font-12" type="text" placeholder="新密码" autocomplete="off">
                                            </div>
                                        </div>
                                        <div class="form-group m-t-20 row">
                                            <div class="col-12">
                                                <input name="confirmpassword" id="confirmpassword"  class="form-control pl-0 font-12" type="password" placeholder="再次输入密码" autocomplete="off">
                                            </div>
                                        </div>

                                       <div class="form-group m-t-20 row">
                                            <div class="col-6">
                                                <input name="varification" id="verification" class="form-control pl-0 font-12" type="text" placeholder="验证码" required=""  aria-required="true">
                                            </div>
                                            <div class="col-6">
                                                <button type="button" class="layui-btn layui-btn-warm" id="btnGet">发送验证码</button>
                                            </div>
                                        </div>
   

                                        <div class="clearfix"></div>
                                    <div class="form-group row m-b-10">
                                            <div class="col-12">
                                                <p><a href="javascript:;"  id="loginBtn" class="btn btn-rounded btn-primary m-b-20 waves-effect waves-light d-block">提交</a></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                   <div class="clearfix"></div>
                                    <div class="pl-3 pt-1 pb-3 pl-3"> 记得账号密码？ <a class="font-14 text-primary" href="/">立即登录</a>
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
  
  

<script src="Public/Front/bootstrapvalidator/js/bootstrapValidator.min.js"></script>
<script src="Public/Front/js/plugins/zxcvbn/4.4.2/zxcvbn.js"></script>
<script src="Public/Front/js/plugins/layer/layer.min.js"></script>
<script src="Public/Front/login/js/modernizr-2.6.2.min.js"></script>
<script>
    var isSend=false;
    $("#btnGet").on("click",function(){
        if(isSend)
            return;
        var obj=this;
        var username=$("#username").val();
        var email=$("#email").val();
        if(username=="")
        {
            layer.msg('请输入用户名！');
            return ;
        }
        if(email=="")
        {
            layer.msg('请输入邮箱！');
            return ;
        }
        isSend=true;
        $.ajax({
            url:"<?php echo U('User/Login/sendUserCode');?>",
            type:"post",
            data:{'username':username,'email':email},
            success:function(res){
                if(res.status){
                    settime(obj)
                    isSend=true;
                    layer.alert("发送成功", {icon: 6});
                }else{
                    isSend=false;
                    layer.msg(res.msg ? res.msg : "操作失败!", {icon: 5}
                    );
                    return false;
                }

            }
        });

    });
    //短信后倒计时
    var countdown=60;
    function settime(obj) {
        if (countdown == 0) {
            $(obj).attr("disabled",false);
            isSend=false;
            $(obj).html("获取验证码");
            countdown = 60;
            return;
        } else {
            $(obj).attr("disabled", true);
            isSend=true;
            $(obj).html("重新发送(" + countdown + ")");
            countdown--;
        }
        setTimeout(function() {
                settime(obj) }
            ,1000)
    }
    $(document).ready(function() {
        $('form').bootstrapValidator({
            //container: '#messages',
            message: 'This value is not valid',
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                username: {
                    message: '<div class="col-12">用户名验证失败</div>',
                    validators: {
                        notEmpty: {
                            message: '<div class="col-12">用户名不能为空</div>'
                        },
                        threshold:6,
                        stringLength: {
                            min: 5,
                            max: 20,
                            message: '<div class="col-12">用户名长度必须在5到20之间</div>'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: '<div class="col-12">用户名由数字字母下划线和.组成</div>'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '<div class="col-12">密码不能为空</div>'
                        },
                        identical: {
                            field: 'confirmpassword',
                            message: '<div class="col-12">两次密码不一致</div>'
                        },
                        different: {
                            field: 'username',
                            message: '<div class="col-12">不能和用户名相同</div>'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: '<div class="col-12">密码长度在6到30之间</div>'
                        }
                    }
                },
                confirmpassword: {
                    message: '<div class="col-12">确认密码无效</div>',
                    validators: {
                        notEmpty: {
                            message: '<div class="col-12">确认密码不能为空</div>'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: '<div class="col-12">密码长度在6到30之间</div>'
                        },
                        identical: {
                            field: 'password',
                            message: '<div class="col-12">两次密码不一致</div>'
                        },
                        different: {
                            field: 'username',
                            message: '<div class="col-12">不能和用户名相同</div>'
                        }
                    }
                },
                email: {
                    message: '<div class="col-12">邮件验证失败</div>',
                    validators: {
                        notEmpty: {
                            message: '<div class="col-12">邮件不能为空</div>'
                        },

                        regexp:{
                            regexp:/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/,
                            message: '<div class="col-12">邮件格式有误，请核对</div>'
                        }
                    }
                }
            }
        }).on('success.form.bv', function(e) {
            e.preventDefault();
            var $form = $(e.target);
            var bv = $form.data('bootstrapValidator');
            $.post($form.attr('action'), $form.serialize(), function(res) {
                if(res.status){
                    layer.alert("修改成功", {icon: 6},function () {
                        window.location.href="<?php echo U('Login/index');?>";
                    });
                }else{
                    layer.msg(res.msg ? res.msg : "修改失败!", {icon: 5},function () {
                            window.location.reload();
                        }
                    );
                }
            }, 'json');
        });
    });
</script>

</body>
</html>