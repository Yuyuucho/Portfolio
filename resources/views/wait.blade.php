<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE:SCHOOL</title>
    <link href="https://fonts.googleapis.com/css?family=DotGothic16&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/wait.css') }}">
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>
<x-app-layout>    
<body>
<script>
        document.addEventListener("DOMContentLoaded", function () {
    var userId = @json(auth()->id()); // ✅ 現在のユーザーID
    var roomId = @json($room->id);    // ✅ 部屋ID

    Pusher.logToConsole = true;

    // Pusherのインスタンスを作成
    var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
        cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
        forceTLS: true
    });

    var channel = pusher.subscribe("lottery-room." + roomId); // ✅ チャンネル名を修正

    // ✅ KickOrBanUpdated イベントを正しくバインド
    channel.bind("App\\Events\\KickOrBanUpdated", function (data) {
        console.log("📢 KickOrBanUpdated イベント受信", data);

        if (data.userId == userId) {
            alert("あなたは KICK されました。");
            window.location.href = "/"; // ✅ ルートページへリダイレクト
        }
    });

          // ✅ 抽選結果の更新イベントを受信
        channel.bind("App\\Events\\LotteryUpdated", function (data) {
            console.log("📢 LotteryUpdated イベント受信", data);

            let winners = data.winners || [];
            let addUsers = data.addUsers || [];
            let statusDiv = document.querySelector('.status');
            let ownerInfoDiv = document.querySelector('.owner-info');

            if (!statusDiv || !ownerInfoDiv) return;

            // 🎯 **まず初期状態をリセット**
            statusDiv.innerHTML = "";
            ownerInfoDiv.innerHTML = "";

            // 🔍 **ユーザーが当選しているかチェック**
            let isWinner = winners.some(winner => winner.id == userId);
            let addWinner = addUsers.some(addUser => addUser.id == userId);

            if (isWinner || addWinner) {
                // 🏆 **当選したユーザーの表示**
                statusDiv.innerHTML = `
                    <div class="win">おめでとうございます！！</div>
                    <div class="win">当選しました。</div>
                    <details class="settings">
                        <summary class="settings-summary">獲得パスワード</summary>
                        <div class="gamepass">{{ $room->gamepass }}</div>
                    </details>
                `;
            } else {
                // ❌ **落選したユーザーの表示**
                console.log(`❌ [userId=${userId}] ユーザーは当選者に含まれていない → 落選画面を表示`);

                setTimeout(() => {
                    statusDiv.innerHTML = `
                        <div class="lose">残念...</div>
                        <div class="lose">落選しました。</div>
                    `;
                    ownerInfoDiv.innerHTML = `
                        <span class="owner-name">{{ $owner->name }}</span>
                        <span class="owner-status">による次の抽選開始をお待ちください。</span>
                    `;
                }, 100); // ✅ DOM更新の遅延
            }
        });

        // ✅ チャンネル情報をログに出力（デバッグ用）
        console.log("🔍 Pusher チャンネル:", channel);
    });

    function leaveRoom(roomId, userId) {
    if (!confirm('本当に退室しますか？')) return;

    // `roomId` と `userId` を持つフォームを取得して送信
    let form = document.querySelector(`form[data-room-id="${roomId}"][data-user-id="${userId}"]`);
    if (form) {
        form.submit();
    } else {
        console.error("フォームが見つかりませんでした");
    }
}
</script>
    </script>

    <div class="body">
        <div class="container">
            @if (! $room->is_active || $enterTiming)
                <div class="status">待機中...</div>
                <div class="owner-info">
                    <span class="owner-name">{{ $owner->name }}</span>
                    <span class="owner-status">の抽選開始を待っています。</span>
                </div>                
            @else
                @if ($pivotData && $pivotData->is_winner)
                <div class="status">
                    <div class="win">おめでとうございます！！</div>
                    <div class="win">当選しました。</div>
                    <details class="settings">
                        <summary class="settings-summary">獲得パスワード</summary>
                        <div class="gamepass">{{ $room->gamepass }}</div>
                    </details>
                </div>                    
                @else
                <div class="status">
                    <div class="lose">残念...</div>
                    <div class="lose">落選しました。</div>
                </div>
                <div class="owner-info">                        
                    <span class="owner-name">{{ $owner->name }}</span>
                    <span class="owner-status">による次の抽選開始をお待ちください。</span>
                </div>
                @endif
            @endif
            <form action="{{ route('leaveRoom', ['room' => $room->id, 'user' => $user->id]) }}" method="POST" class="form-container">
                @csrf
                <input type="submit" class="button" value="退室する" onclick="leaveRoom({{ $room->id }}, {{ $user->id }})"/>
            </form>
        </div>
    </div>
</body>

</x-app-layout>
</html>