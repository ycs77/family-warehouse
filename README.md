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

遷移資料表

```
php artisan migrate
```

設置初始資料

```
php artisan db:seed --class=InitialData
```

## 開啟快取

```
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 產生 A4 物品 QR code 影印圖片

輸入需產生的物品的ID

```
php artisan warehouse:items-img:a4 1-2,5-7
```

清除站存圖片

```
php artisan warehouse:items-img:clear
```
