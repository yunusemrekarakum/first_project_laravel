<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vue in Laravel</title>
    @vite('resources/css/app.css')
</head>
<body>
<div id="app">
</div>
@vite('resources/js/app.js')
</body>
</html>

