<?php
require_once 'utils.php';
require_once 'config.php';
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2018/2/27 0027
 * Time: 9:27
 */

$customerNo = $merchantId;
$params['customerNo'] = $customerNo;
$baseUri = $_POST["baseUri"].'/search/queryBalance';
$params['sign'] = utils::Sign($params,$apiKey);
$params['signType'] = "SHA";
$HtmlStr = utils::getHtml($baseUri, $params);
var_dump($HtmlStr);