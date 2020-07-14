<?php
require_once 'utils.php';
require_once 'config.php';
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2018/2/26 0026
 * Time: 15:17
 */
$params = [
    "merchantId" => $merchantId,
    "batchVersion" => "00",
    "charset" => "utf8"
];
$params['batchDate'] = $_POST['batchDate'];
$params['batchNo'] = $_POST['batchNo'];
$key = $apikey;
$baseUri = $_POST["baseUri"].'/agentPay/v1/batch/' . $params['merchantId'] . '-' . $params['batchNo'];
$params['sign'] = utils::Sign($params,$apiKey);
$params['signType'] = "SHA";
$HtmlStr = utils::getHtml($baseUri, $params);
var_dump($HtmlStr);