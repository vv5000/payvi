<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">

    <style media="screen">
        /* Sticky footer styles
                -------------------------------------------------- */
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            /* Margin bottom by footer height */
            margin-bottom: 150px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            /* Set the fixed height of the footer here */
            height: 60px;
            background-color: #f5f5f5;
        }

        /* Custom page CSS
                -------------------------------------------------- */
        /* Not required for template or sticky footer method. */
        body > .container {
            padding: 30px 15px 0;
        }

        .container .text-muted {
            margin: 20px 0;
        }

        .footer > .container {
            padding-right: 15px;
            padding-left: 15px;
        }

        code {
            font-size: 80%;
        }

        .nav-pills > li {
            font-size: 18px;
            line-height: 2;
        }
    </style>
    <%
    Date d = new Date();
    SimpleDateFormat sdf = new SimpleDateFormat("yyyyMMdd");
    String da = sdf.format(d);
    %>
</head>
<body>

<div class="container">
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">API Demo</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">Demo 程序<span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="/ifpay/payapi.php">支付接口</a></li>
                            <li><a href="/ifpay/agentpayapi.php">代付接口</a></li>
                            <li><a href="/ifpay/payqueryapi.php">支付查询</a></li>
                            <li><a href="/ifpay/agentpayqueryapi.php">代付查询</a></li>
                            <li><a href="/ifpay/querybalanceapi.php">余额查询</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="page-header">
        <h1>代付提交</h1>
    </div>
    <div class="row main">
        <div class="col-sm-8">
            <form class="m-t" method="post" action="/ifpay/curlpost.php">
                <div class="form-group">
                    <label for="baseUri">代付服务器地址</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="baseUri"
                               name="baseUri" placeholder=""
                               value="" aria-describedby="basic-addon2">
                        <span class="input-group-addon"
                              id="basic-addon2">/agentPay/v1/batch/{merchantId}-{batchNo}</span>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label for="batchAmount">代付金额</label> <input type="text"
                                                                 class="form-control" id="batchAmount"
                                                                 name="batchAmount"
                                                                 value="1">
                </div>
                <hr/>
                <h4>账户明细</h4>
                <div class="form-group">
                    <label for="bankAccount">银行账户</label> <input type="text"
                                                                 class="form-control" id="bankAccount"
                                                                 name="bankAccount"
                                                                 value="6216000000000000000">
                </div>
                <div class="form-group">
                    <label for="bankAccountName">开户名</label> <input type="text"
                                                                class="form-control" id="bankAccountName" name="bankAccountName"
                                                                value="小明">
                </div>
                <div class="form-group">
                    <label for="bank">开户行名称</label>
                    <input type="text" class="form-control" id="bank" name="bank" value="中国工商银行">
                    <label for="bankBranch">分行</label>
                    <input type="text" class="form-control" id="bankBranch" name="bankBranch" value="上海分行">
                    <label for="bankSubBranch">支行</label>
                    <input type="text" class="form-control" id="bankSubBranch" name="bankSubBranch" value="陆家嘴支行">
                </div>
                <div class="form-group">
                    <label for="province">省</label>
                    <input type="text" class="form-control" id="province" name="province" placeholder="上海" value="上海">
                </div>
                <div class="form-group">
                    <label for="city">市</label>
                    <input type="text" class="form-control" id="city" name="city" placeholder="上海" value="上海">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
    <script
            src="http://lib.sinaapp.com/js/jquery/2.2.4/jquery-2.2.4.min.js"></script>
    <script
            src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
</body>
</html>
