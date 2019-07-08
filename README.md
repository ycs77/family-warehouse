# 楊家倉庫管理

## 安裝

安裝依賴

```
composer install
```

複製環境變數檔

```
cp .env.example .env
```

產生密鑰

```
php artisan key:generate
```

設置初始資料

```
php artisan db:seed --class=InitialData
```
