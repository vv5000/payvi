﻿<?php
error_reporting(0);
header("Content-type: text/html; charset=utf-8");	
$mchid = "200802525";  //商户后台API管理获取
$Md5key = "woh23g043gmhvl4g0x3c8rf5c4ifb9hq";//商户后台API管理获取
$_POST['mchid'] = $mchid;
if(empty($mchid)||empty($_POST['out_trade_no'])){
		die("信息不完整！");
}
$tjurl = "https://www.hzcy56.cn/Payment_Dfpay_query.html";   //提交地址
ksort($_POST);
$md5str = "";
foreach ($_POST as $key => $val) {
    $md5str = $md5str . $key . "=" . $val . "&";
}
$sign = strtoupper(md5($md5str . "key=" . $Md5key));
$param = $_POST;
$param["pays_md5sign"] = $sign;
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

</head>
<body>
<div class="container">
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">
            <form class="form-inline" id="paysform" method="post" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($param as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                <button type="submit" style='display:none;' ></button>
            </form>
        </div>
    </div>
</div>
<script>
   document.forms['paysform'].submit();
</script>
</body>
</html>
