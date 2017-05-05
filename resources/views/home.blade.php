@extends('layouts.app')

@section('content')

<link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">

<div class="container">

    <div class="row">


        <div class="col-md-2">
            <div class="list-group">
                <a href="#" class="list-group-item active">价格列表</a>
                <a href="{{url('/buy/ltc')}}" class="list-group-item">LTC交易</a>
                <a href="{{url('/orders')}}" class="list-group-item">订单列表</a>
                <a href="{{url('/notice')}}" class="list-group-item">价格提醒</a>
                <a href="{{url('/config')}}" class="list-group-item">系统设置</a>
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
                            <th>价格</th>
                            <th>幅度</th>
                            <th>通知</th>
                            <th>操作</th>
                            <th>Created At</th>
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
    $(function(){
        $.noConflict();

        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{!! url('/datatables/data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'type', name: 'type' },
                    { data: 'price', name: 'price' },
                    { data: 'amount', name: 'amount' },
                    { data: 'notice_amount', name: 'notice_amount' },
                    { data: 'id', name: 'id' ,render:function(data){
                        return '<a type="button" href="{{url('/notice/test')}}/'+data+'" class="btn btn-xs btn-danger">测试</a>';
                    }},
                    { data: 'created_at', name: 'created_at' }
                ]
            });
        });

    });
</script>

@endsection
