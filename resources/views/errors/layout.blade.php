{{-- Self-contained error page (no database access, so it works even when the app is broken). --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | ISOC Jamaica</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400..800&family=Geologica:wght@500..700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendors/fontawesome/css/all.min.css') }}">
    <style>
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; font-family: Manrope, sans-serif; color: #4a5563; padding: 24px;
            background: radial-gradient(circle at 85% 15%, rgba(29,105,211,.5), transparent 45%), radial-gradient(circle at 10% 90%, rgba(238,202,74,.18), transparent 40%), #0c1c2c; }
        .box { background: #fff; border-radius: 24px; padding: 48px 40px; max-width: 520px; width: 100%; text-align: center; box-shadow: 0 30px 60px rgba(0,0,0,.3); }
        .code { font-family: Geologica, sans-serif; font-size: 72px; font-weight: 700; color: #1d69d3; line-height: 1; margin: 18px 0 8px; }
        h1 { font-family: Geologica, sans-serif; color: #0c1c2c; font-size: 26px; margin: 0 0 12px; }
        p { margin: 0 0 28px; line-height: 1.6; }
        a.btn { display: inline-block; background: #1d69d3; color: #fff; text-decoration: none; padding: 13px 30px; border-radius: 999px; font-weight: 700; margin: 4px; }
        a.btn--light { background: #eff2ec; color: #0c1c2c; }
    </style>
</head>
<body>
    <main class="box">
        <a href="{{ url('/') }}"><img src="{{ asset('assets/images/isoc-jamaica-logo.png') }}" alt="ISOC Jamaica home" height="52"></a>
        <div class="code">@yield('code')</div>
        <h1>@yield('title')</h1>
        <p>@yield('message')</p>
        <a href="{{ url('/') }}" class="btn">Go to homepage</a>
        <a href="{{ url('/contact') }}" class="btn btn--light">Contact us</a>
    </main>
</body>
</html>
