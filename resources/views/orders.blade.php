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
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        <table class="table table-bordered" id="users-table">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>类型</th>
                                <th>买入价格</th>
                                <th>买入数量</th>
                                <th>买入状态</th>
                                <th>卖出价格</th>
                                <th>卖出数量</th>
                                <th>卖出状态</th>
                            </tr>
                            </thead>
                        </table>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

    <script>

        function parseStatus(s) {
            switch (s) {
                case '0':
                    return '<span class="label label-default">未成交</span>';
                    break;
                case '1':
                    return '<span class="label label-danger">部分成交</span>';
                    break;
                case '2':
                    return '<span class="label label-success">已完成</span>';
                    break;
                case '3':
                    return '<span class="label label-info">已取消</span>';
                    break;
                case '4':
                    return '废弃';
                    break;
                case '5':
                    return '异常';
                    break;
                case '6':
                    return '<span class="label label-primary">部分成交已取消</span>';
                    break;
                case '7':
                    return '队列中';
                    break;
            }
        }

        $(function () {
            $.noConflict();


            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! url('/orders/list') !!}',
                    columns: [
                        {
                            data: 'id', name: 'id', render: function (data) {
                            return '<a href="{{url('/order/info')}}/' + data + '">#' + data + '</a>';
                        }
                        },
                        {
                            data: 'type', name: 'type', render: function (data) {
                            return data == '2' ? 'LTC' : 'BTC';
                        }
                        },
                        {data: 'buy_price', name: 'buy_price'},
                        {data: 'buy_amount', name: 'buy_amount'},
                        {
                            data: 'buy_status', name: 'buy_status', render: function (s) {
                            return parseStatus(s);
                        }
                        },
                        {data: 'sell_price', name: 'sell_price'},
                        {data: 'sell_amount', name: 'sell_amount'},
                        {
                            data: 'sell_status', name: 'sell_status', render: function (s) {
                            return parseStatus(s);
                        }
                        }
                    ]
                });
            });

        });
    </script>
@endsection
