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
                    @if ($errors->has('room.roomname'))
                        <p class="roomname__error" style="color:red">{{ $errors->first('room.roomname') }}</p>
                    @endif
                </div>
                <div>
                    <label for="roompass">部屋パスワード</label>
                    <!-- パスワードの桁数はデータベースと相談。requestファイルで指定。 -->
                    <input type="password" name="room[roompass]" id="roompass" value="{{ old('room.roompass') }}" />
                    @if ($errors->has('room.roompass'))
                        <p class="roompass__error" style="color:red">{{ $errors->first('room.roompass') }}</p>
                    @endif
                    @if ($errors->has('error'))
                        <div class="alert alert-danger">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                </div>
                <div>
                    <label for="gamepass">ゲーム内パスワード(※後で設定可能)</label>
                    <input type="password" name="room[gamepass]" id="gamepass"  value="{{ old('room.gamepass') }}" />
                    @if ($errors->has('room.gamepass'))
                        <p class="gamepass__error" style="color:red">{{ $errors->first('room.gamepass') }}</p>
                    @endif
                </div>
                <div>
                    <label for="number_of_winners">抽選人数(※後で設定可能)</label>
                    <input type="number" name="room[number_of_winners]" id="number_of_winners" min="1" max="99" value="{{ old('room.number_of_winners') }}" />
                    @if ($errors->has('room.number_of_winners'))
                        <p class="number_of_winners__error" style="color:red">{{ $errors->first('room.number_of_winners') }}</p>
                    @endif
                </div>
                <div>
                    <label for="max_win">最大当選回数(※後で設定可能)</label>
                    <input type="number" name="room[max_win]" id="max_win" min="1" max="99" value="{{ old('room.max_win') }}" />
                    @if ($errors->has('room.max_win'))
                        <p class="max_win__error" style="color:red">{{ $errors->first('room.max_win') }}</p>
                    @endif
                </div>
                <div><input type="submit" value="作成する" /></div>
            </form>       
        </div>
    </body>
</x-app-layout>
</html>