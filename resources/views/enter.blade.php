<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>
    <div>
        <form action="/enter" method="POST"><!-- このフォームで中間テーブルを生成することになると思う。 -->
            @csrf
            <div>
                <label for="roompass">部屋パスワード</label>
                <!-- パスワードの桁数はデータベースと相談。requestファイルで指定。
                     パスワードの表示/非表示を切り替えるにはJSが必要らしい。要検討-->
                <input type="password" name="room[roompass]" id="roompass" placeholder="部屋パスワード" value="{{ old('room.roompass') }}" />
                <p class="roompass__error" style="color:red">{{ $errors->first('room.roompass') }}</p>
            </div>
            <div><input type="submit" value="部屋に入る" /></div>
        </form>
        
        
    </div>
    
</body>
</html>