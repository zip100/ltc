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
                            <th>Created At</th>
                            <th>Updated At</th>
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
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' }
                ]
            });
        });

    });
</script>

<script src="http://js.pusher.com/3.0/pusher.min.js"></script>
<script>
    var pusher = new Pusher("5e212c99987a22408668",{cluster:'ap1',encrypted: true})
    var channel = pusher.subscribe('huobi-price');
    channel.bind('App\\Events\\NewPrice', function(data) {
        console.log(data);

        playSound();
    });

    function playSound()
    {
        var borswer = window.navigator.userAgent.toLowerCase();
        if ( borswer.indexOf( "ie" ) >= 0 )
        {
            //IE内核浏览器
            var strEmbed = '<embed name="embedPlay" src="http://www.gongqinglin.com/accessory/ding.wav" autostart="true" hidden="true" loop="false"></embed>';
            if ( $( "body" ).find( "embed" ).length <= 0 )
                $( "body" ).append( strEmbed );
            var embed = document.embedPlay;

            //浏览器不支持 audion，则使用 embed 播放
            embed.volume = 100;
            //embed.play();这个不需要
        } else
        {
            //非IE内核浏览器
            var strAudio = "<audio id='audioPlay' src='http://www.gongqinglin.com/accessory/ding.wav' hidden='true'>";
            if ( $( "body" ).find( "audio" ).length <= 0 )
                $( "body" ).append( strAudio );
            var audio = document.getElementById( "audioPlay" );

            //浏览器支持 audion
            audio.play();
        }
    }

</script>

@endsection
