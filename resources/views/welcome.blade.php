<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Scores</title>

        <script type="text/javascript" src="{{ asset('/js/app.js') }}" defer></script>
        <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}" />
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div id="app">

        </div>
    </body>
</html>
