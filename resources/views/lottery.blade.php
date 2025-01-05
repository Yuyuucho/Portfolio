<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<x-app-layout>
<body>
    <h1>当選者</h1>
    <div>
        <ul>
            @foreach ($randomUsers as $randomUser)
                <li>{{ $randomUser->name }}</li>
            @endforeach
        </ul>
    </div>
    
</body>
</x-app-layout>
</html>