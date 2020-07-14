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
        <h5>全平台交易统计</h5>
        <div class="ibox-tools">
          <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
             style="cursor:pointer;">ဂ</i>
        </div>
      </div>
      <!--条件查询-->
      <div class="ibox-content">
        <form class="layui-form" action="" method="get" autocomplete="off" id="orderform">
          <input type="hidden" name="m" value="<?php echo ($model); ?>">
          <input type="hidden" name="c" value="Order">
          <input type="hidden" name="a" value="index" id="action">
          <div class="layui-form-item">
            <div class="layui-inline">
              <div class="layui-input-inline">
                <input type="text" name="memberid" autocomplete="off" placeholder="请输入商户号"
                       class="layui-input" value="<?php echo ($_GET['memberid']); ?>">
              </div>

              <div class="layui-input-inline">
                <input type="text" name="orderid" autocomplete="off" placeholder="请输入订单号"
                       class="layui-input" value="<?php echo ($_GET['orderid']); ?>">
              </div>
              <!--<div class="layui-input-inline">
                <input type="text" class="layui-input" name="createtime" id="createtime"
                       placeholder="创建起始时间" value="<?php echo ($_GET['createtime']); ?>">
              </div> -->
              <div class="layui-input-inline">
                <input type="text" class="layui-input" name="successtime" id="successtime"
                       placeholder="按时间" value="<?php echo ($_GET['successtime']); ?>">
              </div>
            </div>
            <div class="layui-inline">
              <div class="layui-input-inline">
                <select name="tongdao">
                  <option value="">全部通道</option>
                  <?php if(is_array($tongdaolist)): $i = 0; $__LIST__ = $tongdaolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($_GET[tongdao] == $vo[code]): ?>selected<?php endif; ?>
                    value="<?php echo ($vo["code"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
            </div>

            <div class="layui-inline">
              <button type="submit" class="layui-btn"><span
                      class="glyphicon glyphicon-search"></span> 搜索
              </button>
              <a href="javascript:;" id="export" class="layui-btn layui-btn-warm"><span class="glyphicon glyphicon-export"></span> 导出数据</a>
              <?php if(($isrootadmin) == "true"): endif; ?>
            </div>
          </div>
        </form>
        <!--<blockquote class="layui-elem-quote" style="font-size:14px;padding;8px;">平台交易总金额：<span class="label label-info"><?php echo ($stamount); ?>元</span> 平台总利润：<span class="label label-info"><?php echo ($strate); ?>元</span>
          代理总收入：<span class="label label-info"><?php echo ($memberprofit); ?>元</span> 商户收入总金额：<span class="label label-info"><?php echo ($strealmoney); ?>元</span> 成功订单数：<span class="label label-info"><?php echo ($success_count); ?></span> 失败订单数：<span class="label label-danger"><?php echo ($fail_count); ?></span>
          投诉保证金已返回金额：<span class="label label-info"><?php echo ($complaints_deposit_unfreezed); ?></span> 投诉保证金冻结金额：<span class="label label-danger"><?php echo ($complaints_deposit_freezed); ?></span>
        </blockquote> -->
          
           <?php if($_GET['successtime']): ?><blockquote class="layui-elem-quote" style="font-size:14px;padding;8px;">
         <?php echo ($_GET['successtime']); ?>：已支付订单总额：<span class="label label-danger"><?php echo ($stamount1); ?>元</span>
           商户收入总金额：<span class="label label-info"><?php echo ($strealmoney); ?>元</span>
           成功总订单数：<span class="label label-info"><?php echo ($success_count); ?></span>
          
           平台收入（含代理分成）总额：<span class="label label-danger"><?php echo ($strate1); ?>元</span> 
             <!--代理收入总额：<span class="label label-danger"><?php echo ($memberprofit1); ?>元</span> -->
           </blockquote><?php endif; ?>
          
        <div class="ibox float-e-margins chart item">
        <div class="ibox-title"><h5>交易统计</h5></div>
          <div class="ibox-content no-padding">
            <div class="panel-body">
              <div class="panel-group" id="version">
                <div class="col-lg-12"><div id="dmonth" style="height: 600px;"></div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="list item">
          <!--交易列表-->
          <table class="layui-table" lay-data="{width:'100%',id:'userData'}">
            <thead>
            <tr>
               <!--<th lay-data="{field:'key',width:60}"></th>-->
              <th lay-data="{field:'ddlx', width:90}">订单类型</th>
              <th lay-data="{field:'out_trade_id', width:240,style:'color:#060;'}">订单号</th>
              <th lay-data="{field:'memberid', width:150}">商户编号</th>
              <th lay-data="{field:'amount', width:100,style:'color:#060;'}">交易金额</th>
              <th lay-data="{field:'rate', width:90}">手续费</th>
              <th lay-data="{field:'actualamount', width:100,style:'color:#C00;'}">实际金额</th>
              <th lay-data="{field:'applydate', width:160}">提交时间</th>
              <th lay-data="{field:'successdate', width:160}">成功时间</th>
              <th lay-data="{field:'zh_tongdao', width:160}">支付通道</th>
              <th lay-data="{field:'bankname', width:120}">支付银行</th>
              <th lay-data="{field:'status', width:232}">状态</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                <!--<td><?php echo ($vo["id"]); ?></td>-->
                <td>
                  <?php switch($vo[ddlx]): case "1": ?>充值<?php break;?>
                    <?php default: ?>收款<?php endswitch;?>
                </td>
                <td style="text-align:center; color:#090;"><?php echo ($vo[out_trade_id]?$vo[out_trade_id]:$vo[pays_orderid]); ?>
                  <?php if($vo["del"] == 1): ?><span style="color: #f00;">×</span><?php endif; ?>
                </td>
                <td style="text-align:center;"><?php echo ($vo["pays_memberid"]); ?></td>
                <td style="text-align:center; color:#060"><?php echo ($vo["pays_amount"]); ?></td>
                <td style="text-align:center; color:#666"><?php echo ($vo["pays_poundage"]); ?></td>
                <td style="text-align:center; color:#C00"><?php echo ($vo["pays_actualamount"]); ?></td>
                <td style="text-align:center;"><?php echo (date('Y-m-d H:i:s',$vo["pays_applydate"])); ?></td>
                <td style="text-align:center;"><?php if($vo[pays_successdate]): echo (date('Y-m-d H:i:s',$vo["pays_successdate"])); else: ?> ---<?php endif; ?></td>
                <td style="text-align:center;"><?php echo ($vo["pays_zh_tongdao"]); ?></td>
                <td style="text-align:center;"><?php echo ($vo["pays_bankname"]); ?></td>
                <td style="text-align:center; color:#369"><?php echo (status($vo['pays_status'])); ?></td>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
          <!--交易列表-->
          <div class="pagex"> <?php echo ($page); ?></div>
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
<script src="/Public/Front/js/echarts.common.min.js"></script>
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
    
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('Admin/Statistics/exportorder',array('memberid'=>$_GET[memberid],'orderid'=>$_GET[orderid],'createtime'=>$_GET[createtime],'successtime'=>$_GET[successtime],'tongdao'=>$_GET[tongdao],'bank'=>$_GET[bank],'status'=>$_GET[status],'ddlx'=>$_GET[ddlx]));?>";
    });

    
    var myChart = echarts.init(document.getElementById('dmonth'));
    myChart.setOption({
        tooltip : {
            trigger: 'axis',
            axisPointer: {
                type: 'cross',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        legend: {
            data:['交易金额','收入金额','支出金额']
        },
        toolbox: {
            feature: {
                saveAsImage: {}
            }
        },
        grid: {
            left: '3%',
            right: '4%',
            bottom: '3%',
            containLabel: true
        },
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : [<?php echo (implode($mdata["mdate"],",")); ?>]
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'交易金额',
                type:'line',
                stack: '总量',
                areaStyle: {normal: {}},
                data:[<?php echo (implode($mdata["amount"],",")); ?>]
            },
            {
                name:'收入金额',
                type:'line',
                stack: '总量',
                areaStyle: {normal: {}},
                data:[<?php echo (implode($mdata["rate"],",")); ?>]
            },
            {
                name:'支出金额',
                type:'line',
                stack: '总量',
                areaStyle: {normal: {}},
                data:[<?php echo (implode($mdata["total"],",")); ?>]
            },
        ]
    });
</script>
</body>
</html>