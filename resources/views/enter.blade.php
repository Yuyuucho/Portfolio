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
        <form action="/enter" method="POST"><!-- このフォームで中間テーブルを生成することになると思う。 -->
            @csrf
            <div>
                <label for="roompass">部屋パスワード</label>
                <!-- パスワードの桁数はデータベースと相談。requestファイルで指定。
                     パスワードの表示/非表示を切り替えるにはJSが必要らしい。要検討
                     usersの設定を参考-->
                <input type="password" name="roompass" id="roompass" placeholder="部屋パスワード" value="{{ old('roompass') }}" />
                <p class="roompass__error" style="color:red">{{ $errors->first('roompass') }}</p>
            </div>
            @if ($errors->has('error'))
                <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                </div>
            @endif
            <div><input type="submit" value="部屋に入る" /></div>
        </form>
        
        
    </div>
    
</body>
</x-app-layout>
</html>