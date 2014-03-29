 <!DOCTYPE html>
<html lang="en">
  <head>
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Facebook integration for Laravel">
    <meta name="author" content="Maks Surguy @msurguy">

    <title>Laravel and Facebook integration</title>

    {{{ stylesheet_link_tag() }}}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    	<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>
    <!--
    <a href="https://github.com/msurguy/laravel-facebook-login" target="_blank"><img style="position: absolute; top: 0; left: 0; border: 0; z-index: 100000;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_darkblue_121621.png" alt="Fork me on GitHub"></a>
    -->
    <div class="container">
      <!-- Static navbar -->
      <div class="navbar navbar-default">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('fb') }}">Facebook Search</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="{{ url('fb') }}">Home</a></li>
          </ul>
          @if(Auth::check())
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Your Profile <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('logout') }}">Logout</a></li>
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
