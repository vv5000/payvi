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
        <h5>异常订单管理</h5>
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
          <input type="hidden" name="p" value="1">
          <div class="layui-form-item">
            <div class="layui-inline">
              <div class="layui-input-inline">
                <input type="text" name="memberid" autocomplete="off" placeholder="请输入商户号"
                       class="layui-input" value="<?php echo ($_GET['memberid']); ?>">
              </div>
              <div class="layui-input-inline">
                <input type="text" name="pays_orderid" autocomplete="off" placeholder="请输入平台订单号"
                       class="layui-input" value="<?php echo ($_GET['pays_orderid']); ?>">
              </div>

              <div class="layui-input-inline">
                <input type="text" name="orderid" autocomplete="off" placeholder="请输入下游订单号"
                       class="layui-input" value="<?php echo ($_GET['orderid']); ?>">
              </div>
              <div class="layui-input-inline">
                <input type="text" name="body" autocomplete="off" placeholder="请输入订单描述"
                       class="layui-input" value="<?php echo ($_GET['body']); ?>">
              </div>

              <div class="layui-input-inline" style="width:300px">
                <input type="text" class="layui-input" name="createtime" id="createtime"
                       placeholder="创建起始时间" value="<?php echo ($_GET['createtime']); ?>">
              </div>
              <div class="layui-input-inline" style="width:300px">
                <input type="text" class="layui-input" name="successtime" id="successtime"
                       placeholder="成功时间范围" value="<?php echo ($_GET['successtime']); ?>">
              </div>
            </div>
            <div class="layui-inline">
              <div class="layui-input-inline">
                <select name="tongdao">
                  <option value="">全部通道</option>
                  <?php if(is_array($tongdaolist)): $i = 0; $__LIST__ = $tongdaolist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($_GET[tongdao] == $vo[id]): ?>selected<?php endif; ?>
                    value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["title"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
              <div class="layui-input-inline">
                <select name="bank">
                  <option value="">全部银行</option>
                  <?php if(is_array($banklist)): $i = 0; $__LIST__ = $banklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($_GET[bank] == $vo[id]): ?>selected<?php endif; ?>
                    value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>
            </div>

            <div class="layui-inline">
              <button type="submit" class="layui-btn"><span
                      class="glyphicon glyphicon-search"></span> 搜索
              </button>


            </div>
          </div>
        </form>

        <div class="list item">
          <!--交易列表-->
          <table class="layui-table" lay-data="{width:'100%',limit:<?php echo ($rows); ?>,id:'userData'}">
            <thead>
            <tr>
              <!--<th lay-data="{field:'key',width:60}"></th>
              <th lay-data="{field:'ddlx', width:90}">订单类型</th>-->
              <th lay-data="{field:'pays_orderid', width:200,style:'color:#060;'}">平台订单号</th>
              <th lay-data="{field:'out_trade_id', width:240,style:'color:#060;'}">下游订单号</th>
              <th lay-data="{field:'pays_memberid', width:110}">商户编号</th>
              <th lay-data="{field:'amount', width:100,style:'color:#060;'}">交易金额</th>
              <th lay-data="{field:'rate', width:90}">手续费</th>
              <th lay-data="{field:'actualamount', width:100,style:'color:#C00;'}">实际金额</th>
              <th lay-data="{field:'applydate', width:160}">提交时间</th>
              <th lay-data="{field:'successdate', width:160}">成功时间</th>
              <th lay-data="{field:'zh_tongdao', width:150}">支付通道</th>
              <!--<th lay-data="{field:'memberid', width:160}">通道商户号</th>
              <th lay-data="{field:'bankname', width:120}">支付银行</th>
              <th lay-data="{field:'tjurl', width:100}">来源地址</th>
              <th lay-data="{field:'body', width:180}">订单描述</th>-->

              <th lay-data="{field:'status', width:120}">冻结状态</th>
              <th lay-data="{field:'op',width:156}">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <!-- <td><?php echo ($vo["id"]); ?></td>
                <td>
                  <?php switch($vo[ddlx]): case "1": ?>充值<?php break;?>
                    <?php default: ?>收款<?php endswitch;?>
                </td>-->
                <td style="text-align:center; color:#090;"><?php echo ($vo[pays_orderid]); ?>
                  <?php if($vo["del"] == 1): ?><span style="color: #f00;">×</span><?php endif; ?>
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
                <!--<td style="text-align:center;"><?php echo ($vo["memberid"]); ?></td>
              <td style="text-align:center;"><?php echo ($vo["pays_bankname"]); ?></td>
                <td style="text-align:center;"><a href="<?php echo ($vo["pays_tjurl"]); ?>" target="_blank" title="<?php echo ($vo["pays_tjurl"]); ?>">
                  来源地址</a></td>
                <td style="text-align:center;"><?php echo ($vo["pays_productname"]); ?></td>-->
                <td style="text-align:center; color:#369"><?php if(($vo["lock_status"]) == "1"): ?>冻结中
                  <?php else: ?>
                  已解冻<?php endif; ?></td>
                <td><a
                        href="javascript:order_view('系统订单号:<?php echo ($vo["pays_orderid"]); ?>','<?php echo U('Admin/Order/show',['oid'=>$vo[id]]);?>',780,750)"><span class="label label-success">订单详情</span>
                </a>
                  <?php if(($vo["lock_status"]) == "1"): ?><button class="layui-btn layui-btn-mini" onclick="forzenOrder(this,'<?php echo ($vo["id"]); ?>')">解冻订单</button><?php endif; ?>
                </td>
              </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
          </table>
          <!--交易列表-->
          <div class="page">  
              <form class="layui-form" action="" method="get" id="pageForm"  autocomplete="off">
                  <?php echo ($page); ?>                 
                  <select name="rows" style="height: 32px;" id="pageList" lay-ignore >
                      <option value="">显示条数</option>
                      <option <?php if($_GET[rows] != '' && $_GET[rows] == 30): ?>selected<?php endif; ?> value="30">30条</option>
                      <option <?php if($_GET[rows] == 40): ?>selected<?php endif; ?> value="40">40条</option>
                      <option <?php if($_GET[rows] == 50): ?>selected<?php endif; ?> value="50">50条</option>
                      <option <?php if($_GET[rows] == 80): ?>selected<?php endif; ?> value="80">80条</option>
                      <option <?php if($_GET[rows] == 100): ?>selected<?php endif; ?> value="100">100条</option>
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
<script src="/Public/Front/js/echarts.common.min.js"></script>
<script>
      $('#pageList').change(function(){
        $('#pageForm').submit();
    });
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
      function forzenOrder(obj, id) {
          layer.confirm('确认要解冻该订单吗？', function (index) {
              $.ajax({
                  url:"<?php echo U('Order/thawOrder');?>",
                  type:'post',
                  data:'orderid='+id,
                  success:function(res){
                      if(res.status=="1"){
                         layer.alert('解冻成功！',function () {
                              location.replace(location.href);
                          });
                      }else{
                          layer.msg('解冻失败!',{icon:2,time:1000});
                      }
                  }
              });
          });
      }
    /*订单-查看*/
    function order_view(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
    /*订单-批量删除*/
    function delAllOrder(title, url, w, h) {
        x_admin_show(title, url, w, h);
    }
    /*订单-设置订单状态为已支付*/
      function setOrderPaid(title, url, w, h) {
          x_admin_show(title, url, w, h);
      }
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('Admin/Order/exportorder',array('memberid'=>$_GET[memberid],'orderid'=>$_GET[orderid],'createtime'=>$_GET[createtime],'successtime'=>$_GET[successtime],'tongdao'=>$_GET[tongdao],'bank'=>$_GET[bank],'status'=>$_GET[status],'ddlx'=>$_GET[ddlx]));?>";
    });

    function chooseOrder_del() {
        var createtime=$("#createtime").val();
        if(createtime==""){
            layer.alert('请选择删除无效订单创建时间段');
            return;
        }
        layer.confirm('删除操作为真实删除，确定要删除时间段从'+createtime.replace('|','到')+'的无效订单吗？',function(index){
            $.ajax({
                url:"<?php echo U('Order/delOrder');?>",
                type:'post',
                data:{'createtime':createtime},
                success:function(res){
                    if(res.status){
                        layer.alert('删除成功！',function () {
                            location.replace(location.href);
                        });
                    }else{
                        layer.alert('删除失败！');
                    }
                }
            });
        });
    }
    
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