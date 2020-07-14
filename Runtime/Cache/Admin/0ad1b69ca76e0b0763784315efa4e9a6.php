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
                    <h5>商户报表</h5>
                    <div class="ibox-tools">
                        <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                           style="cursor:pointer;">ဂ</i>
                    </div>
                </div>
                <!--条件查询-->
                <div class="ibox-content">
                    <form class="layui-form" action="" method="get" autocomplete="off">
                        <input type="hidden" name="m" value="<?php echo ($model); ?>">
                        <input type="hidden" name="c" value="Statistics">
                        <input type="hidden" name="a" value="merchantReport">
                        <input type="hidden" name="p" value="1">
                        <div class="layui-form-item">
                            <label class="layui-form-label">商户号：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="memberid" autocomplete="off" placeholder="请输入商户号"
                                       class="layui-input" value="<?php echo ($_GET['memberid']); ?>">
                            </div>
                                <label class="layui-form-label">日期：</label>
                                <div class="layui-input-inline">
                                    <input type="text" class="layui-input" name="date" id="date" placeholder="日期"  value="<?php echo ($date); ?>">
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
                    <blockquote class="layui-elem-quote" style="font-size:14px;padding;8px;">报告生成时间：<?php echo date('Y-m-d H:i:s');?></blockquote>
                    <table class="layui-table" lay-data="{width:'100%',id:'userData'}">
                        <thead>
                        <tr>
                            <th lay-data="{field:'memberid',width:120}">商户号</th>
                            <th lay-data="{field:'username', width:150}">用户名</th>
                            <th lay-data="{field:'initial_money',width:150}">期初余额</th>
                            <!--<th lay-data="{field:'channel_rate', width:200}">通道费率</th> -->
                            <th lay-data="{field:'income_money', width:150}">入金金额</th>
                            <th lay-data="{field:'tksxf', width:100}">出金手续费</th>
                            <th lay-data="{field:'pays_wait_checked', width:130}">出金待审核金额</th>
                            <th lay-data="{field:'pays_success', width:130}">出金成功金额</th>
                            <th lay-data="{field:'pays_fail', width:130}">出金失败金额</th>
                            <th lay-data="{field:'frozen_money', width:130}">冻结金额</th>
                            <th lay-data="{field:'merchant_money', width:150}">商户实际到账金额</th>
                            <th lay-data="{field:'end_profit', width:150}">期末余额</th>
                            <th lay-data="{field:'current_money', width:150}">当前余额</th>
                            <th lay-data="{field:'partent', width:150}">上级代理</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                <td><?php echo ($vo["memberid"]); ?></td>
                                <td><?php echo ($vo["username"]); ?></td>
                                <td><?php echo ($vo["initial_money"]); ?></td>
                               <!-- <td style="text-align:center;">
                                    <?php if(empty($vo["channel_rate"])): ?>-
                                        <?php else: ?>
                                    <?php if(is_array($vo["channel_rate"])): $i = 0; $__LIST__ = $vo["channel_rate"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; echo ($v["name"]); ?>:<?php echo ($v['feilv']*1000); ?>‰<br><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                                </td> -->
                                <td><?php echo ($vo["income_money"]); ?></td>
                                <td><?php echo ($vo["tksxf"]); ?></td>
                                <td><?php echo ($vo["pays_wait_checked"]); ?></td>
                                <td><?php echo ($vo["pays_success"]); ?></td>
                                <td><?php echo ($vo["pays_fail"]); ?></td>
                                <td><?php echo ($vo["frozen_money"]); ?></td>
                                <td><?php echo ($vo["merchant_money"]); ?></td>
                                <td><?php echo ($vo["end_profit"]); ?></td>
                                <td><?php echo ($vo["current_money"]); ?></td>
                                <td><?php echo ($vo["parent"]); ?></td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
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
<script src="/Public/Front/js/jquery.min.js"></script>
<script src="/Public/Front/js/bootstrap.min.js"></script>
<script src="/Public/Front/js/plugins/peity/jquery.peity.min.js"></script>
<script src="/Public/Front/js/content.js"></script>
<script src="/Public/Front/js/plugins/layui/layui.js" charset="utf-8"></script>
<script src="/Public/Front/js/x-layui.js" charset="utf-8"></script>
<script>

    layui.use(['form','table',  'laydate', 'layer'], function () {
        var form = layui.form
            ,table = layui.table

            , layer = layui.layer
            , laydate = layui.laydate;

        //日期时间范围
        laydate.render({
            elem: '#date'
            , type: 'date'
            ,theme: 'molv'
            , max: "{:date('Y-m-d')}"
        });
    });

    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('Admin/Statistics/exportMerchant',array('memberid'=>$_GET[memberid],'date'=>$date));?>";
    });

</script>
</body>
</html>