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
    <div class="col-md-12">
        <div class="ibox float-e-margins">
            <!--条件查询-->
            <div class="ibox-title">
                <h5>提款管理</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->
            <div class="ibox-content">
                <form class="layui-form" action="" method="get" autocomplete="off" id="withdrawalform">
                    <input type="hidden" name="m" value="<?php echo ($model); ?>">
                    <input type="hidden" name="c" value="Withdrawal">
                    <input type="hidden" name="a" value="payment">
                    <input type="hidden" name="p" value="1">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input type="text" name="orderid" autocomplete="off" placeholder="请输入系统订单号"
                                       class="layui-input" value="<?php echo ($_GET['orderid']); ?>">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="bankfullname" autocomplete="off" placeholder="请输入开户名"
                                       class="layui-input" value="<?php echo ($_GET['bankfullname']); ?>">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" name="memberid" autocomplete="off" placeholder="请输入商户号"
                                       class="layui-input" value="<?php echo ($_GET['memberid']); ?>">
                            </div>

                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="createtime" id="createtime"
                                       placeholder="申请起始时间" value="<?php echo ($_GET['createtime']); ?>">
                            </div>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input" name="successtime" id="successtime"
                                       placeholder="打款起始时间" value="<?php echo ($_GET['successtime']); ?>">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <select name="dfid">
                                    <option value="">全部代付通道</option>
                                    <?php if(is_array($df_list)): $i = 0; $__LIST__ = $df_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($_GET[dfid] == $vo[id]): ?>selected<?php endif; ?>
                                        value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <select name="status">
                                    <option value="">全部状态</option>
                                    <option <?php if($_GET['status'] != '' && $_GET['status'] == 0): ?>selected<?php endif; ?> value="0">未处理</option>
                                    <option <?php if($_GET['status'] == 1): ?>selected<?php endif; ?> value="1">处理中</option>
                                    <option <?php if($_GET['status'] == 2): ?>selected<?php endif; ?> value="2">已打款</option>
                                    <option <?php if($_GET['status'] == 3): ?>selected<?php endif; ?> value="3">已驳回</option>
                                    <option <?php if($_GET['status'] == 4): ?>selected<?php endif; ?> value="4">待确认</option>
                                </select>
                            </div>
                            <div class="layui-input-inline">
                                <select name="T">
                                    <option value="">全部类型</option>
                                    <option <?php if($_GET['T'] != '' && $_GET[T] != '' && $_GET[T] == 0): ?>selected<?php endif; ?>
                                    value="0">T +
                                    0</option>
                                    <option <?php if($_GET[T] == 1): ?>selected<?php endif; ?> value="1">T + 1</option>
                                    <option <?php if($_GET[T] == 7): ?>selected<?php endif; ?> value="7">T + 7</option>
                                    <option <?php if($_GET[T] == 30): ?>selected<?php endif; ?> value="30">T + 30</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <button type="submit" class="layui-btn"><span
                                    class="glyphicon glyphicon-search"></span> 搜索
                            </button>
                            <a href="javascript:;" id="export" class="layui-btn layui-btn-danger"><span class="glyphicon glyphicon-export"></span> 导出数据</a>

                        </div>
                </form>
                <blockquote class="layui-elem-quote" style="font-size:14px;padding;8px;">今日代付总金额：<span class="label label-info"><?php echo ($stat["totay_total"]); ?>元</span> 今日待结算：<span class="label label-warning"><?php echo ($stat["totay_wait"]); ?>元</span> 今日成功笔数：<span class="label label-info"><?php echo ($stat["totay_success_count"]); ?></span> 今日失败笔数：<span class="label label-danger"><?php echo ($stat["totay_fail_count"]); ?></span> 今日平台手续费利润：<span class="label label-info"><?php echo ($stat["totay_profit"]); ?>元</span></blockquote>
                <blockquote class="layui-elem-quote" style="font-size:14px;padding;8px;">本月代付总金额：<span class="label label-info"><?php echo ($stat["month_total"]); ?>元</span> 本月待结算：<span class="label label-warning"><?php echo ($stat["month_wait"]); ?>元</span> 本月成功笔数：<span class="label label-info"><?php echo ($stat["month_success_count"]); ?></span> 本月失败笔数：<span class="label label-danger"><?php echo ($stat["month_fail_count"]); ?></span> 本月平台手续费利润：<span class="label label-info"><?php echo ($stat["month_profit"]); ?>元</span></blockquote>
                <blockquote class="layui-elem-quote" style="font-size:14px;padding;8px;">代付总金额：<span class="label label-info"><?php echo ($stat["total"]); ?>元</span> 待结算：<span class="label label-warning"><?php echo ($stat["total_wait"]); ?>元</span> 完成笔数：<span class="label label-info"><?php echo ($stat["total_success_count"]); ?></span> 驳回笔数：<span class="label label-danger"><?php echo ($stat["total_fail_count"]); ?></span> 平台手续费利润：<span class="label label-info"><?php echo ($stat["total_profit"]); ?>元</span></blockquote>
                <p>
                    <a href="javascript:;" id="checkAll" class="layui-btn layui-btn-sm layui-btn-danger">全选</a>
                    <a href="javascript:;" id="submitAllOrder" class="layui-btn layui-btn-sm layui-btn-warm">提交代付</a>
                    <a href="javascript:;" id="rejectAllOrder" class="layui-btn layui-btn-sm layui-btn-danger">一键驳回</a>
                    <a href="javascript:;" id="queryAllOrder" class="layui-btn layui-btn-sm layui-btn-warnning">代付查询</a>
                </p>
               <!--交易列表-->
                <table class="layui-table" lay-data="{width:'100%',limit:<?php echo ($rows); ?>,id:'userData'}" id="tab">
                    <thead>
                    <tr>
                        <!--th lay-data="{field:'check' , width:60}"> </th-->
                       <!-- <th lay-data="{field:'key', width:80}"></th>-->
                        <!--th lay-data="{field:'t', width:60}">类型</th-->
                        <th lay-data="{field:'orderid', width:165}">系统订单号</th>
                         <th lay-data="{field:'out_trade_no', width:195}">商户订单号</th>
                        <th lay-data="{field:'userid', width:105,style:'color:#060;'}">商户编号</th>
                        <th lay-data="{field:'op', width:130,style:'color:#060;'}">操作</th>
                      <!--th lay-data="{field:'code', width:100}">代付渠道</th>
                      <<th lay-data="{field:'channel_mch_id', width:150,style:'color:#060;'}">通道商户号</th>-->
                        <th lay-data="{field:'tkmoney', width:110}">结算金额</th>
                        <th lay-data="{field:'sxfmoney', width:100,style:'color:#060;'}">手续费</th>
                        <!--th lay-data="{field:'money', width:110}">到账金额</th-->
                        <th lay-data="{field:'bankname', width:120,style:'color:#C00;'}">银行名称</th>
                        <th lay-data="{field:'bankfullname', width:80}">开户名</th>                          
                        <th lay-data="{field:'banknumber', width:180}">银行卡号</th>
                      
                        <th lay-data="{field:'bankzhiname', width:120}">支行名称</th>

                        <!--th lay-data="{field:'sheng', width:80}">所属省</th>
                        <th lay-data="{field:'shi', width:80}">所属市</th-->
                        <th lay-data="{field:'channel_mch_id',width:100}">补发通知</th>                        
                        <th lay-data="{field:'sqdatetime', width:170}">申请时间</th>
                        <th lay-data="{field:'cldatetime', width:170}">处理时间</th>
                        <th lay-data="{field:'status', width:100}">状态</th>
                        <!--th lay-data="{field:'memo', width:100}">备注</th-->
                        

                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <!--td><input type="checkbox"  title="" value="<?php echo ($vo["id"]); ?>" class='checkIds' lay-skin="primary"></td-->
                            <!--<td><?php echo ($vo["id"]); ?></td>-->
                            <!--td>T+<?php echo ($vo["t"]); ?></td-->
                            <td><?php echo ($vo["orderid"]); ?></td>
                            <td><?php echo ($vo["out_trade_no"]); ?></td>
                            <td><?php echo ($vo["userid"]+10000); ?></td>
                               <td class="layui-input-inline">
                                <?php if($vo['status'] < 2 || $vo['status'] == 4): ?><a class="layui-btn layui-btn-danger layui-btn-mini" onclick="set_wtop('结算商户编号:<?php echo ($vo["userid"]); ?>','<?php echo U('Admin/Withdrawal/editwtStatus',['id'=>$vo[id]]);?>',510,280)">【设置状态】</a><?php endif; ?>
                                <!--if condition="$vo['status'] eq 0">
                                    <a class="layui-btn layui-btn-danger layui-btn-mini" onclick="submitPayment(this, '<?php echo ($vo[id]); ?>','exec')" >提交代付</a>
                                </if-->

                                <?php if($vo['status'] == 1 or $vo['status'] == 4): ?><a class="layui-btn layui-btn-warnning layui-btn-mini" onclick="submitPayment(this, '<?php echo ($vo[id]); ?>','query')">代付查询</a><?php endif; ?>
                            </td>
                             <!--td >
                                <?php if($vo['status'] == 0): ?><select lay-ignore=''  style="height: 25px;">
                                        <option>请选择</option>
                                        <?php if(is_array($pfa_lists)): foreach($pfa_lists as $key=>$v): ?><option value="<?php echo ($v["id"]); ?>" <?php if($v[is_default] == 1): ?>selected<?php endif; ?> ><?php echo ($v["title"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                    
                                    <?php else: ?>
                                    <?php echo ($vo["df_name"]); endif; ?>
                                <?php if($vo['status'] == 2): ?><a>通知成功</a><?php endif; ?>
                            </td-->
                            <td><?php echo ($vo["tkmoney"]); ?> 元</td>
                            <td><?php echo ($vo["sxfmoney"]); ?> 元</td>
                            <!--td><?php echo ($vo["money"]); ?> 元</td-->
                            <td><?php echo ($vo["bankname"]); ?></td>
                            <td><?php echo ($vo["bankfullname"]); ?></td>                                  
                            <td><?php echo ($vo["banknumber"]); ?></td>
                      
                            <td><?php echo ($vo["bankzhiname"]); ?></td>

                            <!--td><?php echo ($vo["sheng"]); ?></td>
                            <td><?php echo ($vo["shi"]); ?></td-->
                             <td class="layui-input-inline">
                                <?php if($vo['status'] == 2): ?><a class="" target="_blank" href="<?php echo U('Admin/Withdrawal/notify',['id'=>$vo[id]]);?>" ><font color="red">重发回调</font></a><?php endif; ?>
                            </td>                            
                            <td><?php echo ($vo["sqdatetime"]); ?></td>
                            <td><?php echo ($vo["cldatetime"]); ?></td>
                            <td>
                                <?php switch($vo["status"]): case "0": ?><span style="color:#F00;">未处理</span><?php break;?>
                                    <?php case "1": ?><span style="color:#06F;">处理中</span><?php break;?>
                                    <?php case "2": ?><span style="color:#060;">已打款</span><?php break;?>
                                    <?php case "3": ?><span class="text-danger">已驳回</span><?php break;?>
                                    <?php case "4": ?><span class="text-danger">待确认</span><?php break;?>
                                    <?php default: endswitch;?>
                            </td>
                            <!--td><?php echo ($vo["memo"]); ?></td>
                           
                           <td><?php echo ($vo["channel_mch_id"]); ?></td-->

                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <!--交易列表-->
                <div class="page">
                    <form class="layui-form" action="" method="get" id="pageForm"  autocomplete="off">
                        <?php echo ($page); ?>
                        <select name="rows" style="height: 29px;" id="pageList" lay-ignore >
                            <option value="">显示条数</option>
                            <option <?php if($_GET[rows] != '' && $_GET[rows] == 15): ?>selected<?php endif; ?> value="15">15条</option>
                            <option <?php if($_GET[rows] == 30): ?>selected<?php endif; ?> value="30">30条</option>
                            <option <?php if($_GET[rows] == 50): ?>selected<?php endif; ?> value="50">50条</option>
                            <option <?php if($_GET[rows] == 80): ?>selected<?php endif; ?> value="80">80条</option>
                            <option <?php if($_GET[rows] == 100): ?>selected<?php endif; ?> value="100">100条</option>
                            <option <?php if($_GET[rows] == 1000): ?>selected<?php endif; ?> value="1000">1000条</option>
                        </select>
                    </form>
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
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,form = layui.form //表单
            , table = layui.table; //表格
        //日期时间范围
        laydate.render({
            elem: '#createtime'
            , type: 'datetime'
            ,theme: 'molv'
            , range: '|'
        });
        //日期时间范围
        laydate.render({
            elem: '#successtime'
            , type: 'datetime'
            ,theme: 'molv'
            , range: '|'
        });

    });
    /*订单-查看*/
    function set_wtop(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('Admin/Withdrawal/exportweituo',array('memberid'=>$_GET[memberid],'T'=>$_GET[T],'createtime'=>$_GET[createtime],'successtime'=>$_GET[successtime],'tongdao'=>$_GET[tongdao],'status'=>$_GET[status]));?>";
    });

    function submitPayment(obj, id, name) {
        //var select_obj = $(obj).parent().parent().prev().find('select');
        var select_obj = $(obj).parent().parent().next().find('select');
        var code = select_obj.val();
        if(typeof(code)=="undefined")
            code = '2';
        if(name=='exec') {
            df_view('提交代付', "<?php echo U('Admin/Withdrawal/submitDf');?>?ids="+id+'&channe_code='+code, 640 , 280);
        }else if(name=='notify') {
            df_view('重新发送回调通知', "<?php echo U('Admin/Withdrawal/notify');?>?ids="+id+'&channe_code='+code, 640 , 280);
        } else {
            layer.confirm('确认要提交查询吗？',function(index) {
                $.ajax({
                    url: "<?php echo U('Payment/Index/index',array(), false);?>",
                    type: 'post',
                    data: {'id':id ,'opt':name, 'code':code},
                    success: function (res) {
                        if (res.status!='error') {
                            layer.msg('已成功提交!', {icon: 1, time: 1000},function () {
                                location.replace(location.href);
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2, time: 1000},function () {
                                location.replace(location.href);
                            });
                        }
                    }
                });
            });
        }
    }

    $('#submitAllOrder').on('click', function(){
        var id = '';
        $('.checkIds').each(function(){
            var _this = $(this);
            if( _this.is(':checked')  ){
                id = id + _this.val() + ',';
            }
        });
        if(id){
            df_view('批量提交代付', "<?php echo U('Admin/Withdrawal/submitDf');?>?ids="+id, 640 , 280);
        }else{
            layer.msg('请选择代付申请', {icon: 2, time: 1000},function () {});
        }
    });

    $('#rejectAllOrder').on('click', function(){
        var id = '';
        $('.checkIds').each(function(){
            var _this = $(this);
            if( _this.is(':checked')  ){
                id = id + _this.val() + ',';
            }
        });
        if(id){
            layer.confirm('确认要一键驳回吗？',function(index) {
                $.ajax({
                    url: "<?php echo U('Admin/Withdrawal/rejectAllDf');?>",
                    type: 'post',
                    data: {'id':id},
                    success: function (res) {
                        if (res.status) {
                            layer.msg(res.msg, {icon: 1, time: 1000},function () {
                                location.replace(location.href);
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2, time: 1000});
                        }
                    }
                });
            });
        }else{
            layer.msg('请选择代付申请', {icon: 2, time: 1000},function () {});
        }
    });

    $('#queryAllOrder').on('click', function(){
        var id = '';
        $('.checkIds').each(function(){
            var _this = $(this);
            if( _this.is(':checked')  ){
                id = id + _this.val() + ',';
            }
        });

        if(id){
            layer.confirm('确认要提交吗？',function(index) {
                $.ajax({
                    url: "<?php echo U('Payment/Index/batchQuery');?>",
                    type: 'post',
                    data: {'id':id},
                    success: function (res) {
                        if (res.status!='error') {
                            layer.msg(res.msg, {icon: 1, time: 1000},function () {
                                location.replace(location.href);
                            });
                        }else{
                            layer.msg(res.msg, {icon: 2, time: 1000},function () {
                                location.replace(location.href);
                            });
                        }
                    }
                });
            });
        }else{
            layer.msg('请选择代付订单', {icon: 2, time: 1000},function () {});
        }
    });

    $('#checkAll').on('click', function(){
        var child = $('table').next().find('tbody input[type="checkbox"]');  ;
        child.each(function(){
            $(this).attr('checked', true);
        });
        $('.layui-form-checkbox').addClass('layui-form-checked');

    });

    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
    /*提交代付-身份验证*/
    function df_view(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
</script>
</body>
</html>