<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

  <title>Audio Demo1</title>

  <meta name="keywords" content="html5, canvas, experiment">
  <meta name="description" content="An html5 canvas experiment by 9elements.com">

  <script src="/js/processing.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="/js/jquery-1.6.1.min.js" type="text/javascript" charset="utf-8"></script>
  <script src="/js/apt18.js?ver=111228" type="text/javascript" charset="utf-8"></script>

  <style type="text/css" media="screen">
    body {
        background: #000;
        color: #aaa;
        font-family: Helvetica, sans-serif;
		margin:0;
		padding:0;
		overflow:hidden;
    }

	a {
		text-decoration:none;
	}

    #theapt {
    }
    #stuff {
		postion:absolute;
    }
	#tweet {
		font-family:Helvetica, Arial;
		position:absolute;

		top:150px;
		left:260px;

		width:720px;
	}

	#tweet h1 {
		display:block;
		font-family:Helvetica, Arial;
		font-size:40px;
		font-weight:bold;
		margin:0 0 10px 0;
		padding:0;
	}

	#tweet .date {font-size:30px;font-weight:bold}

	#tweet strong {
		display:block;
		font-size:30px;
	}

	#tweet strong img {
		border-radius: 60px;
	}

	#about {
		font-size:11px;
		position:absolute;
		font-weight:normal;
		bottom:20px;
		left:20px;
	}

	#social {
		font-size:11px;
		position:absolute;
		font-weight:normal;
		bottom:13px;
		right:20px;
		width: 120px;
	}

	#about a {
		color: #59cdec;
	}

	#watchlater {
	  font-size:11px;
		position:absolute;
		font-weight:normal;
		bottom:0px;
		left: 50%;
		margin-left: -95px;
		width: 378px;
		height: 85px;
	  line-height: 1.5;

	  -webkit-animation: 'wobble' 5s;
  	-webkit-animation-iteration-count: infinite;
	}

	@-webkit-keyframes 'wobble' {
	  0% { -webkit-transform: translate(0, 0) rotate(0deg) scale(1); }
	  75% { -webkit-transform: translate(0, 0) rotate(0deg) scale(1); }
  	80% { -webkit-transform: translate(0, 0) rotate(2deg) scale(1); }
  	85% { -webkit-transform: translate(0, 0) rotate(-2deg) scale(1.1); }
  	90% { -webkit-transform: translate(0, 0) rotate(2deg) scale(1); }
  	95% { -webkit-transform: translate(0, 0) rotate(-2deg) scale(0.9); }
  	100% { -webkit-transform: translate(0, 0) rotate(0deg) scale(1); }
  }


	#watchlater h6 {
	  font-size: 11px;
	  margin: 0;
	  padding: 20px 0 0 0;
	  text-transform: uppercase;
  }

  #watchlater .watchlater-claim{
    color: #fff;
  }

  #watchlater .watchlater-comment{
    color: #aaa;
  }

	#watchlater a#watchlater-image {
	  float:left;
	  margin-right: 10px;
	}

	#watchlater a#watchlater-image img {
	  border: 0;
  }

	#watchlater a {
	  color: #59cdec;
	}

  </style>
</head>
<body>
<script src="/js/modernizr-0.9.min.js"></script>
<canvas id="theapt" width="100" height="100"></canvas>
<div id="tweet">
	LOADING
</div>
<div id="stuff">
	<audio id="audio" loop>
		Your browser does not support the <code>audio</code> element.
	</audio>
</div>
</body>
</html>
