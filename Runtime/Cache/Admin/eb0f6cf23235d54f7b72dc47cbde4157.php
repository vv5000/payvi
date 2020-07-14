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
        <h5>用户财务分析</h5>
        <div class="ibox-tools">
          <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
             style="cursor:pointer;">ဂ</i>
        </div>
      </div>
      <!--条件查询-->
      <div class="ibox-content">
        <form class="layui-form" action="" method="get" autocomplete="off" id="orderform">
    
          <div class="layui-form-item">
              <div class="layui-inline">
                <div class="layui-input-inline">
                  <input type="text" name="memberid" autocomplete="off" placeholder="请输入商户号"
                         class="layui-input" value="<?php echo ($_GET['memberid']); ?>">
                </div>
     <!--            <div class="layui-inline">
                    <div class="layui-input-inline">
                      <input type="text" class="layui-input" name="order_time" id="order_time"
                    placeholder="订单筛选时间" value="<?php echo ($_GET['order_time']); ?>">
                    </div>
                              
                </div>

                <div class="layui-inline">
                    <div class="layui-input-inline">
                      <input type="text" class="layui-input" name="wtkk_time" id="wtkk_time"
                    placeholder="代付筛选时间" value="<?php echo ($_GET['wtkk_time']); ?>">
                    </div>
                              
                </div> -->

      <!--           <div class="layui-inline">
                    <div class="layui-input-inline">
                      <input type="text" class="layui-input" name="tk_time" id="tk_time"
                    placeholder="提现筛选时间" value="<?php echo ($_GET['tk_time']); ?>">
                    </div>
                            
                </div> -->
                <!-- <p style="color: red;">*多个时间同时搜索，数据可能会出现偏差值</p> -->
              </div>

              <div class="layui-inline">
                <button type="submit" class="layui-btn"><span
                        class="glyphicon glyphicon-search"></span> 搜索
                </button>
                <a href="javascript:;" id="export"
                   class="layui-btn layui-btn-danger"><span
                        class="glyphicon glyphicon-export"></span> 导出数据</a>
              </div>
          </div>
        </form>
      

        <div class="list item">
          <!--交易列表-->
          <table class="layui-table" lay-data="{width:'100%',limit:<?php echo ($rows+1); ?>,id:'userData'}">
            <thead>
            <tr>
              <th lay-data="{field:'memberid',width:120}">商户编号</th>
              <th lay-data="{field:'username',width:120}">商户名称</th>
              <th lay-data="{field:'all_order_count',width:120}">提交订单</th>
              <th lay-data="{field:'paid_order_count',width:120}">已付订单</th>
              <th lay-data="{field:'nopaid_order_count',width:120}">未付订单</th>
              <th lay-data="{field:'all_order_amount',width:120}">提交金额</th>
              <th lay-data="{field:'paid_order_amount',width:120}">实付金额</th>
              <th lay-data="{field:'pays_poundage',width:120}">入金手续费</th>
              <th lay-data="{field:'balance',width:120}">可用资金</th>
              <th lay-data="{field:'blockedbalance',width:120}">冻结资金</th>
              <th lay-data="{field:'pays_amount',width:120}">入金总额</th>
              <th lay-data="{field:'tkmoney',width:120}">出金总额</th>
              <th lay-data="{field:'sxfmoney',width:120}">出金手续费</th>
              <th lay-data="{field:'money',width:120}">实际出金金额</th>
          
     <!--          <th lay-data="{field:'lx3',width:115}">手动增加余额</th>
              <th lay-data="{field:'lx4',width:115}">手动减少余额</th> -->
        
            </tr>
            </thead>
            <tbody>
              
              <?php if(is_array($list)): foreach($list as $key=>$v): ?><tr>
 
                <td><?php echo ($v["pays_memberid"]); ?></td>
                <td><?php echo ($v["username"]); ?></td>
                <td><?php echo ($v["all_order_count"]); ?></td>
                <td><?php echo ($v["paid_order_count"]); ?></td>
                <td><?php echo ($v["nopaid_order_count"]); ?></td>
                <td><?php echo ($v["all_order_amount"]); ?></td>
                <td><?php echo ($v["paid_order_amount"]); ?></td>
                <td><?php echo ($v["pays_poundage"]); ?></td>
                <td><?php echo ($v["balance"]); ?></td>
                <td><?php echo ($v["blockedbalance"]); ?></td>
                <td><?php echo ($v["pays_amount"]); ?></td>
                <td><?php echo ($v["tkmoney"]); ?></td>
                <td><?php echo ($v["sxfmoney"]); ?></td>
                <td><?php echo ($v["money"]); ?></td>
                <td><?php echo ($v["member_income"]); ?></td>
                <td><?php echo ($v["platform_income"]); ?></td>
              </tr><?php endforeach; endif; ?>
              <tr>
                <td>统计：</td>
                <td><?php echo ($stat["member_count"]); ?>个商户</td>
                <td><?php echo ($stat["all_order_count"]); ?>条订单</td>
                <td><?php echo ($stat["paid_order_count"]); ?>条订单</td>
                <td><?php echo ($stat["nopaid_order_count"]); ?>条订单</td>
                <td><?php echo ($stat["all_order_amount"]); ?>元</td>
                <td><?php echo ($stat["paid_order_amount"]); ?>元</td>
                <td><?php echo ($stat["balance"]); ?>元</td>
                <td><?php echo ($stat["blockedbalance"]); ?>元</td>
                <td><?php echo ($stat["pays_amount"]); ?>元</td>
                <td><?php echo ($stat["tkmoney"]); ?>元</td>
                <td><?php echo ($stat["sxfmoney"]); ?>元</td>
                <td><?php echo ($stat["money"]); ?>元</td>
                <td><?php echo ($stat["member_income"]); ?>元</td>
                <td><?php echo ($stat["platform_income"]); ?>元</td>
              </tr>
            </tbody>
          </table>
          <div class="page"><?php echo ($page); ?>
            <div class="layui-input-inline">
              <form class="layui-form" action="" method="get" id="pageForm" autocomplete="off">

                <select name="rows" style="height: 32px;" id="pageList" lay-ignore >
                  <option value="">显示条数</option>
                  <option <?php if($_GET[rows] != '' && $_GET[rows] == 15): ?>selected<?php endif; ?> value="15">15条</option>
                  <option <?php if($_GET[rows] == 30): ?>selected<?php endif; ?> value="30">30条</option>
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


    layui.use(['laydate', 'laypage', 'layer', 'table', 'form', 'element'], function() {
        var laydate = layui.laydate //日期
            , laypage = layui.laypage //分页
            ,layer = layui.layer //弹层
            ,form = layui.form //表单
            , table = layui.table //表格
            , element = layui.element;

        var time = ['order_time', 'wtkk_time',  'tk_time'];
        for(k in time){
          //日期时间范围
          laydate.render({
              elem: '#' + time[k]
              , type: 'datetime'
              ,theme: 'molv'
              , range: '|'
          });
        }
    });
    
    /*订单-查看*/
    function order_view(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
    $('#export').on('click',function(){
        window.location.href
            ='<?php echo ($export); ?>';
    });

    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('Admin/Statistics/exportUserFinance',array('memberid'=>$_GET[memberid]));?>";
    });
</script>
</body>
</html>