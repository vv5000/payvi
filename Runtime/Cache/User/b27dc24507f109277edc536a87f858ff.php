<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?php echo ($sitename); ?> - 谷歌身份验证</title>
    <link href="<?php echo ($siteurl); ?>Public/Front/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo ($siteurl); ?>Public/Front/css/font-awesome.css" rel="stylesheet">
    <link rel="stylesheet" src="<?php echo ($siteurl); ?>Public/Front/bootstrapvalidator/css/bootstrapValidator.min.css">
    <link href="<?php echo ($siteurl); ?>Public/Front/css/animate.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo ($siteurl); ?>Public/Front/login/css/style.css">
    <script src="<?php echo ($siteurl); ?>Public/Front/login/js/modernizr-2.6.2.min.js"></script>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html"/>
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            window.top.location = window.location;
        }
    </script>
</head>
<body class="style-3">

<div class="container">
    <div class="row col-hidden-xs">
        <div class="mt-50"></div>
    </div>
    <div class="row">
        <div class="col-md-4 col-md-push-8">

            <form class="fh5co-form animate-box form-horizontal" data-animate-effect="fadeInRight" id="formlogin"
                  method="post" role="form" action="<?php echo U('User/Index/google');?>">
            <div class="form-group"><h2>谷歌身份验证器二次验证</h2>  </div>
            <div class="form-group">
                <label for="code" class="sr-only">谷歌安全码</label>
                <input type="text" name="code" class="form-control" id="code" name="text" required="" aria-required="true" autocomplete="off" placeholder="谷歌安全码">
            </div>
            <div class="form-group">
                <span class="col-sm-4 mp-nm"><input type="text" class="form-control" id="verification" name="captcha_code"  placeholder="图形验证码" required=""  aria-required="true" placeholder="Password" autocomplete="off" ajaxurl="<?php echo U("Login/checkverify");?>"></span>
                <label class="userverification col-sm-8"><img class="verifyimg" alt="点击刷新验证码" src="<?php echo U('Login/verifycode');?>?t=<?php echo time();?>" style="cursor:pointer;" onclick='getCode()' title="点击刷新验证码"></label>

            </div>

            <div class="form-group">
                <p>找不到谷歌安全码? 点击 <a href="<?php echo U('User/Account/unbindGoogle');?>">解绑谷歌身份验证器</a>></p>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">提交</button>
            </div>
            </form>
            <!-- END Sign In Form -->
        </div>
    </div>
    <div class="row" style="padding-top: 60px; clear: both;">
        <div class="col-md-12 text-center"><p><small>&copy; <?php echo ($sitename); ?> All Rights Reserved.  </small></p></div>
    </div>
</div>
<script src="<?php echo ($siteurl); ?>Public/Front/js/jquery.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/js/bootstrap.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/bootstrapvalidator/js/bootstrapValidator.min.js"></script>

<?php echo tongji(0);?>
</body>
</html>
<script type="text/javascript">
    function getCode(){
        $(".verifyimg").attr("src","<?php echo U('Login/verifycode',array(), false);?>?a=" + Math.random());
    }
</script>