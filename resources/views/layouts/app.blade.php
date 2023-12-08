<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <title>@yield('title', 'Auth app')</title>
</head>
<body>

<div class="wrapper">

    @yield('content')

    <footer class="footer">
        <div class="footer__container">
            <div class="footer__body">
                <a href="/" class="footer__logo header__logo">php<span>laravel</span></a>
                <div class="footer__wrap">
                    <div class="footer__info d-flex">
                        <a href="#" class="footer__link me-2">Link_1</a>
                        <a href="#" class="footer__link me-2">Link_2</a>
                        <a href="#" class="footer__link me-2">Link_3</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    b
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
<script src="{{ asset('js/app.min.js' )}}"></script>
</body>
</html>
