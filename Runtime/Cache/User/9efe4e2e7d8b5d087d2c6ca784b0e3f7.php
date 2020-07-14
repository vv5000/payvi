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
        <h5>订单管理</h5>
        <div class="ibox-tools">
          <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
             style="cursor:pointer;">ဂ</i>
        </div>
      </div>
      <!--条件查询-->
      <div class="ibox-content">
        <form class="layui-form" action="" method="get" autocomplete="off" id="orderform">
          <input type="hidden" name="m" value="User">
          <input type="hidden" name="c" value="Order">
          <input type="hidden" name="a" value="index">
          <input type="hidden" name="p" value="1">
          <div class="layui-form-item">
            <div class="layui-inline">
              <div class="layui-input-inline">
                <input type="text" name="orderid" autocomplete="off" placeholder="请输入订单号"
                       class="layui-input" value="<?php echo ($orderid); ?>">
              </div>
              <div class="layui-input-inline">
                <input type="text" name="body" autocomplete="off" placeholder="请输入订单描述"
                       class="layui-input" value="<?php echo ($body); ?>">
              </div>
              <div class="layui-input-inline" style="width:300px">
                <input type="text" class="layui-input" name="createtime" id="createtime"
                       placeholder="创建起始时间" value="<?php echo ($createtime); ?>">
              </div>
              <div class="layui-input-inline" style="width:300px">
                <input type="text" class="layui-input" name="successtime" id="successtime"
                       placeholder="完成起始时间" value="<?php echo ($successtime); ?>">
              </div>
            </div>
            <div class="layui-inline">
              <div class="layui-input-inline">
                <select name="tongdao">
                  <option value="">全部通道</option>
                  <?php if(is_array($banklist)): $i = 0; $__LIST__ = $banklist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if($tongdao == $vo[code]): ?>selected<?php endif; ?>
                    value="<?php echo ($vo["code"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
              </div>

              <div class="layui-input-inline">
                <select name="status">
                  <option value="">全部状态</option>
                  <option value="0" <?php if($status == '0'): ?>selected<?php endif; ?>>未处理</option>
                  <option value="1" <?php if($status == '1'): ?>selected<?php endif; ?>>成功，未返回</option>
                  <option value="2" <?php if($status == '2'): ?>selected<?php endif; ?>>成功，已返回</option>
                </select>
              </div>
              <div class="layui-input-inline">
                <select name="ddlx">
                  <option value="">订单类型</option>
                  <option <?php if($ddlx == 2): ?>selected<?php endif; ?> value="0">收款订单</option>
                  <option <?php if($ddlx == 1): ?>selected<?php endif; ?> value="1">充值订单</option>
                </select>
              </div>

            </div>

            <div class="layui-inline">
              <button type="submit" class="layui-btn"><span
                      class="glyphicon glyphicon-search"></span> 搜索
              </button>
              <a href="javascript:;" id="export" class="layui-btn layui-btn-danger"><span class="glyphicon glyphicon-export"></span> 导出数据</a>
            </div>
          </div>
        </form>
        <?php if($_GET['status'] == '2'): ?><blockquote class="layui-elem-quote" style="font-size:14px;padding:8px;">今日成功交易总额：<span class="label label-info"><?php echo ($stat["todaysum"]); ?>元</span>  今日成功交易笔数：<span class="label label-info"><?php echo ($stat["todaysuccesscount"]); ?></span>
            今日实际到账：<span class="label label-info"><?php echo ($stat["taodayactualamount"]); ?>元</span> 今日失败笔数：<span class="label label-danger"><?php echo ($stat["todayfailcount"]); ?></span>
          </blockquote><?php endif; ?>
        <?php if($_GET['createtime'] OR $_GET['successtime']): ?><blockquote class="layui-elem-quote" style="font-size:14px;padding:8px;">订单交易总金额(含未付)：<span class="label label-info"><?php echo ($sum["pays_amount"]); ?>元</span>
           成功订单数：<span class="label label-info"><?php echo ($sum["success_count"]); ?></span> 失败订单数：<span class="label label-danger"><?php echo ($sum["fail_count"]); ?></span>
           <!--投诉保证金已返回金额：<span class="label label-info"><?php echo ($sum["complaints_deposit_unfreezed"]); ?></span> 投诉保证金冻结金额：<span class="label label-danger"><?php echo ($sum["complaints_deposit_freezed"]); ?></span>-->
        </blockquote><?php endif; ?>
        <!--交易列表-->
        <table class="layui-table" lay-data="{width:'100%',limit:<?php echo ($rows); ?>,id:'userData'}">
          <thead>
          <tr>
            <th lay-data="{field:'key',width:90}">序号</th>
            <th lay-data="{field:'ddlx', width:60}">类型</th>
            <th lay-data="{field:'out_trade_id', width:240,style:'color:#060;'}">订单号</th>
            <th lay-data="{field:'memberid', width:110}">商户编号</th>
            <th lay-data="{field:'amount', width:100,style:'color:#060;'}">交易金额</th>
            <th lay-data="{field:'rate', width:90}">手续费</th>
            <th lay-data="{field:'actualamount', width:100,style:'color:#C00;'}">实际金额</th>
            <th lay-data="{field:'applydate', width:160}">提交时间</th>
            <th lay-data="{field:'successdate', width:160}">成功时间</th>
             <!--<th lay-data="{field:'zh_tongdao', width:120}">支付通道</th> -->
            <th lay-data="{field:'bankname', width:120}">支付银行</th>
             <!--<th lay-data="{field:'tjurl', width:100}">来源地址</th> -->
            <th lay-data="{field:'body', width:100}">订单描述</th>
            <th lay-data="{field:'status', width:110}">支付状态</th>
            <th lay-data="{field:'op',width:130}">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
              <td><?php echo ($vo["id"]); ?></td>
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
               <!--<td style="text-align:center;"><?php echo ($vo["pays_zh_tongdao"]); ?></td>-->
              <td style="text-align:center;"><?php echo ($vo["pays_bankname"]); ?></td>
              <!--<td style="text-align:center;"><a href="<?php echo ($vo["pays_tjurl"]); ?>" target="_blank" title="<?php echo ($vo["pays_tjurl"]); ?>">
                来源地址</a></td> -->
              <td style="text-align:center;"><?php echo ($vo["pays_productname"]); ?></td>
              <td style="text-align:center; color:#369"><?php echo (status($vo['pays_status'])); ?></td>
              <td>
                <button class="layui-btn layui-btn-small" onclick="order_view('订单号:<?php echo ($vo["out_trade_id"]); ?>','<?php echo U('User/Order/show',['oid'=>$vo[id]]);?>',780,630)">订单详情</button>
              </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
          </tbody>
        </table>
        <!--交易列表-->
        <div class="page">
            
            <form class="layui-form" action="" method="get" id="pageForm" autocomplete="off">     
              <?php echo ($page); ?>                            
                <select name="rows" style="height: 32px;" id="pageList" lay-ignore >
                    <option value="">显示条数</option>
                    <option <?php if($rows != '' && $rows == 15): ?>selected<?php endif; ?> value="15">15条</option>
                    <option <?php if($rows == 30): ?>selected<?php endif; ?> value="30">30条</option>
                    <option <?php if($rows == 50): ?>selected<?php endif; ?> value="50">50条</option>
                    <option <?php if($rows == 80): ?>selected<?php endif; ?> value="80">80条</option>
                    <option <?php if($rows == 100): ?>selected<?php endif; ?> value="100">100条</option>
                    <option <?php if($rows == 1000): ?>selected<?php endif; ?> value="1000">1000条</option>
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
    function order_view(title,url,w,h){
        x_admin_show(title,url,w,h);
    }
    /*订单-删除*/
    function order_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            $.ajax({
                url:"<?php echo U('Order/delOrder');?>",
                type:'post',
                data:'id='+id,
                success:function(res){
                    if(res.status){
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!',{icon:1,time:1000});
                    }
                }
            });
        });
    }
    $('#export').on('click',function(){
        window.location.href
            ="<?php echo U('User/Order/exportorder',array('orderid'=>$orderid,'createtime'=>$createtime,'successtime'=>$successtime,'status'=>$status,'memberid'=>$memberid));?>";
    });
    $('#pageList').change(function(){
        $('#pageForm').submit();
    });
</script>
</body>
</html>