<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE:SCHOOL</title>
    <link href="https://fonts.googleapis.com/css?family=DotGothic16&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lottery.css') }}">
</head>
<x-app-layout>
<body>
    <div class="body">
        <div class="container">        
            <h1 class="title">当選者</h1>
            <div class="winner-list">
                <ul>
                    @foreach ($randomUsers as $randomUser)
                        <li>{{ $randomUser->name }}</li>
                    @endforeach
                </ul>
            </div>

            <form action="/lottery/{{ $room->id }}" method="POST" class="form-container">    
                @csrf
                @method('PUT')
                <details class="settings">
                    <summary class="settings-summary">設定の変更</summary>
                    <div class="form-group">            
                        <label for="gamepass">ゲーム内パスワードの変更</label>
                        <input type="password" name="room[gamepass]" id="gamepass" value="{{ $room->gamepass }}"/>
                        @if ($errors->has('room.gamepass'))
                            <p class="error">{{ $errors->first('room.gamepass') }}</p>
                        @endif            
                    </div>
                    <div class="form-group">
                        <label for="number_of_winners">抽選人数の変更</label>
                        <input type="number" name="room[number_of_winners]" id="number_of_winners" min="1" max="99" value="{{ $room->number_of_winners }}" />
                        @if ($errors->has('room.number_of_winners'))
                            <p class="error">{{ $errors->first('room.number_of_winners') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="max_win">最大当選回数の変更</label>
                        <input type="number" name="room[max_win]" id="max_win" min="1" max="99" value="{{ $room->max_win }}" />
                        @if ($errors->has('room.max_win'))
                            <p class="error">{{ $errors->first('room.max_win') }}</p>
                        @endif
                    </div>
                </details>              
                <input type="submit" value="もう一度抽選する" class="button" />
            </form>

            <form action="/lottery/{{ $room->id }}" method="post" class="form-contaier">
                @csrf
                @method('DELETE')
                <button type="submit" class="button danger" onclick="deleteRoom({{ $room->id }})">解散する</button>
            </form>
        </div>
    </div>
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