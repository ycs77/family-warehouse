# 玩具倉庫管理

可以像圖書館一樣透過掃 QRCode 來借還物品

用法大概是先登記好物品資料，然後產生物品的 QRCode 並影印出來，貼到物品上，之後只需在借出、還回的時候掃 QRCode 就可以了~

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

產生的 A4 圖片影印完成、並剪裁後，貼至物品上，即可使用網站中的掃描器掃描該 QR code，取得相關資訊。輸出的圖片會存在 `storage/app/print_a4` 資料夾下。輸入需產生的物品的ID：

```
php artisan warehouse:items-img:a4 1-2,5-7
```

忽視暫存圖片，強制重新生成 QR code：

```
php artisan warehouse:items-img:a4 1-2,5-7 --force
```

完全照著輸入的 ID 產生圖片，包括重複的 ID：

```
php artisan warehouse:items-img:a4 1-2,5-7 -r
```

清除暫存圖片：

```
php artisan warehouse:items-img:clear
```

## LICENSE

[![CC0 badge](https://licensebuttons.net/p/zero/1.0/80x15.png)](./LICENSE)
