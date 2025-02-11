<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE:SCHOOL</title>
    <link href="https://fonts.googleapis.com/css?family=DotGothic16&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>
<x-app-layout>
    <body>
        <div class="body">
            <div class="form-container">
                <form action="/create" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="roomname">部屋名</label>
                        <input type="text" name="room[roomname]" id="roomname" value="{{ old('room.roomname') }}" />
                        @if ($errors->has('room.roomname'))
                            <p class="error">{{ $errors->first('room.roomname') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="roompass">部屋パスワード</label>
                        <!-- パスワードの桁数はデータベースと相談。requestファイルで指定。 -->
                        <input type="password" name="room[roompass]" id="roompass" value="{{ old('room.roompass') }}" />
                        @if ($errors->has('room.roompass'))
                            <p class="error">{{ $errors->first('room.roompass') }}</p>
                        @endif
                        @if ($errors->has('error'))
                            <div class="alert alert-danger">
                                {{ $errors->first('error') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="gamepass">ゲーム内パスワード(※後で設定可能)</label>
                        <input type="password" name="room[gamepass]" id="gamepass" placeholder="空白OK" value="{{ old('room.gamepass') }}" />
                        @if ($errors->has('room.gamepass'))
                            <p class="error">{{ $errors->first('room.gamepass') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="number_of_winners">抽選人数(※後で設定可能)</label>
                        <input type="number" name="room[number_of_winners]" id="number_of_winners" min="1" max="99" placeholder="空白OK" value="{{ old('room.number_of_winners') }}" />
                        @if ($errors->has('room.number_of_winners'))
                            <p class="error">{{ $errors->first('room.number_of_winners') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="max_win">最大当選回数(※後で設定可能)</label>
                        <input type="number" name="room[max_win]" id="max_win" min="1" max="99" placeholder="空白OK" value="{{ old('room.max_win') }}" />
                        @if ($errors->has('room.max_win'))
                            <p class="error">{{ $errors->first('room.max_win') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <input type="submit" value="作成する" class="button"/>
                    </div>
                </form>       
            </div>
        </div>
    </body>
</x-app-layout>
</html>