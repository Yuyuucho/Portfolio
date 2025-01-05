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
    <h1>{{ $room->roomname }}</h1>
    <div>
       接続数 {} 名
    </div>
    <form action="/start/{{ $room->id }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="gamepass">ゲーム内パスワード</label>
            <input type="password" name="gamepass" id="gamepass" value="{{ $room->gamepass }}"/>
            <p class="gamepass__error" style="color:red">{{ $errors->first('gamepass') }}</p>
        </div>
        <div><input type="submit" value="マッチ開始" /></div>
    </form>  
</body>
</x-app-layout>
</html>