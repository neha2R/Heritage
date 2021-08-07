<!DOCTYPE html>
<html lang="en">
<head>
  <title>Heritage FAQs</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <style>
    /* Remove the navbar's default margin-bottom and rounded borders */ 
    .navbar {
      margin-bottom: 0;
      border-radius: 0;
    }
    
    /* Add a gray background color and some padding to the footer */
    footer {
      background-color: #f2f2f2;
      padding: 25px;
    }

  
.wrapper{
  width:70%;
}
@media(max-width:992px){
 .wrapper{
  width:100%;
} 
}
.panel-heading {
  padding: 0;
	border:0;
}
.panel-title>a, .panel-title>a:active{
	display:block;
	padding:15px;
  color:#555;
  font-size:16px;
  font-weight:bold;
	text-transform:uppercase;
	letter-spacing:1px;
  word-spacing:3px;
	text-decoration:none;
}
.panel-heading  a:before {
   font-family: 'Glyphicons Halflings';
   content: "\e114";
   float: right;
   transition: all 0.5s;
}
.panel-heading.active a:before {
	-webkit-transform: rotate(180deg);
	-moz-transform: rotate(180deg);
	transform: rotate(180deg);
} 
  </style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button> -->
      <a class="navbar-brand" href="#">Heritage FAQs</a>
    </div>
    <!-- <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Gallery</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
      </ul>
    </div> -->
  </div>
</nav>

<div class="jumbotron ">
  <div class="container text-center">
    <h1 >Heritage FAQs</h1>      
    <!-- <p>Some text that represents "Me"...</p> -->
  </div>
</div>
  
<div class="container">
<div class="wrapper center-block">
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      @php $i=1;@endphp
@foreach($faqs as $faq)
  <div class="panel panel-default">
    <div class="panel-heading {{$i==1?'active':''}}" role="tab" id="heading{{$i}}">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{$i}}" aria-expanded="{{$i==1?'true':'false'}}" aria-controls="collapse{{$i}}">
          {{$faq->title}}
        </a>
      </h4>
    </div>
    <div id="collapse{{$i}}" class="panel-collapse collapse {{$i==1?'in':''}} " role="tabpanel" aria-labelledby="heading{{$i}}">
      <div class="panel-body">
          {{$faq->content}}
      </div>
    </div>
  </div>
  @php  $i++; @endphp
 @endforeach
</div>
</div>
</div>
</body>
<script>
     $('.panel-collapse').on('show.bs.collapse', function () {
    $(this).siblings('.panel-heading').addClass('active');
  });

  $('.panel-collapse').on('hide.bs.collapse', function () {
    $(this).siblings('.panel-heading').removeClass('active');
  });
    </script>
</html>
