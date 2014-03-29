<!DOCTYPE html>
<html lang="en">
    <head>
  	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facebook Serch</title>

        {{{ stylesheet_link_tag() }}}

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
    <div class="container">
        <!-- Static navbar -->
        <div class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ route('facebook.main') }}">Facebook Search</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="{{ route('facebook.main') }}">Home</a></li>
                </ul>
                @if(Auth::check())
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Your Profile <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('facebook.logout') }}">Logout</a></li>
                        </ul>
                    </li>
                </ul>
                @endif
            </div><!--/.nav-collapse -->
        </div>

      <!-- Main component for a primary marketing message or call to action -->

      @yield('content')


    </div> <!-- /container -->

    {{{ javascript_include_tag() }}}

    </body>
</html>
