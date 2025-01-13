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

    <br>
    <br>

    <form action="/lottery/{{ $room->id }}" method="POST">    
        @csrf
        @method('PUT')
        <p>設定の変更</p>
        <div>            
            <label for="gamepass">ゲーム内パスワードの変更</label>
            <input type="password" name="room[gamepass]" id="gamepass" value="{{ $room->gamepass }}"/>
            @if ($errors->has('room.gamepass'))
                <p class="gamepass__error" style="color:red">{{ $errors->first('room.gamepass') }}</p>
            @endif            
        </div>
        <div>
            <label for="number_of_winners">抽選人数の変更</label>
            <input type="number" name="room[number_of_winners]" id="number_of_winners" min="1" max="99" value="{{ $room->number_of_winners }}" />
            @if ($errors->has('room.number_of_winners'))
                <p class="number_of_winners__error" style="color:red">{{ $errors->first('room.number_of_winners') }}</p>
            @endif
        </div>
        <div>
            <label for="max_win">最大当選回数の変更</label>
            <input type="number" name="room[max_win]" id="max_win" min="1" max="99" value="{{ $room->max_win }}" />
            @if ($errors->has('room.max_win'))
                <p class="max_win__error" style="color:red">{{ $errors->first('room.max_win') }}</p>
            @endif
        </div>      
        <input type="submit" value="もう一度抽選する">
    </form>

    <br>
    <br>

    <form action="/lottery/{{ $room->id }}" method="post">
        @csrf
        @method('DELETE')
        <button type="button" onclick="deleteRoom({{ $room->id }})">解散する</button>
    </form>
    <script>
        function deleteRoom(id) {
            'use strict'

            if (confirm('本当に解散しますか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>    
</body>
</x-app-layout>
</html>