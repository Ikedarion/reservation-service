# Rese(飲食店予約サービス)
画像

## 作成した目的
外部の飲食店予約サービスは手数料を取られるので自社で予約サービスを持ちたい。

## 機能一覧
- ログイン機能　・予約、決済機能
- お気に入り機能  ・レビュー機能
- 予約編集機能   ・店舗情報作成、編集機能
- ユーザー情報作成、編集機能  ・メール送信機能

## アプリケーションURL
デプロイのURLを貼り付けるログインなどがあれば注意事項など<br>
- 開発環境 : http://localhost/<br>
- phpmyadmin : http://localhost:8080/<br>
AWS
- 開発環境 : http://localhost/<br>
- 本番環境 : http://15.152.239.99/

## リポジトリ
```
 https://github.com/Ikedarion/reservation-service.git
```

## 環境構築
### laravelの環境構築
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
  ```
- 5.アプリケーションキーの作成<br>
  ```
   php artisan migrate
  ```
- 6.マイグレーションの実行<br>
  ```
   php artisan serve
  ```
- 7.シーディングの実行<br>
  ```
   php artisan db:seed --class=UserSeeder
   php artisan db:seed --class=RestaurantSeeder
  ```
**その他**<br>
例) アカウントの種類(テストユーザー)<br>
- メールアドレス : test1@example.com
- パスワード : password1
- 役割 : 管理者

### AWS環境の環境構築
必要な環境
- Docker
- docker-compose
- PHP, MySQL
  
以下のAWSサービスを利用
- Amazon S3（ストレージ）
- Amazon RDS（MySQLデータベース）
- Amazon EC2（バックエンドサーバー）

1. EC2インスタンスへのSSH接続
```
 ssh -i "your-key.pem" ubuntu@your-ec2-public-ip
```

2. Gitリポジトリのクローン
```
 git clone https://github.com/Ikedarion/reservation-service.git
 cd your-project
```
3. .envファイルの作成
cp .env.example .env
.envファイルにAWSの設定やデータベースの設定を記述します。
```
 AWS_ACCESS_KEY_ID=your-access-key
 AWS_SECRET_ACCESS_KEY=your-secret-key
 AWS_DEFAULT_REGION=us-west-2
 AWS_BUCKET_NAME=your-s3-bucket-name
 DB_CONNECTION=mysql
 DB_HOST=your-db-host
 DB_PORT=3306
 DB_DATABASE=your-db-name
 DB_USERNAME=your-db-username
 DB_PASSWORD=your-db-password
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
8. タスクスケジューラーの設定
```
 php artisan schedule:run
 * * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```
9. コンテナのセットアップ
```
 docker-compose up -d --build
```
10. アプリケーションの起動
```
 php artisan serve
```
#### docker-compose.yml
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

## テーブル設計
<img src="https://github.com/user-attachments/assets/fd9eea1e-0418-4df8-82c8-744c74d51344" width="350" height="500"/>

## ER図
<img width="350" height="400" alt="0AE484EB-DB07-4476-AB62-04A8A9087E91" src="https://github.com/user-attachments/assets/19fadd94-dc82-464d-9182-1c335f5308c7" />

## 使用技術(実行環境)
- PHP8.2.25
- mysql8.0.26
- laravel8.83.29
