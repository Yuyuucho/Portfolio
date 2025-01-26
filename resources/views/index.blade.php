<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<x-app-layout>
    <body>
    <div>
    <a href="/create">部屋を建てる</a>
    <a href="/enter">部屋に入る</a>
</div>      
    </body>
</x-app-layout>
</html>