<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>


    <script src="http://js.pusher.com/3.0/pusher.min.js"></script>
    <script>
        var pusher = new Pusher("5e212c99987a22408668",{cluster:'ap1',encrypted: true})
        var channel = pusher.subscribe('huobi-price');
        channel.bind('App\\Events\\HuobiPrice', function(data) {
            playSound();
            setTimeout(function(){
                alert((data.huobi.type == '1'?'BTC':'LTC') + '当前价格 '+ data.huobi.price+' 幅度'+ data.huobi.amount);
            },1000);
        });
        channel.bind('App\\Events\\PriceNotice', function(data) {
            playSound();
            setTimeout(function(){
                alert((data.notice.type == '1'?'BTC':'LTC') + '价格 '+data.notice.operator+' '+data.notice.price);
            },1000);
        });
        channel.bind('App\\Events\\NewPrice', function(data) {
            console.log(data);
        });

        function playSound()
        {
            var borswer = window.navigator.userAgent.toLowerCase();
            if ( borswer.indexOf( "ie" ) >= 0 )
            {
                //IE内核浏览器
                var strEmbed = '<embed name="embedPlay" src="/js/792.wav" autostart="true" hidden="true" loop="false"></embed>';
                if ( $( "body" ).find( "embed" ).length <= 0 )
                    $( "body" ).append( strEmbed );
                var embed = document.embedPlay;

                //浏览器不支持 audion，则使用 embed 播放
                embed.volume = 100;
                //embed.play();这个不需要
            } else
            {
                //非IE内核浏览器
                var strAudio = "<audio id='audioPlay' src='/js/792.wav' hidden='true'>";
                if ( $( "body" ).find( "audio" ).length <= 0 )
                    $( "body" ).append( strAudio );
                var audio = document.getElementById( "audioPlay" );

                //浏览器支持 audion
                audio.play();
            }
        }

    </script>
</body>
</html>
