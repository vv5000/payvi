<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
 <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title><?php echo ($sitename); ?>---管理</title>
    <link rel="shortcut icon" href="favicon.ico">
    <link href="/Public/Front/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/Front/css/font-awesome.min.css" rel="stylesheet">
    <link href="/Public/Front/css/animate.css" rel="stylesheet">
    <link href="/Public/Front/css/style.css" rel="stylesheet">
      <link href="/Public/Front/css/zuy.css" rel="stylesheet">
    <link rel="stylesheet" href="/Public/Front/js/plugins/layui/css/layui.css">
    <link rel="stylesheet" type="text/css" href="/Public/Front/iconfont/iconfont.css"/>
<style>
        .layui-form-label {width:110px;padding:4px}
        .layui-form-item .layui-form-checkbox[lay-skin="primary"]{margin-top:0;}
        .layui-form-switch {width:54px;margin-top:0px;}
    </style>
<body class="gray-bg">
<div class="wrapper wrapper-content animated">


<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <!--条件查询-->
            <div class="ibox-title">
                <h5>提款管理</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->           <div class="ibox-content">
          <br>  <br>
                <div class="layui-tab">
                    <ul class="layui-tab-title">
                        <li class="layui-this">提款设置</li>
                        <li>提款时间设置</li>
                        <li>提款节假日设置</li>
                        <li>自动代付设置</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" action="" id="profile">
	<input type="hidden" name="id" value="<?php echo ($configs['id']); ?>">
	<input type="hidden" name="u[issystem]" value="1">
	<div class="layui-form-item">
		<label class="layui-form-label">单笔最小金额：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[tkzxmoney]" autocomplete="off" value="<?php echo ($configs["tkzxmoney"]); ?>"
				   placeholder="" class="layui-input">
		</div>
		<label class="layui-form-label">单笔最大金额：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[tkzdmoney]" autocomplete="off" value="<?php echo ($configs["tkzdmoney"]); ?>"
				   placeholder="" class="layui-input">
		</div>
	</div>

	<div class="layui-form-item">
		<label class="layui-form-label">当日总金额：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[dayzdmoney]" autocomplete="off" value="<?php echo ($configs["dayzdmoney"]); ?>"
				   placeholder="0.00" class="layui-input">
		</div>
		<label class="layui-form-label">当日总次数：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[dayzdnum]" autocomplete="off" value="<?php echo ($configs["dayzdnum"]); ?>"
				   placeholder="" class="layui-input">
		</div>
	</div>

	<div class="layui-form-item">
		<label class="layui-form-label">单人单卡单日最高提现额：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[daycardzdmoney]" autocomplete="off" value="<?php echo ($configs["daycardzdmoney"]); ?>"
				   placeholder="0.00" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">结算方式：</label>
		<div class="layui-input-block">
			<input type="radio" name="u[t1zt]" <?php if($configs[t1zt] == 0): ?>checked<?php endif; ?> value="0" title="D+0"
			checked="">
			<input type="radio" name="u[t1zt]" <?php if($configs[t1zt] == 1): ?>checked<?php endif; ?> value="1" title="T+1">
			<input type="radio" name="u[t1zt]" <?php if($configs[t1zt] == 7): ?>checked<?php endif; ?> value="7" title="T+7（每周一允许结算）">
			<input type="radio" name="u[t1zt]" <?php if($configs[t1zt] == 30): ?>checked<?php endif; ?> value="30" title="T+30（每月第一天允许结算）">
		</div>
	</div>

	<!--<div class="layui-form-item">
		<label class="layui-form-label">结算方式：</label>
		<div class="layui-input-block">
			<input type="radio" name="u[t0zt]" <?php if($configs[t0zt] == 0): ?>checked<?php endif; ?> value="0" title="关闭" checked="">
			<input type="radio" name="u[t0zt]" <?php if($configs[t0zt] == 1): ?>checked<?php endif; ?> value="1" title="开通">
		</div>
	</div>-->
<!-- 	<div class="layui-form-item">
		<label class="layui-form-label">购买T+0金额：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[gmt0]" autocomplete="off" value="<?php echo ($configs["gmt0"]); ?>"
				   placeholder="0.00" class="layui-input">
		</div>
	</div> -->

	<div class="layui-form-item">
		<label class="layui-form-label">手续费类型：</label>
		<div class="layui-input-inline">
			<select name="u[tktype]" lay-verify="required" lay-search="">
				<option value=""></option>
				<option <?php if($configs['tktype'] == 0): ?>selected<?php endif; ?> value="0">按比例计算</option>
				<option <?php if($configs['tktype'] == 1): ?>selected<?php endif; ?> value="1">按单笔计算</option>
			</select>
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">单笔提款比例（%）：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" step="0.1" name="u[sxfrate]" autocomplete="off"
				   value="<?php echo ($configs["sxfrate"]); ?>" placeholder="%" class="layui-input">
		</div>
		<label class="layui-form-label">单笔提款收取（元）：</label>
		<div class="layui-input-inline">
			<input type="number" min="0" name="u[sxffixed]" autocomplete="off" value="<?php echo ($configs["sxffixed"]); ?>"
				   placeholder="0.00" class="layui-input">
		</div>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">手续费扣除方式：</label>
		<div class="layui-input-block">
			<input type="radio" name="u[tk_charge_type]" value="0" title="从到账金额扣" <?php if($configs['tk_charge_type'] == 0): ?>checked<?php endif; ?>>
		</div>
		<input type="radio" name="u[tk_charge_type]" value="1" title="从商户余额扣" <?php if($configs['tk_charge_type'] == 1): ?>checked<?php endif; ?>>
	</div>
	<div class="layui-form-item">
		<label class="layui-form-label">提款状态：</label>
		<div class="layui-input-block">
			<input type="radio" name="u[tkzt]" <?php if($configs[tkzt] == 0): ?>checked<?php endif; ?> value="0" title="关闭" checked="">
			<input type="radio" name="u[tkzt]" <?php if($configs[tkzt] == 1): ?>checked<?php endif; ?> value="1" title="开通">
		</div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="submit" lay-filter="save">提交保存</button>
		</div>
	</div>
</form>
                        </div>
                        <div class="layui-tab-item">
                            <form class="layui-form" action="" id="settime">
	<input type="hidden" name="id" value="<?php echo ($configs['id']); ?>">	
	<div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">提款时间：</label>
      <div class="layui-input-inline">
      <select name="u[allowstart]">
      <option <?php if($configs['allowstart'] == 0): ?>selected<?php endif; ?> value="0">0:00</option>
	  <option <?php if($configs['allowstart'] == 1): ?>selected<?php endif; ?> value="1">1:00</option>
	  <option <?php if($configs['allowstart'] == 2): ?>selected<?php endif; ?> value="2">2:00</option>
	  <option <?php if($configs['allowstart'] == 3): ?>selected<?php endif; ?> value="3">3:00</option>
	  <option <?php if($configs['allowstart'] == 4): ?>selected<?php endif; ?> value="4">4:00</option>
	  <option <?php if($configs['allowstart'] == 5): ?>selected<?php endif; ?> value="5">5:00</option>
	  <option <?php if($configs['allowstart'] == 6): ?>selected<?php endif; ?> value="6">6:00</option>
	  <option <?php if($configs['allowstart'] == 7): ?>selected<?php endif; ?> value="7">7:00</option>
	  <option <?php if($configs['allowstart'] == 8): ?>selected<?php endif; ?> value="8">8:00</option>
	  <option <?php if($configs['allowstart'] == 9): ?>selected<?php endif; ?> value="9">9:00</option>
	  <option <?php if($configs['allowstart'] == 10): ?>selected<?php endif; ?> value="10">10:00</option>
	  <option <?php if($configs['allowstart'] == 11): ?>selected<?php endif; ?> value="11">11:00</option>
	  <option <?php if($configs['allowstart'] == 12): ?>selected<?php endif; ?> value="12">12:00</option>
	  <option <?php if($configs['allowstart'] == 13): ?>selected<?php endif; ?> value="13">13:00</option>
	  <option <?php if($configs['allowstart'] == 14): ?>selected<?php endif; ?> value="14">14:00</option>
	  <option <?php if($configs['allowstart'] == 15): ?>selected<?php endif; ?> value="15">15:00</option>
	  <option <?php if($configs['allowstart'] == 16): ?>selected<?php endif; ?> value="16">16:00</option>
	  <option <?php if($configs['allowstart'] == 17): ?>selected<?php endif; ?> value="17">17:00</option>
	  <option <?php if($configs['allowstart'] == 18): ?>selected<?php endif; ?> value="18">18:00</option>
	  <option <?php if($configs['allowstart'] == 19): ?>selected<?php endif; ?> value="19">19:00</option>
	  <option <?php if($configs['allowstart'] == 20): ?>selected<?php endif; ?> value="20">20:00</option>
	  <option <?php if($configs['allowstart'] == 21): ?>selected<?php endif; ?> value="21">21:00</option>
	  <option <?php if($configs['allowstart'] == 22): ?>selected<?php endif; ?> value="22">22:00</option>
	  <option <?php if($configs['allowstart'] == 23): ?>selected<?php endif; ?> value="23">23:00</option>
      </select>
    </div>
      <div class="layui-form-mid">-</div>
      <div class="layui-input-inline">
      <select name="u[allowend]">
      <option <?php if($configs['allowend'] == 0): ?>selected<?php endif; ?> value="0">0:00</option>
	  <option <?php if($configs['allowend'] == 1): ?>selected<?php endif; ?> value="1">1:00</option>
	  <option <?php if($configs['allowend'] == 2): ?>selected<?php endif; ?> value="2">2:00</option>
	  <option <?php if($configs['allowend'] == 3): ?>selected<?php endif; ?> value="3">3:00</option>
	  <option <?php if($configs['allowend'] == 4): ?>selected<?php endif; ?> value="4">4:00</option>
	  <option <?php if($configs['allowend'] == 5): ?>selected<?php endif; ?> value="5">5:00</option>
	  <option <?php if($configs['allowend'] == 6): ?>selected<?php endif; ?> value="6">6:00</option>
	  <option <?php if($configs['allowend'] == 7): ?>selected<?php endif; ?> value="7">7:00</option>
	  <option <?php if($configs['allowend'] == 8): ?>selected<?php endif; ?> value="8">8:00</option>
	  <option <?php if($configs['allowend'] == 9): ?>selected<?php endif; ?> value="9">9:00</option>
	  <option <?php if($configs['allowend'] == 10): ?>selected<?php endif; ?> value="10">10:00</option>
	  <option <?php if($configs['allowend'] == 11): ?>selected<?php endif; ?> value="11">11:00</option>
	  <option <?php if($configs['allowend'] == 12): ?>selected<?php endif; ?> value="12">12:00</option>
	  <option <?php if($configs['allowend'] == 13): ?>selected<?php endif; ?> value="13">13:00</option>
	  <option <?php if($configs['allowend'] == 14): ?>selected<?php endif; ?> value="14">14:00</option>
	  <option <?php if($configs['allowend'] == 15): ?>selected<?php endif; ?> value="15">15:00</option>
	  <option <?php if($configs['allowend'] == 16): ?>selected<?php endif; ?> value="16">16:00</option>
	  <option <?php if($configs['allowend'] == 17): ?>selected<?php endif; ?> value="17">17:00</option>
	  <option <?php if($configs['allowend'] == 18): ?>selected<?php endif; ?> value="18">18:00</option>
	  <option <?php if($configs['allowend'] == 19): ?>selected<?php endif; ?> value="19">19:00</option>
	  <option <?php if($configs['allowend'] == 20): ?>selected<?php endif; ?> value="20">20:00</option>
	  <option <?php if($configs['allowend'] == 21): ?>selected<?php endif; ?> value="21">21:00</option>
	  <option <?php if($configs['allowend'] == 22): ?>selected<?php endif; ?> value="22">22:00</option>
	  <option <?php if($configs['allowend'] == 23): ?>selected<?php endif; ?> value="23">23:00</option>
      </select>
    </div>
    </div>
	</div>
	<div class="layui-form-item">
		<div class="layui-input-block">
			<button class="layui-btn" lay-submit="submit" lay-filter="time">提交保存</button>
		</div>
	</div>
</form>
                        </div>
                        <div class="layui-tab-item">
                            	<p class="text-danger"><strong>(这里可以排除指定的日期为节假日)</strong></p>
	
	<div class="layui-form">
	<div class="layui-form-item">
	<div class="layui-inline">
      <label class="layui-form-label">添加排除日期：</label>
      <div class="layui-input-inline">
        <div id="setholiday" style="height: 38px; line-height: 38px; cursor: pointer; border-bottom: 1px solid #e2e2e2;"></div>
      </div>
    </div>
	</div>
	</div>
	<div class="layui-form" id="setdatebox">
		<?php if(is_array($holidays)): $i = 0; $__LIST__ = $holidays;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><button class="layui-btn layui-btn-small layui-btn-danger" onclick="del_btn(this,<?php echo ($vo["id"]); ?>)"><?php echo (date('Y-m-d',$vo["datetime"])); ?> &nbsp;&nbsp;<i class="layui-icon"></i> 删除</button><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
                        </div>
                        <div class="layui-tab-item">
                            <form class="layui-form" action="" id="autodf">
		<input type="hidden" name="id" value="<?php echo ($configs['id']); ?>">
		<input type="hidden" name="u[issystem]" value="1">
		<div class="layui-form-item">
			<label class="layui-form-label">开关：</label>
			<div class="layui-input-block">
				<input type="radio" name="u[auto_df_switch]" lay-filter="autoDfSwitch" <?php if($configs[auto_df_switch] == 0): ?>checked<?php endif; ?> value="0" title="关" checked="">
				<input type="radio" name="u[auto_df_switch]" lay-filter="autoDfSwitch" <?php if($configs[auto_df_switch] == 1): ?>checked<?php endif; ?> value="1" title="开">
			</div>
		</div>
		<div id="settingAutoDf" style="display:<?php if(!$configs[auto_df_switch]): ?>none<?php endif; ?>;">
		<div class="layui-form-item">
			<label class="layui-form-label">开始时间：</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input" name="u[auto_df_stime]" id="auto_df_stime"
					   placeholder="自动代付开始时间" value="<?php echo ($configs["auto_df_stime"]); ?>" lay-verify="required" >
			</div>
			<label class="layui-form-label">结束时间：</label>
			<div class="layui-input-inline">
				<input type="text" class="layui-input" name="u[auto_df_etime]" id="auto_df_etime"
				placeholder="自动代付结束时间" value="<?php echo ($configs["auto_df_etime"]); ?>" lay-verify="required" >
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">最大金额：</label>
			<div class="layui-input-inline">
				<input type="number" min="0" name="u[auto_df_maxmoney]" autocomplete="off" value="<?php echo ($configs["auto_df_maxmoney"]); ?>"
					   placeholder="单笔最大金额限制，填0表示不限制" class="layui-input" style="width: 250px;"lay-verify="required" >
				<P>单笔最大金额限制，填0表示不限制</P>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商户每天自动代付笔数限制：</label>
			<div class="layui-input-inline">
				<input type="number" min="0" name="u[auto_df_max_count]" autocomplete="off" value="<?php echo ($configs["auto_df_max_count"]); ?>"
					   placeholder="商户每天自动代付笔数限制，填0表示不限制" class="layui-input" style="width: 250px;"lay-verify="required" >
				<P>商户每天自动代付笔数限制，填0表示不限制</P>
			</div>
		</div>
		<div class="layui-form-item">
			<label class="layui-form-label">商户每天自动代付最大总金额限制：</label>
			<div class="layui-input-inline">
				<input type="number" min="0" name="u[auto_df_max_sum]" autocomplete="off" value="<?php echo ($configs["auto_df_max_sum"]); ?>"
					   placeholder="商户每天自动代付最大总金额限制，填0表示不限制" class="layui-input" style="width: 250px;"lay-verify="required" >
				<P>商户每天自动代付最大总金额限制，填0表示不限制</P>
			</div>
		</div>
</div>
<div class="layui-form-item">
	<div class="layui-input-block">
		<button class="layui-btn" lay-submit="submit" lay-filter="autodf">提交保存</button>
	</div>
</div>
</form>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<script src="/Public/Front/js/jquery.min.js"></script>
<script src="/Public/Front/js/bootstrap.min.js"></script>
<script src="/Public/Front/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/Public/Front/js/content.js"></script>
<script src="/Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/x-layui.js" charset="utf-8"></script>
    <script>
        layui.use(['layer', 'form', 'laydate','element'], function () {
            var form = layui.form
                ,$ = layui.jquery
                , laydate = layui.laydate
                ,element = layui.element
                , layer = layui.layer;
                laydate.render({
                    elem: '#auto_df_stime'
                    , type: 'time'
                    ,theme: 'molv'
                    ,format: 'HH:mm'
                });
                laydate.render({
                    elem: '#auto_df_etime'
                    , type: 'time'
                    ,theme: 'molv'
                    ,format: 'HH:mm'
                });
            //监听radio
            form.on('radio(changeRule)', function (data) {
                //console.log(data.elem); //得到radio原始DOM对象
                //console.log(data.value); //被点击的radio的value值
                if (data.value == 1) {
                    $('#changeRule').css('display', '');
                } else if (data.value == 0) {
                    $('#changeRule').css('display', 'none');
                }
            });
            //监听radio
            form.on('radio(autoDfSwitch)', function(data){
                if(data.value==1){
                    $('#settingAutoDf').css('display','');
                }else if(data.value==0){
                    $('#settingAutoDf').css('display','none');
                }
            });
            //监听提交
            form.on('submit(save)', function (data) {
                $.ajax({
                    url: "<?php echo U('Withdrawal/saveWithdrawal');?>",
                    type: "post",
                    data: $('#profile').serialize(),
                    success: function (res) {
                        if (res.status) {
                            layer.alert("编辑成功", {icon: 6}, function () {
                                location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        }else{
                            layer.alert("操作失败", {icon: 5}, function () {
                                location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        }
                    }
                });
                return false;
            });

            //选中后的回调
            laydate.render({
                elem: '#setholiday'
                ,done: function(value, date){
                    $.ajax({
                        url: "<?php echo U('Withdrawal/addHoliday');?>",
                        type: "post",
                        data: "datetime="+value,
                        success: function (res) {
                            if (res.status) {
                                layer.msg("编辑成功", {icon: 6}, function () {
                                    location.reload();
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.layer.close(index);
                                });
                            }else{
                                layer.msg(res.msg, {icon: 5}, function () {
                                    location.reload();
                                    var index = parent.layer.getFrameIndex(window.name);
                                    parent.layer.close(index);
                                });
                            }
                        }
                    });
                }
            });

            //监听时间
            form.on('submit(time)', function (data) {
                $.ajax({
                    url: "<?php echo U('Withdrawal/settimeEdit');?>",
                    type: "post",
                    data: $('#settime').serialize(),
                    success: function (res) {
                        if (res.status) {
                            layer.msg("编辑成功", {icon: 6}, function () {
                                location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg("操作失败", {icon: 5}, function () {
                                location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        }
                    }
                });
                return false;
            });
            //监听自动代付设置
            form.on('submit(autodf)', function (data) {
                $.ajax({
                    url: "<?php echo U('Withdrawal/autoDfEdit');?>",
                    type: "post",
                    data: $('#autodf').serialize(),
                    success: function (res) {
                        if (res.status) {
                            layer.msg("编辑成功", {icon: 6}, function () {
                                location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        }else{
                            layer.msg("操作失败", {icon: 5}, function () {
                                location.reload();
                                var index = parent.layer.getFrameIndex(window.name);
                                parent.layer.close(index);
                            });
                        }
                    }
                });
                return false;
            });
        });
        //监听button
        function del_btn(obj,id){
            console.log(obj);
            $.ajax({
                url: "<?php echo U('Withdrawal/delHoliday');?>",
                type: "post",
                data: "id="+id,
                success: function (res) {
                    if (res.status) {
                        layer.msg("删除成功", {icon: 6}, function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                        $(obj).remove();
                    }else{
                        layer.msg(res.msg, {icon: 5}, function () {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                        });
                    }
                }
            });
        }
    </script>
    </body>
    </html>