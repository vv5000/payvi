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
                <h5>风控设置</h5>
                <div class="ibox-tools">
                    <i class="layui-icon" onclick="location.replace(location.href);" title="刷新"
                       style="cursor:pointer;">ဂ</i>
                </div>
            </div>
            <!--条件查询-->
            <div class="ibox-content"><br>
                <div class="layui-tab">
                    <div class="layui-tab-content">
                        <div class="layui-tab-item layui-show">
                            <form class="layui-form" action="" id="profile">
                                <input type="hidden" name="data[id]" value="1">
                                <div class="layui-form-item">
                                  <span style="padding-left: 15px;">
                                    <b style="color:red">注意:不需要风控的事项请默认0</b>
                                  </span>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">单笔最小金额：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" name="data[min_money]" autocomplete="off" value="<?php echo ($info["min_money"]); ?>"
                                               placeholder="" class="layui-input">
                                    </div>
                                    <label class="layui-form-label">单笔最大金额：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" name="data[max_money]" autocomplete="off" value="<?php echo ($info["max_money"]); ?>"
                                               placeholder="" class="layui-input">
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">当日总金额：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" name="data[all_money]" autocomplete="off" value="<?php echo ($info["all_money"]); ?>"
                                               placeholder="0.00" class="layui-input">
                                    </div>

                                </div>

                                <div class="layui-form-item">
                                  <div class="layui-inline">
                                      <label class="layui-form-label">交易时间：</label>
                                      <div class="layui-input-inline">
                                          <select name="data[start_time]">
                                              <option <?php if($info['start_time'] == 0): ?>selected<?php endif; ?> value="0">0:00</option>
                                              <option <?php if($info['start_time'] == 1): ?>selected<?php endif; ?> value="1">1:00</option>
                                              <option <?php if($info['start_time'] == 2): ?>selected<?php endif; ?> value="2">2:00</option>
                                              <option <?php if($info['start_time'] == 3): ?>selected<?php endif; ?> value="3">3:00</option>
                                              <option <?php if($info['start_time'] == 4): ?>selected<?php endif; ?> value="4">4:00</option>
                                              <option <?php if($info['start_time'] == 5): ?>selected<?php endif; ?> value="5">5:00</option>
                                              <option <?php if($info['start_time'] == 6): ?>selected<?php endif; ?> value="6">6:00</option>
                                              <option <?php if($info['start_time'] == 7): ?>selected<?php endif; ?> value="7">7:00</option>
                                              <option <?php if($info['start_time'] == 8): ?>selected<?php endif; ?> value="8">8:00</option>
                                              <option <?php if($info['start_time'] == 9): ?>selected<?php endif; ?> value="9">9:00</option>
                                              <option <?php if($info['start_time'] == 10): ?>selected<?php endif; ?> value="10">10:00</option>
                                              <option <?php if($info['start_time'] == 11): ?>selected<?php endif; ?> value="11">11:00</option>
                                              <option <?php if($info['start_time'] == 12): ?>selected<?php endif; ?> value="12">12:00</option>
                                              <option <?php if($info['start_time'] == 13): ?>selected<?php endif; ?> value="13">13:00</option>
                                              <option <?php if($info['start_time'] == 14): ?>selected<?php endif; ?> value="14">14:00</option>
                                              <option <?php if($info['start_time'] == 15): ?>selected<?php endif; ?> value="15">15:00</option>
                                              <option <?php if($info['start_time'] == 16): ?>selected<?php endif; ?> value="16">16:00</option>
                                              <option <?php if($info['start_time'] == 17): ?>selected<?php endif; ?> value="17">17:00</option>
                                              <option <?php if($info['start_time'] == 18): ?>selected<?php endif; ?> value="18">18:00</option>
                                              <option <?php if($info['start_time'] == 19): ?>selected<?php endif; ?> value="19">19:00</option>
                                              <option <?php if($info['start_time'] == 20): ?>selected<?php endif; ?> value="20">20:00</option>
                                              <option <?php if($info['start_time'] == 21): ?>selected<?php endif; ?> value="21">21:00</option>
                                              <option <?php if($info['start_time'] == 22): ?>selected<?php endif; ?> value="22">22:00</option>
                                              <option <?php if($info['start_time'] == 23): ?>selected<?php endif; ?> value="23">23:00</option>
                                          </select>
                                      </div>

                                      <div class="layui-form-mid">-</div>
                                      <div class="layui-input-inline">
                                          <select name="data[end_time]">
                                              <option <?php if($info['end_time'] == 0): ?>selected<?php endif; ?> value="0">0:00</option>
                                              <option <?php if($info['end_time'] == 1): ?>selected<?php endif; ?> value="1">1:00</option>
                                              <option <?php if($info['end_time'] == 2): ?>selected<?php endif; ?> value="2">2:00</option>
                                              <option <?php if($info['end_time'] == 3): ?>selected<?php endif; ?> value="3">3:00</option>
                                              <option <?php if($info['end_time'] == 4): ?>selected<?php endif; ?> value="4">4:00</option>
                                              <option <?php if($info['end_time'] == 5): ?>selected<?php endif; ?> value="5">5:00</option>
                                              <option <?php if($info['end_time'] == 6): ?>selected<?php endif; ?> value="6">6:00</option>
                                              <option <?php if($info['end_time'] == 7): ?>selected<?php endif; ?> value="7">7:00</option>
                                              <option <?php if($info['end_time'] == 8): ?>selected<?php endif; ?> value="8">8:00</option>
                                              <option <?php if($info['end_time'] == 9): ?>selected<?php endif; ?> value="9">9:00</option>
                                              <option <?php if($info['end_time'] == 10): ?>selected<?php endif; ?> value="10">10:00</option>
                                              <option <?php if($info['end_time'] == 11): ?>selected<?php endif; ?> value="11">11:00</option>
                                              <option <?php if($info['end_time'] == 12): ?>selected<?php endif; ?> value="12">12:00</option>
                                              <option <?php if($info['end_time'] == 13): ?>selected<?php endif; ?> value="13">13:00</option>
                                              <option <?php if($info['end_time'] == 14): ?>selected<?php endif; ?> value="14">14:00</option>
                                              <option <?php if($info['end_time'] == 15): ?>selected<?php endif; ?> value="15">15:00</option>
                                              <option <?php if($info['end_time'] == 16): ?>selected<?php endif; ?> value="16">16:00</option>
                                              <option <?php if($info['end_time'] == 17): ?>selected<?php endif; ?> value="17">17:00</option>
                                              <option <?php if($info['end_time'] == 18): ?>selected<?php endif; ?> value="18">18:00</option>
                                              <option <?php if($info['end_time'] == 19): ?>selected<?php endif; ?> value="19">19:00</option>
                                              <option <?php if($info['end_time'] == 20): ?>selected<?php endif; ?> value="20">20:00</option>
                                              <option <?php if($info['end_time'] == 21): ?>selected<?php endif; ?> value="21">21:00</option>
                                              <option <?php if($info['end_time'] == 22): ?>selected<?php endif; ?> value="22">22:00</option>
                                              <option <?php if($info['end_time'] == 23): ?>selected<?php endif; ?> value="23">23:00</option>
                                          </select>
                                      </div>
                                  </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">单位时间限制：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" name="data[unit_interval]" autocomplete="off" value="<?php echo ($info["unit_interval"]); ?>" placeholder="0.00" class="layui-input">
                                    </div>

                                    <label class="layui-form-label">限制时间单位：</label>
                                    <div class="layui-input-inline">
                                        <select name="data[time_unit]">
                                            <option <?php if($info['time_unit'] == 's'): ?>selected<?php endif; ?> value="s">秒</option>
                                            <option <?php if($info['time_unit'] == 'i'): ?>selected<?php endif; ?> value="i">分</option>
                                            <option <?php if($info['time_unit'] == 'h'): ?>selected<?php endif; ?> value="h">时</option>
                                            <option <?php if($info['time_unit'] == 'd'): ?>selected<?php endif; ?> value="d">天</option>     
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">单位时间次数：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" name="data[unit_number]" autocomplete="off" value="<?php echo ($info["unit_number"]); ?>"
                                               placeholder="" class="layui-input">
                                    </div>
                                    <label class="layui-form-label">单位时间金额：</label>
                                    <div class="layui-input-inline">
                                        <input type="number" min="0" name="data[unit_all_money]" autocomplete="off" value="<?php echo ($info["unit_all_money"]); ?>"
                                               placeholder="" class="layui-input">
                                    </div>
                                </div>
                   
                                <div class="layui-form-item">
                                    <label class="layui-form-label">风控状态：</label>
                                    <div class="layui-input-block">
                                        <input type="radio" name="data[status]" <?php if($info['status'] == 0): ?>checked<?php endif; ?> value="0" title="关闭" checked="">
                                        <input type="radio" name="data[status]" <?php if($info['status'] == 1): ?>checked<?php endif; ?> value="1" title="开通">
                                    </div>
                                </div>
                                <div class="layui-form-item">
                                    <div class="layui-input-block">
                                        <button class="layui-btn" lay-submit="submit" lay-filter="save">提交保存</button>
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
        //监听提交
        form.on('submit(save)', function (data) {
            $.ajax({
                url: "<?php echo U('Transaction/editAddConfig');?>",
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
                            layer.alert(res['msg'], {icon: 2});
                    }
                }
            });
            return false;
        });
    });

</script>
</body>
</html>