<?php
class alipay{
    public $userid;
    public $userkey;
    private $service='alipay.wap.create.direct.pay.by.user';
    private $charset='UTF-8';
    private $sign_type='MD5';

    public function submitOrder($data){
        $data['service']=$this->service;
        $data['partner']=$this->userid;
        $data['seller_id']=$this->userid;
        $data['_input_charset']=$this->charset;
        $data['sign_type']=$this->sign_type;
        $data['payment_type']=1;
        $data['it_b_pay']='';
        $data['extern_token']='';
        $data['otherfee']='';
        $data['airticket']='';
        $data['rn_check']='T';
        $data['buyer_cert_no']='';
        $data['buyer_real_name']='';
        $data['scene']='';
        $data['hb_fq_param']='';
        $data['goods_type']='';
        $data['app_pay']='Y';
        $data['promo_params']='';
        $data['sign']=$this->makeSign($data);
        $url='https://mapi.alipay.com/gateway.do';

        $html='<!doctype html><html><head><title>正在跳转...</title></head><body onload="document.pay.submit()"><form name="pay" action="'.$url.'" method="post">';
        foreach($data as $key=>$val){
           if( $val )
			{
				 $html.="\r\n".'<input type="hidden" name="'.$key.'" value="'.$val.'">';
			}
        }
        $html.='</form></body></html>';
        return $html;
    }

    public function isNotify(){
        $data=isset($_POST) ? $_POST : false;
        if($data && $data['sign']==$this->makeSign($data) ){ //&& $this->verifyNotify($data['notify_id'])
            if($data['trade_status']=='TRADE_FINISHED' || $data['trade_status']=='TRADE_SUCCESS'){
                return array('orderid'=>$data['out_trade_no'],'total_fee'=>$data['total_fee']);
            }
        }
        return false;
    }

    public function isReturn(){
        $data=isset($_GET) ? $_GET : false;
        if($data && $data['sign']==$this->makeSign($data)){
            if($data['trade_status']=='TRADE_FINISHED' || $data['trade_status']=='TRADE_SUCCESS'){
                return array('orderid'=>$data['out_trade_no'],'total_fee'=>$data['total_fee']);
            }
        }
        return false;
    }

    public function makeSign($data){
        //$this->logs('sign',$data);

        ksort($data);
        $str='';
        foreach($data as $key=>$val){
            if($key!='sign' && $key!='sign_type' && $val!==''){
                $str.=$str ? '&' : '';
                $str.=$key.'='.$val;
            }
        }
        return md5($str.$this->userkey);
    }

    public function verifyNotify($notify_id){
        $url='https://mapi.alipay.com/gateway.do?service=notify_verify&partner='.$this->userid.'&notify_id='.$notify_id;
        $responseTxt=$this->getHttpResponseGET($url);
        if (preg_match("/true$/i",$responseTxt)) {
            return true;
        }
        return false;
    }

    function getHttpResponseGET($url,$cacert_url='') {
    	$curl = curl_init($url);
    	curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
    	curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//SSL证书认证
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);//严格认证
    	//curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
    	$responseText = curl_exec($curl);
    	//var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
    	curl_close($curl);

    	return $responseText;
    }

    public function logs($title,$data){
        $handler = fopen('result.txt','a+');
        $content = "================".$title."===================\n";
        if(is_string($data) === true){
            $content .= $data."\n";
        }
        if(is_array($data) === true){
            forEach($data as $k=>$v){
                $content .= "key: ".$k." value: ".$v."\n";
            }
        }
        $flag = fwrite($handler,$content);
        fclose($handler);
    }
}
?>
