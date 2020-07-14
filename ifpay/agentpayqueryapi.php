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
        body>.container {
            padding: 30px 15px 0;
        }

        .container .text-muted {
            margin: 20px 0;
        }

        .footer>.container {
            padding-right: 15px;
            padding-left: 15px;
        }

        code {
            font-size: 80%;
        }

        .nav-pills>li {
            font-size: 18px;
            line-height: 2;
        }
    </style>
</head>
<body>
<div class="container">
    <div>
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <ul class="nav nav-pills">
                    <li role="presentation" class="active"><a href="index.html">API文档</a></li>
                    <li role="presentation" class="dropdown"><a
                            class="dropdown-toggle" data-toggle="dropdown" href="#"
                            role="button" aria-haspopup="true" aria-expanded="false"> 接口
                        <span class="caret"></span>
                    </a>
                        <ul class="dropdown-menu">
                            <li><a href="/ifpay/payapi.php">支付接口</a></li>
                            <li><a href="/ifpay/agentpayapi.php">代付接口</a></li>
                            <li><a href="/ifpay/payqueryapi.php">支付查询</a></li>
                            <li><a href="/ifpay/agentpayqueryapi.php">代付查询</a></li>
                            <li><a href="/ifpay/querybalanceapi.php">余额查询</a></li>
                        </ul></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="page-header">
        <h1>代付查询</h1>
    </div>
    <div class="row main">
        <div class="col-sm-8">
            <form class="m-t" method="POST" action="/ifpay/agentpayquery.php">
                <div class="form-group">
                    <label for="baseUri">代付查询服务器地址</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="baseUri"
                               name="baseUri" placeholder=""
                               value="" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">/payment/v1/order/{merchantId}-{orderNo}</span>
                    </div>
                </div>
                <hr />
                <hr />
                <div class="form-group">
                    <label for="batchDate">批次日期</label> <input type="text"
                                                               class="form-control" id="batchDate" name="batchDate"
                                                               placeholder="20171128" value="">
                </div>
                <div class="form-group">
                    <label for="batchNo">批次号</label> <input type="text"
                                                            class="form-control" id="batchNo" name="batchNo"
                                                            placeholder="" value="">
                </div>
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
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