<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE:SCHOOL</title>
    <link href="https://fonts.googleapis.com/css?family=DotGothic16&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/lottery.css') }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>
<x-app-layout>
<body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
                            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                            forceTLS: true
        });

        var channel = pusher.subscribe("lottery-room.{{ $room->id }}");

        // ユーザーリストの更新
        channel.bind("App\\Events\\LotteryUpdated", function (data) {
            let winnerList = document.querySelector('.winner-list ul');
            if (!winnerList) return;

            winnerList.innerHTML = '';

            let winners = data.winners || [];
            let addUsers = data.addUsers || [];

            if (winners.length === 0 && addUsers.length === 0) {
                winnerList.innerHTML = `<li>現在、当選者はいません。</li>`;
                return;
            }

            winners.forEach(winner => {
                let listItem = document.createElement('li');
                listItem.setAttribute("data-user-id", winner.id);
                listItem.innerHTML = `
                    <div class="winner">${winner.name}</div>
                    <button type="button" onclick="kickUser(${winner.id})">KICK</button>
                    <button type="button" onclick="banUser(${winner.id})">BAN</button>
                `;
                winnerList.appendChild(listItem);
            });

            if (addUsers.length > 0) {
                let newListItem = document.createElement('li');
                newListItem.innerHTML = `<div class="new">New!!</div>`;
                winnerList.appendChild(newListItem);

                addUsers.forEach(addUser => {
                    let listItem = document.createElement('li');
                    listItem.setAttribute("data-user-id", addUser.id);
                    listItem.innerHTML = `
                        <div class="winner">${addUser.name}</div>
                        <button type="button" onclick="kickUser(${addUser.id})">KICK</button>
                        <button type="button" onclick="banUser(${addUser.id})">BAN</button>
                    `;
                    winnerList.appendChild(listItem);
                });
            }

        });

        // KICK/BANされたらリストから削除
        channel.bind("App\\Events\\KickOrBanUpdated", function (data) {
            let kickedUser = document.querySelector(`li[data-user-id="${data.userId}"]`);
            if (kickedUser) kickedUser.remove();
        });
    });

    function kickUser(userId) {
    if (!confirm('本当に KICK しますか？')) return;

    fetch("{{ route('kick', ['room' => $room->id, 'user' => ':userId']) }}".replace(':userId', userId), {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Content-Type": "application/json"
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text); });
        }
        return response.json();
    })
    .then(data => {
        console.log("✅ KICK成功:", data);
    })
    .catch(error => {
        console.error("❌ KICKエラー:", error);
    });
}

function banUser(userId) {
    if (!confirm('本当に BAN しますか？')) return;

    fetch("{{ route('ban', ['room' => $room->id, 'user' => ':userId']) }}".replace(':userId', userId), {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
            "Content-Type": "application/json"
        }
    }).then(response => response.json())
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text); });
        }
        return response.json();
    })
    .then(data => {
        console.log("✅ BAN成功:", data);
    })
    .catch(error => {
        console.error("❌ BANエラー:", error);
    });
}
</script>
    <div class="body">
        <div class="container">        
            <h1 class="title">当選者</h1>
            

            <form action="?" method="POST" class="form-container">    
                @csrf
                <div class="winner-list">
                    <ul>
                        @if ($randomUsers->isEmpty() && $addUsers->isEmpty())
                            <li>現在、当選者はいません。</li>
                        @else
                            @foreach ($randomUsers as $randomUser)
                                <li>
                                    <div class="winner">{{ $randomUser->name }}</div>
                                    <button type="button" onclick="kickUser({{ $randomUser->id }})">KICK</button>
                                    <button type="button" onclick="banUser({{ $randomUser->id }})">BAN</button>
                                </li>
                            @endforeach

                            @if ($addUsers->isNotEmpty())
                                <li><div class="new">New!!</div></li>
                                @foreach ($addUsers as $addUser)
                                    <li>
                                        <div class="winner">{{ $addUser->name }}</div>
                                        <button type="button" onclick="kickUser({{ $addUser->id }})">KICK</button>
                                        <button type="button" onclick="banUser({{ $addUser->id }})">BAN</button>
                                    </li>
                                @endforeach
                            @endif
                        @endif
                    </ul>
                </div>
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
                <input type="submit" value="もう一度抽選する" class="button" formaction="/lottery/again/{{ $room->id }}" />
                <input type="submit" value="追加抽選する" class="button" formaction="/lottery/add/{{ $room->id }}" onclick="addLottery({{ $room->id }})"/>
            </form>

            <form action="/lottery/{{ $room->id }}" method="post" class="form-contaier">
                @csrf
                @method('DELETE')
                <button type="submit" class="button danger" onclick="deleteRoom({{ $room->id }})">解散する</button>
            </form>
        </div>
    </div>
    <script>
        function addLottery(id) {
            'use strict'
            if (confirm('ゲーム内パスワードの変更はお済ですか？')) {
                document.getElementById(`form_${id}`).submit();
            }
        }
    </script>
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