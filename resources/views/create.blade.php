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
        <div>
            <form action="/create" method="POST">
                @csrf
                <div>
                    <label for="roomname">部屋名</label>
                    <input type="text" name="room[roomname]" id="roomname" placeholder="部屋名" value="{{ old('room.roomname') }}" />
                    <p class="roomname__error" style="color:red">{{ $errors->first('room.roomname') }}</p>
                </div>
                <div>
                    <label for="roompass">部屋パスワード</label>
                    <!-- パスワードの桁数はデータベースと相談。requestファイルで指定。 -->
                    <input type="password" name="room[roompass]" id="roompass" value="{{ old('room.roompass') }}" />
                    <p class="roompass__error" style="color:red">{{ $errors->first('room.roompass') }}</p>
                </div>
                <div>
                    <label for="gamepass">ゲーム内パスワード</label>
                    <input type="password" name="room[gamepass]" id="gamepass" placeholder="後で設定可能" value="{{ old('room.gamepass') }}" />
                    <p class="xgamepass__error" style="color:red">{{ $errors->first('room.gamepass') }}</p>
                </div>
                <div><input type="submit" value="作成する" /></div>
            </form>       
        </div>
    </body>
</x-app-layout>
</html>