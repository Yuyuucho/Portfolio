## RE:SCHOOL
#### 視聴者参加型配信をスムーズに進行するための抽選・パスワード配布アプリです。

#### 概要

#### このアプリは、複数のユーザーが部屋に参加し、ランダムに当選者を決定してパスワードを配布する抽選システムです。部屋の作成、参加、抽選の実施、追加抽選、ユーザーのキック・BAN などの機能を提供します。

#### アプリのURL

#### URL:https://re-5chool.com/

#### アプリの目的

#### このアプリは、視聴者参加型ゲーム配信のスムーズな進行を補助する目的で作成しました。<br>
・荒らしによって部屋の立て直しが発生し、進行が遅れる事。<br>
・部屋の立て直しによって一度入れたのにゲームをプレイできなかったという体験をする可能性があること。<br>
・通信環境の差により、早いもの勝ちのルールに不公平感があること。<br>
上記の問題を解決するために、ゲーム外のアプリで参加者を抽選し当選者にパスワードを配るアプリを作りました。

## 使用技術

| Category | Technology Stack |
| -------------------- | ---------- |
| Framework            | Laravel 10          |
| Frontend             | Blade, JavaScript   |
| Database             | MySQL               |
| Real-time processing | LaravelEcho, Pusher |

## 主な機能

#### ・ユーザー登録 & 認証 (Laravel Breeze使用)<br>
・部屋の作成 & 参加<br>
・ランダム抽選<br>
・追加抽選 (kick されたユーザーや BAN されたユーザーの枠を補充)<br>
・ユーザーの Kick / BAN<br>
・WebSocket (Pusher) によるリアルタイム更新

## テーブル設計

### roomsテーブル

| カラム名        | データ型           | NULL許可 | デフォルト        | 説明                         |
|--------------|----------------|------|-------------|----------------|
| id           | BIGINT         | NO   | AUTO_INCREMENT | 主キー         |
| roomname     | VARCHAR(255)   | NO   |              | ルーム名        |
| roompass     | VARCHAR(255)   | NO   |              | ルームパスワード |
| gamepass     | VARCHAR(255)   | NO   |              | ゲーム用パスワード |
| number_of_winners | INT      | NO   |              | 当選人数        |
| max_win      | INT            | NO   | 1            | 最大当選回数    |
| is_active    | BOOLEAN        | YES  | 0            | ルームの稼働状態 |
| created_at   | TIMESTAMP      | YES  |              | 作成日時        |
| updated_at   | TIMESTAMP      | YES  |              | 更新日時        |
| deleted_at   | TIMESTAMP      | YES  |              | ソフトデリート用 |

### usersテーブル（Laravel標準）

| カラム名      | データ型           | NULL許可 | デフォルト | 説明               |
|-----------|----------------|------|------|--------------|
| id        | BIGINT         | NO   | AUTO_INCREMENT | 主キー |
| name      | VARCHAR(255)   | NO   |      | ユーザー名 |
| email     | VARCHAR(255)   | NO   |      | メールアドレス |
| email_verified_at | TIMESTAMP | YES |  | メール確認日時 |
| password  | VARCHAR(255)   | NO   |      | パスワード |
| remember_token | VARCHAR(100) | YES |  | リメンバートークン |
| created_at| TIMESTAMP      | YES  |      | 作成日時 |
| updated_at| TIMESTAMP      | YES  |      | 更新日時 |

### room_userテーブル（中間テーブル）

| カラム名        | データ型           | NULL許可 | デフォルト | 説明                       |
|--------------|----------------|------|------|----------------|
| room_id      | BIGINT         | NO   |      | roomsテーブルの外部キー |
| user_id      | BIGINT         | NO   |      | usersテーブルの外部キー |
| is_owner     | BOOLEAN        | NO   | 0    | オーナーフラグ |
| is_winner    | BOOLEAN        | NO   | 0    | 当選フラグ |
| status       | VARCHAR(50)    | YES  |      | kicked/banned/addedなどの状態 |
| enter_timing | TIMESTAMP      | YES  |      | ルーム参加時刻 |
| win_count    | INT            | YES  | 0    | 当選回数 |
| created_at   | TIMESTAMP      | YES  |      | 作成日時 |
| updated_at   | TIMESTAMP      | YES  |      | 更新日時 |

## 必要環境

| 項目 | バージョン |
|---|---|
| PHP | 8.1以上 |
| Laravel | 10 |
| MySQL | 8.0以上 |
| Composer | 2.x |
| Node.js | 18以上 (npm対応) |
| Pusherアカウント | 必須（リアルタイム処理に必要） |

## インストール手順（ローカル環境）

### 1. リポジトリのクローン
```sh
git clone <リポジトリのURL>
cd <プロジェクトフォルダ名>
```

### 2. 環境設定ファイルの作成
```sh
cp .env.example .env
```

### 3. 必要パッケージのインストール
```sh
composer install
npm install
```

### 4. アプリケーションキーの生成
```sh
php artisan key:generate
```

### 5. 環境設定（.env）の編集
以下を適宜設定してください。

```sh
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lottery_app
DB_USERNAME=root
DB_PASSWORD=password

BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-app-key
PUSHER_APP_SECRET=your-pusher-app-secret
PUSHER_APP_CLUSTER=your-pusher-cluster
```

### 6. マイグレーション＆シーディング
```sh
php artisan migrate --seed
```

### 7. フロントエンドビルド
```sh
npm run build
```

### 8. サーバー起動
```sh
php artisan serve
```

### 9. Pusher設定（ローカル環境の場合）
Pusherのダッシュボードで「App Keys」を確認し、.envに反映してください。

### 今後の展望
#### まだ実装したいけどできていない機能や、不完全な部分も多くあるので日々改善していきたい。<br>
・オーナーが部屋を解散したときの参加者側の処理<br>
・user nameとは別に、ゲーム内と同じ名前を設定できるgame nameカラムの追加。<br>
・アクティブ状態の判断の明確化

#### アプリの命名理由
#### アプリ名はこのアプリを作るきっかけになった、私がよく見る配信者が視聴者参加型配信の時に荒らしにブチギレていたため、"RAGE"という単語は入れたいと思っていました。 <br>
また、部屋のことをアプリ内で"room"としていたことから学校の教室を連想しました。 <br>
そこで<br>
RAGE 〇〇 SCHOOL
という名前にしようと考え、その結果、小学校を意味する＋入るを意味するENTRYと若干にている "ELEMENTARY" という単語を採用し、
頭文字をとってRE:SCHOOLとしました。
