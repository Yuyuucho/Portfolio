<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE:SCHOOL</title>
    <link href="https://fonts.googleapis.com/css?family=DotGothic16&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/start.css') }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>
<x-app-layout>
    <body>
    
        <div class="body">
            <div class="form-container">
                <div class="room-info">
                    <span class="room-name">{{ $room->roomname }}</span>
                    <span class="room-status">を運営中...</span>
                </div>
                <div class="access-count">
                    接続数: <span id="accessNumber">{{ $accessNumber }}</span> 名
                </div>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Pusherのデバッグを有効化
                        Pusher.logToConsole = true;

                        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                            forceTLS: true
                        });

                        var roomId = {{ $room->id }};
                        var channel = pusher.subscribe("lottery-room." + roomId);

                        // イベント名を "UserVisitedPage" に変更
                        channel.bind("App\\Events\\UserVisitedPage", function (data) {
                            console.log("📢 UserVisitedPage イベント受信", data);
                            if (data.accessNumber !== undefined) {
                                document.getElementById("accessNumber").textContent = data.accessNumber;
                            }
                        });
                    });
                </script>
                <form action="/start/{{ $room->id }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="gamepass">ゲーム内パスワード</label>
                        <input type="password" name="room[gamepass]" id="gamepass" value="{{ $room->gamepass }}"/>
                        @if ($errors->has('room.gamepass'))
                            <p class="error">{{ $errors->first('room.gamepass') }}</p>
                        @endif                    
                    </div>
                    <div class="form-group">
                        <label for="number_of_winners">抽選人数</label>
                        <input type="number" name="room[number_of_winners]" id="number_of_winners" min="1" max="99" value="{{ $room->number_of_winners }}" />
                        @if ($errors->has('room.number_of_winners'))
                            <p class="error">{{ $errors->first('room.number_of_winners') }}</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="max_win">最大当選回数</label>
                        <input type="number" name="room[max_win]" id="max_win" min="1" max="99" value="{{ $room->max_win }}" />
                        @if ($errors->has('room.max_win'))
                            <p class="error">{{ $errors->first('room.max_win') }}</p>
                        @endif
                    </div>
                    <div class="from-group">
                        <input type="submit" value="マッチ開始" class="button"/>
                    </div>
                </form> 
            </div>
        </div> 
    </body>
</x-app-layout>
</html>