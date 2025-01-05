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
    <!-- 部屋名、部屋主名を表示 -->
    @php
        $pivotData = $room->users()->where('users.id', $user->id)->first()?->pivot;
    @endphp
     @if (! $room->is_active)
    <h1>待機中・・・</h1>
    <p>部屋主が開始するのを待っています。</p>
    @else
        @if ($pivotData && $pivotData->is_winner)
        <h1>当選しました。</h1>
        @else
        <h1>落選しました。</h1>
        @endif
    @endif
</body>
</x-app-layout>
</html>