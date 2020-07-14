<?php
 
 
 

header("Content-type: text/html; charset=utf-8");
 
$tjurl = "http://www.jiufupay.cn/apisubmit";   //�����ύ��ַ
 
$APPKey = "e0fceee10ee3187e8cbb96cddc1f22ca153aec5b"; 
$version="1.0";
$customerid="10885";
$sdorderno=date('YmdHis') . mt_rand(100000,999999);
$total_fee=100.22;
$paytype="alipaywap";
$notifyurl= "http://".$_SERVER["HTTP_HOST"]."/3back.asp";
$returnurl= "http://".$_SERVER["HTTP_HOST"]."/3back.asp";
 

 
$sign = md5("version=".$version."&customerid=".$customerid."&total_fee=".$total_fee."&sdorderno=".$sdorderno."&notifyurl=".$notifyurl."&returnurl=".$returnurl."&".$APPKey."");


$jsapi["version"] = $version; //ͨ��
$jsapi["customerid"] = $customerid; //ͨ��
$jsapi["total_fee"] = $total_fee; //ͨ��
$jsapi["sdorderno"] = $sdorderno; //ͨ��
$jsapi["notifyurl"] = $notifyurl; //ͨ��
 $jsapi["returnurl"] = $returnurl; //ͨ��
$jsapi["sign"] = $sign;
$jsapi["paytype"] = $paytype; //ͨ��
 

?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!--[if lt IE 9]>
    <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row" style="margin:15px;0;">
        <div class="col-md-12">

            <form class="form-inline" id="pay" name="pay" method="get" action="<?php echo $tjurl; ?>">
                <?php
                foreach ($jsapi as $key => $val) {
                    echo '<input type="hidden" name="' . $key . '" value="' . $val . '">';
                }
                ?>
                
            </form>
			 <script type="text/javascript">
      function tijiao() {
      document.getElementById("pay").submit();
      }
      tijiao();
    </script>
        </div>
    </div>

</div>
 
</body>
</html>  