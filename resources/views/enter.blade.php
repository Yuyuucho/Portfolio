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
                <form action="/enter" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="roompass">部屋パスワード</label>
                        <!-- パスワードの桁数はデータベースと相談。requestファイルで指定。
                            パスワードの表示/非表示を切り替えるにはJSが必要らしい。要検討
                            usersの設定を参考-->
                        <input type="password" name="roompass" id="roompass" value="{{ old('roompass') }}" />
                        <p class="roompass__error" style="color:red">{{ $errors->first('roompass') }}</p>
                    </div>
                    @if ($errors->has('error'))
                        <div class="error">
                            {{ $errors->first('error') }}
                        </div>
                    @endif
                    <div class="from-group">
                        <input type="submit" value="部屋に入る" class="button"/>
                    </div>
                </form>              
            </div>
        </div>
    </body>
</x-app-layout>
</html>