<?php
/**
 * Created by PhpStorm.
 * User: acer
 * Date: 2018/2/14 0014
 * Time: 16:09
 */
require_once 'utils.php';
$content = file_get_contents("php://input");
parse_str($content,$data);
foreach ($data as $key => $value){
    $params[$key] = $value;
}
unset($params['sign']);
unset($params['signType']);
$str = utils::Sign($params,$apikey);
$sign = $_POST['sign'];
if ($sign == $str){
    echo "success";
} else {
    echo "fail";
}