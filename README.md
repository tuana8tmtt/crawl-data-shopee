# Shopee-Crawler && Manage data (developing)
Crawl data from the shopee.vn

## Prepare

- Install packages
```
composer install
```
- Migrate database and seeder
```
php artisan migrate
```
```
php artisan db:seed
```
## Run
- Start server and queue
```
php artisan serve
```
```
php artisan queue:work --timeout=0
```
```
