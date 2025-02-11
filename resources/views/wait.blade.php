<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RE:SCHOOL</title>
    <link href="https://fonts.googleapis.com/css?family=DotGothic16&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/wait.css') }}">
</head>
<x-app-layout>    
    <body>
        <div class="body">
            <div class="container">
                <!-- 部屋名、部屋主名を表示 (コントローラーで処理) -->
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
            </div>
        </div>
    </body>
</x-app-layout>
</html>