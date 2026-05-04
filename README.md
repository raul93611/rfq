# RFQ System

System to manage RFQs (Request for Quotation).

## Setup

### 1. Configure the application

Copy or edit [app/Bootstrap/config.inc.php](app/Bootstrap/config.inc.php) and set the constants to match your environment:

```php
define('NOMBRE_SERVIDOR', 'localhost');
define('NOMBRE_SERVIDOR_DB', 'database:3306');
define('NOMBRE_USUARIO', 'root');
define('PASSWORD', 'tiger');
define('NOMBRE_BD', 'elogicnewdb');
define('WEBHOOK_AWARD', '');
define('WEBHOOK_FULFILLMENT', '');

define('SERVIDOR', 'http://localhost/rfq/');
```

Update `SERVIDOR` to match your server URL.

### 2. Import the database

### 3. Install PHP dependencies

Enter the webserver container and run Composer:

```bash
docker compose exec webserver bash
composer install
```

### 4. Disable MySQL strict mode

Enter the database container and append the setting to `my.cnf`, then restart:

```bash
docker compose exec database bash
echo 'sql_mode = ""' >> my.cnf
exit
docker compose restart database
```

### 5. Create the temp directory

Create a `/tmp` directory at the project root:

```bash
mkdir -p /var/www/html/rfq/tmp
```

## Production

Enable the `xmlwriter` PHP module on the server.
