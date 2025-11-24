<!DOCTYPE html>
<html lang="en">
    <head>
        <title>{{ config('app.name', 'SWStarted') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 cursor-default">
        <div id="app"></div>
    </body>
</html>
