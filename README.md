# Rese(飲食店予約サービス)
<img width="450" alt="0940FE97-0F2C-4BEB-AD75-EF2C09D9104A" src="https://github.com/user-attachments/assets/2f4088a2-ecb1-406d-996a-7c41c4ed177e" />

## 機能一覧
ユーザー<br>
- ログイン機能<br>
- 予約、決済機能<br>
- お気に入り機能<br>
- レビュー機能<br>

店舗代表者<br>
- 予約編集、削除機能<br>
- 店舗情報作成、編集機能<br>

管理者<br>
- ユーザー情報作成、編集機能<br>
- メール送信機能

## アプリケーションURL
- 開発環境 : http://localhost/<br>
- phpmyadmin : http://localhost:8080/<br>

## 環境構築
### ローカル環境のセットアップ
- 1.docker-compose exec php bash
- 2.composer install
- 3.「.env.example」ファイルを 「.env」ファイルに命名を変更。<br>
    または、新しく.envファイルを作成
- 4..envに以下の環境変数を追加
  ```
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass

   #mailtrapを使用する場合
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS=no-reply@example.com
   MAIL_FROM_NAME="${APP_NAME}"
  ```
- 5.アプリケーションキーの作成<br>
  ```
   php artisan key:generate
  ```
- 6.マイグレーションの実行<br>
  ```
   php artisan migrate
  ```
- 7.シーディングの実行（開発用のダミーデータ)<br>
  ```
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=RestaurantSeeder
  ```
- 8.Stripe設定
  ```
   STRIPE_KEY=your-public-key-here
   STRIPE_SECRET_KEY=your-secret-key-here
  ```
- 9.タスクスケジューラーの設定
  ```
   php artisan schedule:run
   * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
  ```
- 10.Dockerコンテナのセットアップ
  ```
   docker-compose up -d --build
  ```
#### その他
例) アカウントの種類(テストユーザー)<br>
- ユーザー メールアドレス: `user@example.com` パスワード: `Password1`
- 店舗代表者 メールアドレス: `test3@example.com` パスワード: `Password1`
- 管理者 メールアドレス: `admin@example.com` パスワード: `Password1`

## テーブル設計
<img src="https://github.com/user-attachments/assets/fd9eea1e-0418-4df8-82c8-744c74d51344" width="350" height="500"/>

## ER図
<img width="400" height="400" src="https://github.com/user-attachments/assets/7879a39c-7ca1-44c4-978a-4b289060573d" />

## 使用技術(実行環境)
- PHP8.2.25
- mysql8.0.26
- laravel8.83.29
