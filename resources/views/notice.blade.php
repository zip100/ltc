@extends('layouts.app')

@section('content')

    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <div class="container">

        <div class="row">


            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{url('/home')}}" class="list-group-item">价格列表</a>
                    <a href="{{url('/buy/ltc')}}" class="list-group-item">LTC交易</a>
                    <a href="{{url('/orders')}}" class="list-group-item">订单列表</a>
                    <a href="{{url('/notice')}}" class="list-group-item active">价格提醒</a>
                </div>
            </div>


            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">


                        <table class="table table-bordered" id="users-table">
                            <thead>
                            <tr>
                                <th colspan="7">
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#myModal">添加</button>
                                </th>
                            </tr>
                            <tr>
                                <th>Id</th>
                                <th>类型</th>
                                <th>条件</th>
                                <th>价格</th>
                                <th>状态</th>
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

    <!-- 模态框（Modal） -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">添加通知</h4>
                </div>
                <div class="modal-body">

                    <form class="form-horizontal" role="form" id="addForm" method="post"
                          action="{{url('/add-notice')}}">
                        {{ csrf_field() }}
                        <fieldset>
                            <div class="form-group">
                                <label for="disabledSelect" class="col-sm-2 control-label">类型</label>
                                <div class="col-sm-10">
                                    <select id="disabledSelect" class="form-control" name="type">
                                        <option value="1">BTC(比特币)</option>
                                        <option value="2">LTC(莱特币)</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="form-group">
                                <label for="disabledSelect" class="col-sm-2 control-label">条件</label>
                                <div class="col-sm-10">
                                    <select id="disabledSelect" class="form-control" name="operator">
                                        <option value=">="> >=</option>
                                        <option value=">"> ></option>
                                        <option value="="> =</option>
                                        <option value="<="> <=</option>
                                        <option value="<"> <</option>
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="ds_host" name="price">价格</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="ds_host" type="text" name="price"/>
                                </div>
                                <label class="col-sm-2 control-label" for="ds_name">手机号码</label>
                                <div class="col-sm-4">
                                    <input class="form-control" id="ds_name" type="text" name="mobile"/>
                                </div>
                            </div>
                        </fieldset>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                    <button type="button" class="btn btn-primary" onclick="$('#addForm').submit();">提交更改</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal -->
    </div>

    <script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>

    <script>
        $(function () {
            $.noConflict();

            $(function () {
                $('#users-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{!! url('/notice-list') !!}',
                    columns: [
                        {data: 'id', name: 'id'},
                        {data: 'type', name: 'type',render: function (data) {
                            return data == '2' ? 'LTC' : 'BTC';
                        }},
                        {data: 'operator', name: 'operator'},
                        {data: 'price', name: 'price'},
                        {data: 'status', name: 'status',render:function(s){
                            switch (s) {
                                case '0':
                                    return '<span class="label label-default">未触发</span>';
                                    break;
                                case '1':
                                    return '<span class="label label-danger">已触发</span>';
                                    break;
                            }

                        }},
                        {data: 'id', name: 'id',render: function (data) {
                            return '<a type="button" href="{{url('/notice/delete')}}/'+data+'" class="btn btn-xs btn-danger">删除</a>';
                        }},
                        {data: 'created_at', name: 'created_at'}
                    ]
                });
            });

        });
    </script>

@endsection
