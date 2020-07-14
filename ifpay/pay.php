<?php
require_once 'utils.php';
require_once 'config.php';
$date = date("Ymd");
$orderNo = 'order' . $date . rand();
$params['orderNo'] = $orderNo;      //商户订单号，务必确保在系统中唯一，必填
$params['totalFee'] = $_POST["totalFee"];               //订单金额，单位为RMB元，必填
$params['defaultbank'] = $_POST["defaultbank"];         //网银代码，当支付方式为bankPay时，该值为空；支付方式为directPay时该值必传
$params['title'] = 'test';                              //商品的名称，请勿包含字符，选填
$params['paymethod'] = $_POST['paymethod'];             //支付方式，directPay：直连模式；bankPay：收银台模式，必填
$params['service'] = "online_pay";                      //固定值online_pay，表示网上支付，必填
$params['paymentType'] = "1";                           //支付类型，固定值为1，必填
$params['merchantId'] = $merchantId;                    //支付平台分配的商户ID，必填
$params['returnUrl'] = "https://www.baidu.com";         //支付成功跳转URL，仅适用于支付成功后立即返回商户界面，必填
$params['notifyUrl'] = "https://pay.365cpb.com/ifpay/notice.php";    //商户支付成功后，该地址将收到支付成功的异步通知信息，该地址收到的异步通知作为发货依据，必填
$params['charset'] = "utf-8";                           //参数编码字符集，必填
$params['body'] = "test";                               //商品的具体描述，选填
    $isApp = $_POST["isApp"];
    if ($isApp == 'web') {
        $params['isApp'] = "web";
    }
    if ($isApp == 'app') {
        $params['isApp'] = "app";
    }
    if ($isApp == 'H5') {
        $params['isApp'] = "h5";
        $params['appName'] = "h5";
        $params['appMsg'] = "h5";
        $params['appType'] = "Android";
        $params['userIp'] = "127.0.0.1";
        $params['backUrl'] = "https://www.baidu.com";
    }
$baseUri = $_POST["baseUri"].'/payment/v1/order/'.$merchantId.'-'.$orderNo;
$params['sign'] = utils::Sign($params,$apiKey);
$params['signType'] = "SHA";//signType不参与加密，所以要放在最后
$HtmlStr = utils::postHtml($baseUri, $params);
var_dump($HtmlStr);