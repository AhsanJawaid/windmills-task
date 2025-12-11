<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Laravel App</title>

    {{-- <link href="your-css-path.css" rel="stylesheet"> --}}
</head>
<body>
    <div class="container">
        @yield('content') 
    </div>

    {{-- <script src="your-js-path.js"></script> --}}
</body>
</html>