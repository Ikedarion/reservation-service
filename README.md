# Rese(飲食店予約サービス)
<img width="450" alt="0940FE97-0F2C-4BEB-AD75-EF2C09D9104A" src="https://github.com/user-attachments/assets/2f4088a2-ecb1-406d-996a-7c41c4ed177e" />

## 作成した目的
外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい。

## 機能一覧
<ul>
  ユーザー
  <li>ログイン機能</li>
  <li>予約、決済機能</li>
  <li>お気に入り機能</li>
  <li>レビュー機能</li>
  店舗代表者
  <li>予約編集、削除機能</li>
  <li>店舗情報作成、編集機能</li>
  管理者
  <li>ユーザー情報作成、編集機能</li>
  <li>メール送信機能</li>
</ul>

## アプリケーションURL
デプロイのURLを貼り付けるログインなどがあれば注意事項など<br>
- 開発環境 : http://localhost/<br>
- phpmyadmin : http://localhost:8080/<br>
##### AWS
- 開発環境 : http://<br>
- 本番環境 : http://

## リポジトリ
```
 https://github.com/Ikedarion/reservation-service.git
```

## 環境構築
### 必要なツール
- Docker
- Docker Compose
- PHP
- MySQL
- AWS アカウント（AWS S3, RDS, EC2 を使用）

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
  
   AWS_ACCESS_KEY_ID=your-access-key
   AWS_SECRET_ACCESS_KEY=your-secret-key
   AWS_DEFAULT_REGION=ap-northeast-1 # 東京リージョンの場合
   AWS_BUCKET_NAME=your-s3-bucket-name
   AWS_URL=https://your-s3-bucket-name.s3.us-west-2.amazonaws.com #画像保存時に使用します

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
   php artisan migrate
  ```
- 6.マイグレーションの実行<br>
  ```
   php artisan serve
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

### AWS環境のセットアップ
1. EC2インスタンスへのSSH接続
```
 ssh -i "your-key.pem" ec2-user@your-ec2-public-ip
```

2. Gitリポジトリのクローン
```
 git clone https://github.com/Ikedarion/reservation-service.git
 cd reservation-service
```
3. .envファイルの作成<br>
cp .env.example .env<br>
.envに以下の環境変数を追加
```
 AWS_ACCESS_KEY_ID=your-access-key
 AWS_SECRET_ACCESS_KEY=your-secret-key
 AWS_DEFAULT_REGION=ap-northeast-1 # 東京リージョンの場合
 AWS_BUCKET_NAME=your-s3-bucket-name
 AWS_URL=https://my-awesome-app-bucket.s3.ap-northeast-1.amazonaws.com #画像保存時に使用します

 DB_CONNECTION=mysql
 DB_HOST=your-rds-endpoint.amazonaws.com 
 DB_PORT=3306
 DB_DATABASE=your-db-name
 DB_USERNAME=your-db-username
 DB_PASSWORD=your-db-password

 本番環境の場合
 APP_ENV=production                             # 本番環境
 APP_DEBUG=false                                # 本番環境ではデバッグを無効
 APP_URL=https://your-production-app-url.com    # アプリケーションのURL (本番環境用)
 LOG_LEVEL=error
```
4. Stripeの設定
```
 STRIPE_KEY=your-public-key-here
 STRIPE_SECRET_KEY=your-secret-key-here
```
5. Composerの依存関係のインストール
```
 composer install
```
6. アプリケーションキーの生成
```
 php artisan key:generate
```
7. データベースのマイグレーション
```
 php artisan migrate
```
8. シーディングの実行（開発用の初期データ)<br>
```
 php artisan db:seed --class=UserSeeder
 php artisan db:seed --class=RestaurantSeeder
```
9. タスクスケジューラーの設定
```
 php artisan schedule:run
 * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```
10. コンテナのセットアップ
```
 docker-compose up -d --build
```
11. アプリケーションの起動
```
 php artisan serve
```
#### docker-compose.yml　
AWS
```
services:
  nginx:
    image: nginx:1.21.1
    container_name: nginx
    ports:
      - "80:80"
    volumes:
      - ./src:/var/www/
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
    networks:
      - mynetwork

  php:
    image: php:8.2-fpm
    container_name: php
    build: ./docker/php
    volumes:
      - ./src:/var/www/
    networks:
      - mynetwork

networks:
  mynetwork:
    driver: bridge
```
#### default.conf
```
server_name localhost; # 自分の開発環境のドメイン名やIPアドレスに変更

```

## テーブル設計
<img src="https://github.com/user-attachments/assets/fd9eea1e-0418-4df8-82c8-744c74d51344" width="350" height="500"/>

## ER図
<img width="400" height="400" src="https://github.com/user-attachments/assets/7879a39c-7ca1-44c4-978a-4b289060573d" />

## 使用技術(実行環境)
- PHP8.2.25
- mysql8.0.26
- laravel8.83.29
