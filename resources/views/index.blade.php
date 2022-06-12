<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="pusher-key" content="{{ config('broadcasting.connections.pusher.key') }}">
  <link rel="shortcut icon" href="../../public/img/logo.svg">
  <link rel="icon" href="../../public/img/logo.svg">
  <title>{{ config('app.name') }}</title>
  <link rel="stylesheet" href="/css/app.css" type="text/css">
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body>
  <div id="fb-root"></div>
  <script>
    window.fbAsyncInit = function() {
      FB.init({
        xfbml            : true,
        version          : 'v6.0'
      });
    };

    (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/vi_VN/sdk/xfbml.customerchat.js';
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>
  <div class="fb-customerchat"
    attribution=setup_tool
    page_id="110488943885327"
    theme_color="#FF8F00"
    logged_in_greeting="Xin chào, Mình là TravelBot!"
    logged_out_greeting="Xin chào, Mình là TravelBot!">
  </div>
  
  <noscript>
    <strong>We're sorry but laravel-vue doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
  </noscript>
  <div id="app"></div>

  @if (app()->isLocal())
    <script src="{{ mix('js/app.js') }}" defer></script>
  @else
    <script src="{{ mix('js/app.js') }}" defer></script>
    <script src="{{ mix('js/manifest.js') }}" defer></script>
    <script src="{{ mix('js/vendor.js') }}" defer></script>
  @endif
</body>

</html>
