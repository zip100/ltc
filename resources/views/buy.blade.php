@extends('layouts.app')

@section('content')

    <div class="container">

        <div class="row">

            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{url('/home')}}" class="list-group-item ">价格列表</a>
                    <a href="#" class="list-group-item active">LTC交易</a>
                    <a href="{{url('/orders')}}" class="list-group-item">订单列表</a>
                    <a href="{{url('/notice')}}" class="list-group-item">价格提醒</a>
                </div>
            </div>

            <div class="col-md-8">

                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        <form class="form-horizontal" role="form" method="post" action="{{url('/buy/ltc')}}">
                            {{ csrf_field() }}
                            <fieldset>
                                <legend>账户信息</legend>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">总资产折合</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled value="{{$account['total']}}"/>
                                    </div>
                                    <label class="col-sm-3 control-label">净资产折合</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['net_asset']}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">可用人民币</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['available_cny_display']}}"/>
                                    </div>
                                    <label class="col-sm-3 control-label">冻结人民币</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['frozen_cny_display']}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">可用莱特币</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['available_ltc_display']}}"/>
                                    </div>
                                    <label class="col-sm-3 control-label">可用比特币</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['available_btc_display']}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">冻结比特币</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['frozen_btc_display']}}"/>
                                    </div>
                                    <label class="col-sm-3 control-label">冻结莱特币</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['frozen_ltc_display']}}"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">申请比特币数量</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['loan_btc_display']}}"/>
                                    </div>
                                    <label class="col-sm-3 control-label">申请莱特币数量</label>
                                    <div class="col-sm-2">
                                        <input class="form-control" type="text" disabled
                                               value="{{$account['loan_ltc_display']}}"/>
                                    </div>
                                </div>
                            </fieldset>

                            <legend>买卖大盘</legend>
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>price</th>
                                            <th>amount</th>
                                            <th>accu</th>
                                        </tr>
                                        </thead>
                                        <tbody id="buy_list">

                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-6">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>price</th>
                                            <th>amount</th>
                                            <th>accu</th>
                                        </tr>
                                        </thead>
                                        <tbody id="sell_list">

                                        </tbody>
                                    </table>
                                </div>
                            </div>


                            <legend>预买入信息</legend>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">建议价格</label>
                                <div class="col-sm-2">
                                    <input disabled class="form-control pull-right" type="text" name="auto_price"/>
                                </div>

                                <label class="col-sm-2 control-label">全额数量</label>
                                <div class="col-sm-2">
                                    <input disabled class="form-control pull-right" type="text" name="auto_amount"/>
                                </div>
                            </div>

                            <div class="form-group">

                                <label class="col-sm-2 control-label">买入价格</label>
                                <div class="col-sm-2">
                                    <input class="form-control pull-right" type="text" name="buy_price"/>
                                </div>
                                <label class="col-sm-2 control-label">卖出价格</label>
                                <div class="col-sm-2">
                                    <input class="form-control pull-right" type="text" name="sell_price"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2 control-label">购买数量</label>
                                <div class="col-sm-2">
                                    <input class="form-control pull-right" type="text" name="buy_amount"/>
                                </div>
                                <div class="col-sm-2">
                                    <button type="submit" class="btn btn-success pull-right">买入</button>
                                </div>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
    <script>
        var money = {{$account['available_cny_display']}};

        function parseAmount(i) {
            return parseInt(i * 10000) / 10000;
        }

        function select(price) {
            if (confirm('价格:' + price + ' 确认要买入吗?')) {
                $('input[name="buy_price"]').val(price);
                $('input[name="sell_price"]').val(price + 1.5);
            }
        }

        $(function () {
            setInterval(function () {
                $.ajax({
                    url: 'http://api.huobi.com/staticmarket/detail_ltc_json.js',
                    dataType: 'json',
                    success: function (data) {

                        $('input[name="auto_price"]').val(data.p_new);
                        $('input[name="auto_amount"]').val(parseAmount(money / data.p_new));

                        $('#sell_list,#buy_list').html('');

                        for (var i in data.top_buy) {
                            var row = data.top_buy[i];
                            var html = '<tr onclick="select(' + row.price + ')"><td>' + row.price + '</td><td>' + row.amount + '</td><td>' + row.accu + '</td></tr>';
                            $('#buy_list').append(html);
                        }

                        for (var i in data.top_sell) {
                            var row = data.top_sell[i];
                            var html = '<tr onclick="select(' + row.price + ')"><td>' + row.price + '</td><td>' + row.amount + '</td><td>' + row.accu + '</td></tr>';
                            $('#sell_list').append(html);
                        }
                    }
                });
            }, 1000);
        });
    </script>

@endsection
