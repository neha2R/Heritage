<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Heritage</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script src="{{ asset('js/app.js') }}"></script>
        @if(session()->has('welcome'))
            <div class="alert alert-dismissable alert-success">
               <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button> <strong>
               {!! session()->get('welcome') !!}
               <script>
               console.log('call');
               </script>
               </strong>
            </div>
            @endif
        <script type="text/javascript">
 var to_user_id ={{ auth()->id() }}
 //let channelId='App.Models.User.${Echo.socketId()}';
///Echo.private('App.Models.User.${Echo.socketId()}')

 
Echo.private('App.Models.User.{{ auth()->id() }}')
      .notification((notification) => {
            console.log(notification.message);
     });
//                         $( document ).ready(function() {

//             var to_user_id ={{ auth()->id() }}
//            // console.log(to_user_id);

// Echo.private('events')
//  .listen('.getmessages', (e) => {
// console.log(to_user_id);
//     if(to_user_id==e.chat.to_user_id )
//     {
//   console.log('RealTimeMessage: ' + JSON.stringify(e.chat));}
// });
                       
// const app = new Vue({
//       el: '#app',
     
//       mounted() {
//         @if(!empty($id))

// Echo.private('events.1')
//  .listen('RealTimeMessage', (e) =>  console.log('RealTimeMessage: ' + e.message));    @endif   }

//     })
 // Echo.private('events.1')
 //.listen('RealTimeMessage', (e) => console.log('RealTimeMessage: ' + e.message));
</script>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <!-- <a href="{{ route('register') }}">Register</a> -->
                        @endif
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                Heritage
                </div>


            </div>
        </div>
    </body>
</html>
