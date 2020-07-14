<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title><?php echo ($sitename); ?> - 管理中心</title>
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
                <h5>对账单</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->
            <div class="ibox-content">
                <form class="layui-form" action="" method="get" autocomplete="off" id="orderform">
                    <input type="hidden" name="m" value="User">
                    <input type="hidden" name="c" value="Account">
                    <input type="hidden" name="a" value="reconciliation">
                    <input type="hidden" name="p" value="1">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <input type="text" class="layui-input" name="date" id="date" placeholder="日期"  value="<?php echo ($date); ?>">
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn"><span
                                    class="glyphicon glyphicon-search"></span> 搜索
                            </button>
                        </div>
                    </div>
                </form>
                <!--交易列表-->
                <table class="layui-table" lay-data="{width:'100%',id:'userData'}">
                    <thead>
                    <tr>
                        <th lay-data="{field:'date',width:120}">日期</th>
                        <th lay-data="{field:'order_total_count', width:200,style:'color:#060;'}">总订单数</th>
                        <th lay-data="{field:'order_success_count', width:200,style:'color:#060;'}">成功订单数</th>
                        <th lay-data="{field:'order_fail_count', width:200}">未支付订单数</th>
                       
                        <th lay-data="{field:'order_success_amount', width:200}">订单成功总额</th>
                       <th lay-data="{field:'order_success0_amount', width:200}">订单未付总额</th>
                       <th lay-data="{field:'order_total_amount', width:200}">当日所有订单(含已付)总额</th>
                      <th lay-data="{field:'order_poundage_amount', width:200}">手续费总额</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["date"]); ?></td>
                            <td style="text-align:center; color:#090;"><?php echo ($vo["order_total_count"]); ?></td>
                            <td style="text-align:center;"><?php echo ($vo["order_success_count"]); ?></td>
                            <td style="text-align:center;"><?php echo ($vo["order_fail_count"]); ?></td>
                          <td style="text-align:center;"><?php echo ($vo["order_success_amount"]); ?></td>
                            <td style="text-align:center;"><?php echo ($vo["order_success0_amount"]); ?></td>
                               <td style="text-align:center;"><?php echo ($vo["order_total_amount"]); ?></td>
                                 <td style="text-align:center;"><?php echo ($vo["order_poundage_amount"]); ?></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
                <!--交易列表-->
               <div class="page">

                    <form class="layui-form" action="" method="get" id="pageForm" autocomplete="off">     
                        <?php echo ($page); ?>
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
<script src="/Public/Front/js/Util.js" charset="utf-8"></script>
<script>
    layui.use(['laydate', 'laypage', 'layer', 'table', 'form'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,form = layui.form //表单
            , table = layui.table; //表格
        //日期时间范围
        laydate.render({
            elem: '#date'
            , type: 'date'
            ,theme: 'molv'
            , min:"<?php echo ($time); ?>"
            , max: "{:date('Y-m-d')}",

        });
    });
    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
</script>
</body>
</html>