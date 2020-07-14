<?php
require_once 'utils.php';
require_once 'config.php';
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2018/2/26 0026
 * Time: 15:16
 */
$params['orderNo'] = $_POST['orderNo'];                             //商户订单号
$params['charset'] = "utf-8";                                       //编码
$params['merchantId'] = $merchantId;                                //商户号
$baseUri = $_POST["baseUri"].'/payment/v1/order/'.$merchantId.'-'.$_POST['orderNo'];
$params['sign'] = utils::Sign($params,$apiKey);
$params['signType'] = "SHA";                                        //signType不参与加密，所以要放在最后
$HtmlStr = utils::getHtml($baseUri, $params);
var_dump($HtmlStr);