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
       接続数 {{ $accessNumber }} 名
    </div>
    <form action="/start/{{ $room->id }}" method="POST">
        @csrf
        @method('PUT')
        <div>
            <label for="gamepass">ゲーム内パスワード</label>
            <input type="password" name="room[gamepass]" id="gamepass" value="{{ $room->gamepass }}"/>
            @if ($errors->has('room.gamepass'))
                <p class="gamepass__error" style="color:red">{{ $errors->first('room.gamepass') }}</p>
            @endif
            
        </div>
        <div>
            <label for="number_of_winners">抽選人数</label>
            <input type="number" name="room[number_of_winners]" id="number_of_winners" min="1" max="99" value="{{ $room->number_of_winners }}" />
            @if ($errors->has('room.number_of_winners'))
                <p class="number_of_winners__error" style="color:red">{{ $errors->first('room.number_of_winners') }}</p>
            @endif
        </div>
        <div>
            <label for="max_win">最大当選回数</label>
            <input type="number" name="room[max_win]" id="max_win" min="1" max="99" value="{{ $room->max_win }}" />
            @if ($errors->has('room.max_win'))
                <p class="max_win__error" style="color:red">{{ $errors->first('room.max_win') }}</p>
            @endif
        </div>
        <div><input type="submit" value="マッチ開始" /></div>
    </form>  
</body>
</x-app-layout>
</html>