# How to deploy

## Config .env
+ Add information of server:
```bash
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
+ Add information of admin user:

```bash
ADMIN_EMAIL=
ADMIN_PASSWORD=
```

## Run command

```bash
composer install
```
```bash
php artisan migrate
```

```bash
php artisan db:seed
```
```bash
php artisan optimize
```
```bash
php artisan config:cache
```
```bash
php artisan route:cache
```
## Struct Backend
```bash
app/
|   Domains/
        |   Auth/
            |   Actions/
            |   DTO/
            |   Exceptions/
|   Http/
        | Controllers/
|   Livewire/
    |   Auth/
bootstrap/
config/
database/
public/
resources/
routes/
storage
tests/
.env
composer.json
...
```