<!DOCTYPE html>
<html>
<head>
  <title>Dog Video Aggregator</title>
  
  <link href="css/reset.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/main.css" rel="stylesheet">
</head>

<body>

  

<div class="container-fluid"> 
  <div class="row">
    <div class="col-md-12 text-center">
      <h1>All things dog videos</h1>
    </div>
  </div>

  <div class="row">
    <div class="col-md-2"></div>

    <div class="col-md-8">
      <div class="video-player" data-id="nGeKSiCQkPw">
        <iframe class="embed-responsive-item" 
          src="{{ $URL }}" frameborder="0" allowfullscreen>
        </iframe>
      </div>
    </div>

    <div class="col-md-2"></div>
  </div>

  <div class="row">
    <div class="col-md-12 text-center">
      <h2>{{ $Title }}</h2>
    </div>
  </div>

  <div class="row">
    <div class="col-md-5 text-center">
      <!-- Dislike -->
      {!! Form::open(['route' => 'dislikeVideo', 'id' => 'dislike']) !!}
        {!! Form::button('', array('type' => 'submit', 'class' => 'glyphicon glyphicon-thumbs-down')) !!}
      {!! Form::close() !!}
    </div>    
    <div class="col-md-2 text-center">
      Total Likes: <input class="text-center" id="likeCount" value="{{ $likes }}" readonly></input>
    </div>   
    <div class="col-md-5 text-center">
      <!-- like -->
      {!! Form::open(['route' => 'likeVideo', 'id' => 'like']) !!}
        {!! Form::button('', array('type' => 'submit', 'class' => 'glyphicon glyphicon-thumbs-up')) !!}
      {!! Form::close() !!}
    </div>
  </div>
  
   <div class="row">
    <div class="col-md-12 text-center">
      {!! Form::open(['route' => 'getVideo']) !!}
        {!! Form::button('Next Video', array('type' => 'submit', 'class' => ' btn-primary')) !!}
      {!! Form::close() !!}
    </div>
  </div>


  <div class="footer navbar text-center">
    <a href="http://benclayton.me" target='_blank'><i class="fa fa-globe" aria-hidden="true"></i></a>
    Copyright © Ben Clayton | Built with Laravel
    <a href="https://twitter.com/BenjyClay" target='_blank'><i class="fa fa-twitter" aria-hidden="true"></i></a>
  </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}" />

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script>

var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');


var base_url = 'http://localhost'

        $("document").ready(function(){
            $("#like").submit(function(e){
              console.log(CSRF_TOKEN);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url : base_url+'/likeVideo',
                    data : {_token: CSRF_TOKEN},
                    success : function(data){
                      $("#likeCount").val( Number($("#likeCount").val()) + 1 );
                    }
                },"json");

            });

            $("#dislike").submit(function(e){
              console.log(CSRF_TOKEN);
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url : base_url+'/dislikeVideo',
                    data : {_token: CSRF_TOKEN},
                    success : function(data){
                      $("#likeCount").val( Number($("#likeCount").val()) - 1 );
                    }
                },"json");

            });
        });
    </script>

</body>
</html>

