<?php
require_once 'utils.php';
require_once 'config.php';
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2018/2/24 0024
 * Time: 16:26
 */
$date = date("Ymd");
$batchNo = 'merchant' . $date . rand();
$batchDate = $date;
$batchAmount = $_POST["batchAmount"];
$params = [
    "merchantId" => $merchantId,                //商户号 ID
    "batchVersion" => "00",                     //版本号，固定值为00
    "batchBiztype" => "00000",                  //提交批次类型，默认00000
    "batchDate" => $batchDate,                  //提交日期
    "batchNo" => $batchNo,                      //批次号，不能重复
    "charset" => "utf8",                        //输入编码：GBK，UTF-8
    "batchCount" => "1",                        //总笔数
    "batchAmount" => $batchAmount               //总金额
];
$ip = utils::get_onlineip();
echo $ip;
$batchContent = [
    $_POST["bankAccount"],                      //银行账户
    $_POST["bankAccountName"],                  //开户名
    $_POST["bank"],                             //银行名称
    $_POST["bankBranch"],                       //分行
    $_POST["bankSubBranch"],                    //支行
    "私",                                       //业务类型
    $batchAmount,                               //金额
    "CNY",                                      //币种
    $_POST["province"],                         //省
    $_POST['city'],                             //市
    "13888888888",                              //手机
    "身份证",                                   //身份类型
    "123456789123456789",                       //身份证号码
    "123456",                                   //用户协议号
    $batchNo,                                   //商户订单号
    $ip                                         //备注
];
$string = "1";
foreach ($batchContent as $value) {
    $string .= ',' . $value;
}
$batchContent = $string;
$params['batchContent'] = $batchContent;
var_dump($params);
$key = $apikey;
$baseUri = $_POST["baseUri"].'/agentPay/v1/batch/' . $params['merchantId'] . '-' . $params['batchNo'];
$params['sign'] = utils::Sign($params,$apiKey);
$params['signType'] = "SHA";
$HtmlStr = utils::postHtml($baseUri, $params);
var_dump($HtmlStr);
//$html = utils::curl_post($params,$baseUri);
//var_dump($html);