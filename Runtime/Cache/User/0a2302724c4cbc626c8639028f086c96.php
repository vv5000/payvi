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
      <div class="ibox-title">
        <h5>商户名称：<?php echo ($fans["username"]); ?></h5><h5>&nbsp;&nbsp;商户ID：<?php echo ($fans["memberid"]); ?></h5>
      </div>
      <div class="ibox-content">
        <p>上次登录IP：<?php echo ($lastlogin["loginip"]); ?>，登录地址：<?php echo ($lastlogin["loginaddress"]); ?>，登录时间：<?php echo ($lastlogin["logindatetime"]); ?></p>
        <?php if(!empty($ipItem)): ?><p>可登录IP：
            <?php if(is_array($ipItem)): foreach($ipItem as $k=>$v): ?>[<?php echo ($k); ?>]<?php echo ($v); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; ?>
        </p><?php endif; ?>
      </div>
    </div>
  </div>
</div>
 <div class="row zuy-nav">
<?php if(($fans[groupid]) == "4"): ?><div class="col-sm-3">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5><?php echo ($stat["todayordercount"]); ?></h5>
      </div>
      <div class="ibox-content" style="height: 80px">
        <h1 class="no-margins">今日总订单数</h1>
        <div class="stat-percent font-bold text-success">单</div>
      </div>
    </div>
  </div>
    
  <div class="col-sm-3" >
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5><?php echo ($stat["todayorderpaidcount"]); ?></h5>
      </div>
      <div class="ibox-content" style="height: 80px">
        <h1 class="no-margins">今日已付订单数</h1>
        <div class="stat-percent font-bold text-success">单</div>
      </div>
    </div>
  </div>
    
    <div class="col-sm-3">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><?php echo ($stat["todayordernopaidcount"]); ?></h5>
        </div>
        <div class="ibox-content" style="height: 80px">
          <h1 class="no-margins">今日未付订单</h1>
          <div class="stat-percent font-bold text-success">单</div>
        </div>
      </div>
  </div>
      
  <div class="col-sm-3">
    <div class="ibox float-e-margins">
      <div class="ibox-title">
        <h5><?php echo ($stat["today_income"]); ?></h5>
      </div>
      <div class="ibox-content" style="height: 80px">
        <h1 class="no-margins">今日总入金</h1>
        <div class="stat-percent font-bold text-success">元</div>
      </div>
    </div>
  </div><?php endif; ?>
    </div>
    
    
  <div class="row">
    <?php if(($fans[groupid]) == "4"): ?><div class="col-sm-3">
      <div class="ibox float-e-margins">
        
        <div class="ibox-title">
          <h5><?php echo ($stat["todayorderactualsum"]); ?></h5>
        </div>
          
        <div class="ibox-content" style="height: 80px">
          <h1 class="no-margins">今日实付金额</h1>
          <div class="stat-percent font-bold text-success">元</div>
        </div>
          
      </div>
    </div><?php endif; ?>
      
    <div class="col-sm-3">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><?php echo ($fans['balance']); ?></h5>
        </div>
        <div class="ibox-content"  style="height: 80px">
          <h1 class="no-margins">账户余额</h1>
          <div class="stat-percent font-bold text-success">元</div>
          <small>可提现</small>
        </div>
      </div>
    </div>
      
    <div class="col-sm-3">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><?php echo ($fans['blockedbalance']); ?></h5>
        </div>
        <div class="ibox-content"  style="height: 80px">
          <h1 class="no-margins">冻结资金</h1>
          <div class="stat-percent font-bold text-info">元</div>
          <small>待解冻</small>
        </div>
      </div>
    </div>
      
    <div class="col-sm-3">
      <div class="ibox float-e-margins">
        <div class="ibox-title">
          <h5><?php echo ($stat["complaints_deposit"]); ?></h5>
        </div>
        <div class="ibox-content"  style="height: 80px">
          <h1 class="no-margins">投诉保证金</h1>
          <div class="stat-percent font-bold text-info">元</div>
          <small>待解冻</small>
        </div>
      </div>
    </div>
 </div>
     

<div class="row">
  <div class="col-md-12">
    <div class="ibox float-e-margins">

        <div class="ibox-title">
          <h5>平台公告</h5>
        </div>
        <div class="ibox-content">
          <ul class="list-unstyled">
            <?php if(is_array($gglist)): $i = 0; $__LIST__ = $gglist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li style="line-height:30px;"><a href="<?php echo U('Index/showcontent',['id'=>$vo['id']]);?>"><?php echo ($vo["title"]); ?></a> <div style="float:right"><?php echo (date("Y-m-d",$vo["createtime"])); ?></div> </li><?php endforeach; endif; else: echo "" ;endif; ?>
          </ul>
        </div>
          
      </div>
    </div>
  </div>
    </div>
  </div>

  <!--<div class="row">
    <div class="col-md-12">
      <div class="ibox float-e-margins">
        
        <div class="ibox-title">
          <h5>日交易统计</h5>
        </div>
        <div class="ibox-content">
          <div id="main" style="height:300px"></div>
        </div>
        
      </div>
    </div>
  </div> -->

<!-- 全局js -->
<script src="<?php echo ($siteurl); ?>Public/Front/js/jquery.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/js/bootstrap.min.js"></script>
<script src="<?php echo ($siteurl); ?>Public/Front/js/content.js?v=1.0.0"></script>
<script src="/Public/Front/js/echarts.common.min.js"></script>
<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'));
    var option = {
        title : {
            text: '交易订单概况',
            subtext: '按天统计'
        },
        tooltip : {
            trigger: 'axis'
        },
        legend: {
            data:['成交','金额']
        },
        toolbox: {
            show : true,
            feature : {
                mark : {show: true},
                dataView : {show: true, readOnly: false},
                magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                restore : {show: true},
                saveAsImage : {show: true}
            }
        },
        calculable : true,
        xAxis : [
            {
                type : 'category',
                boundaryGap : false,
                data : <?php echo ($category); ?>
            }
        ],
        yAxis : [
            {
                type : 'value'
            }
        ],
        series : [
            {
                name:'成交',
                type:'line',
                smooth:true,
                itemStyle: {normal: {areaStyle: {type: 'default'}}},
                data:<?php echo ($dataone); ?>
            },
            {
                name:'金额',
                type:'line',
                smooth:true,
                itemStyle: {normal: {areaStyle: {type: 'default'}}},
                data:<?php echo ($datatwo); ?>
            }
        ]
    };

    // 为echarts对象加载数据
    myChart.setOption(option);
</script>
</body>
</html>