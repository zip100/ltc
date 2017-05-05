@extends('layouts.app')

@section('content')

    <link href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap.min.css" rel="stylesheet">

    <div class="container">

        <div class="row">


            <div class="col-md-2">
                <div class="list-group">
                    <a href="{{url('/home')}}" class="list-group-item ">价格列表</a>
                    <a href="{{url('/buy/ltc')}}" class="list-group-item">LTC交易</a>
                    <a href="{{url('/orders')}}" class="list-group-item">订单列表</a>
                    <a href="{{url('/notice')}}" class="list-group-item">价格提醒</a>
                    <a href="{{url('/config')}}" class="list-group-item active">系统设置</a>
                </div>
            </div>


            <div class="col-md-8">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">

                        <form method="post" action="{{url('/config-save')}}">
                            {{ csrf_field() }}
                            @foreach ($lists as $row)
                                <div class="form-group">
                                    <label class="control-label">{{$row['name']}}</label>
                                    <div class="checkbox">
                                        <label>
                                            <input type="radio" name="{{$row['key']}}" value="1"
                                                   @if ($row['value']==1) checked @endif> 开启
                                        </label>
                                        <label>
                                            <input type="radio" name="{{$row['key']}}" value="0"
                                                   @if ($row['value']==0) checked @endif> 关闭
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-group">
                                <button type="submit" class="btn btn-default">保存</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js"></script>
@endsection
