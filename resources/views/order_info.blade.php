@extends('layouts.app')

@section('content')

    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <div class="container">

        <div class="row">

            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{url('/home')}}" class="list-group-item ">价格列表</a>
                    <a href="{{url('/buy/ltc')}}" class="list-group-item">LTC交易</a>
                    <a href="{{url('/orders')}}" class="list-group-item active">订单列表</a>
                    <a href="{{url('/notice')}}" class="list-group-item">价格提醒</a>
                    <a href="{{url('/config')}}" class="list-group-item">系统设置</a>
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        <form role="form" method="post" action="http://appkit.imwork.net:808/buy/btc"
                              class="form-horizontal"><input type="hidden" name="_token"
                                                             value="jZz4iIbhQO4yQgbR6hIQfkNSBQuCU2UM6rXaAfGD">
                            <fieldset>
                                <legend>买入</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">买入价格</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled="disabled" value="{{$info['buy_price']}}" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">买入数量</label>
                                        <div class="col-sm-3">
                                            <input type="text" disabled="disabled" value="{{$info['buy_amount']}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">买入状态</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled="disabled" value="{{parseStatus($info['buy_status'])}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">最后查询</label>
                                        <div class="col-sm-3">
                                            <input type="text" disabled="disabled" value="{{$info['last_buy_query']}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <legend>卖出</legend>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">卖出价格</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled="disabled" value="{{$info['sell_price']}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">卖出数量</label>
                                        <div class="col-sm-3"><input type="text" disabled="disabled" value="{{$info['sell_amount']}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">卖出状态</label>
                                    <div class="col-sm-3">
                                        <input type="text" disabled="disabled" value="{{parseStatus($info['sell_status'])}}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">最后查询</label>
                                        <div class="col-sm-3"><input type="text" disabled="disabled" value="{{$info['last_sell_query']}}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
