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
    <!-- 部屋名、部屋主名を表示 (コントローラーで処理) -->
    @if (! $room->is_active || $enterTiming)
    <h1>待機中・・・</h1>
    <p>{{ $owner->name }}の抽選開始を待っています。</p>
    @else
        @if ($pivotData && $pivotData->is_winner)
        <h1>当選しました。</h1>
        <h1>{{ $room->gamepass }}</h1>
        @else
        <h1>落選しました。{{ $owner->name }}による次の抽選開始をお待ちください。</h1>
        @endif
    @endif
</body>
</x-app-layout>
</html>