<?php
include_once 'PayService.php';
header('Content-type:text/html; Charset=utf-8');
$Pay = new PayService();
$data = $_POST;
$outTradeNo = date('Ydmhis').rand();  //你自己的商品订单号
$payAmount = empty($data['amount']) ? 100 : $data['amount'];                    //付款金额，单位:元
$orderName = '支付测试';               //订单标题
$payType = empty($data['code']) ? 'bank' : $data['code'];
$returnUrl = 'https://www.jiufupay.cn/test/Return.php';     //付款成功后的返回地址(不要有问号)
$notifyUrl = 'https://www.jiufupay.cn/test/Notify.php';     //付款成功后的回调地址(不要有问号)
$pay = $Pay->doPay($payType,$payAmount, $outTradeNo, $orderName, $returnUrl, $notifyUrl);
echo json_encode($pay);