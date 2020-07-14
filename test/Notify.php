<?php
include_once 'PayService.php';
$d = file_get_contents("php://input");
file_put_contents('notify-data.log','通知成功'.json_encode($d));
header('Content-type:text/html; Charset=utf-8');
$Pay = new PayService();

$arr = $Pay->notify();
file_put_contents('notify.log','通知成功'.json_encode($arr));
echo "{'result_code':'OK','result_msg':'SUCCESS'}";
