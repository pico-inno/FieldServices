# ERP POS Project (V-2)
 

## Project Setup
```bash
  git clone https://github.com/picoinno/erppos_v2.git
```
```bash
  cd erppos_v2
```

## Database
- Rename `.env.example` file to `.env`inside your project root and fill the database information.

- Run `composer install` or ```php composer.phar install```
- Run `composer dump-autoload`
- Connect your database in .env file

### key generate
```php
  php artisan key:generate
  php artisan migrate --seed
```


##
## Versions

**Laravle:** 10.0,

**Laravle:** 8.2^,

**Composer:** 2.5.5

