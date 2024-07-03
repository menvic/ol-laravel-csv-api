# [up docker to see - Api docs](http://localhost/docs#user-data-POSTapi-user-data-upload)

--- path to test file - unpack and use: "./storage/app/csv/example_1000000.zip"

## instructions

### 1) duplicate .env.example to .env

### 2) install composer

```bash
composer install
```

### 3) generate key

```bash
php artisan key:generate
```

### 4) up docker

```bash
./vendor/bin/sail up -d
```

### 5) to migrate tables

```bash
./vendor/bin/sail artisan migrate
```

### 6) to generate csv (1000000 is for default)

--- path to generated files: "./storage/app/csv/"

```bash
./vendor/bin/sail artisan csv:generate 9999
./vendor/bin/sail artisan csv:generate
```

### 7) open api docs - <http://localhost/docs#user-data-POSTapi-user-data-upload>

## OPTIONAL

### to seed data if need

```bash
./vendor/bin/sail artisan db:seed --class=UserDataSeeder
```

### to regenerate api docs

```bash
sail artisan scribe:generate
```
