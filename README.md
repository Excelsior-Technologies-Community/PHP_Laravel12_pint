# PHP_Laravel12_pint

## Overview

PHP_Laravel12_pint is a Laravel 12 project created to demonstrate the use of Laravel Pint for automatic PHP code formatting.


## Conclusion

This project demonstrates the best practices for coding standards in Laravel 12 using Laravel Pint. It is ideal for beginners to intermediate developers who want to integrate automatic code formatting into their workflow while learning Laravel fundamentals.



## The project serves as a practice setup to:

Understand Laravel 12 installation and setup

Configure MySQL database connections

Create controllers and test PHP code structure

Install and use Laravel Pint to ensure clean, standardized, and PSR-12 compliant code


## Purpose of the Project

Learn Laravel 12 basics: project creation, database configuration, migrations, and controllers.

Practice PHP coding standards: intentionally add badly formatted code in a controller.

Use Laravel Pint:

Automatically detect coding style issues

Format code to follow PSR-12 and Laravel standards

Understand workflow for code quality in real projects:

Check code (--test)

Fix code (vendor/bin/pint)

Verify changes

This project is perfect for beginners in Laravel and PHP who want to enforce consistent coding standards and learn automatic code formatting tools.



---


# Project SetUp

---



## STEP 1: Create New Laravel 12 Project

### Run Command :

```
composer create-project laravel/laravel PHP_Laravel12_pint "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_pint

```

Make sure Laravel 12 is installed successfully.




## STEP 2: Setup Database

### Open .env

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=php_laravel12_pint
DB_USERNAME=root
DB_PASSWORD=

```

### Create Database

Use your MySQL client, phpMyAdmin, or command line to create a database named `php_laravel12_pint`.



### Then run:

```
php artisan migrate

```



## STEP 3: Install Laravel Pint Manually (IMPORTANT)

### Run this command:

```
composer require laravel/pint --dev

```
 Wait until install completes



## STEP 4: Check Pint is Installed

### Since Laravel 12 does not support php artisan pint, check Pint version using the binary:

```
vendor\bin\pint --version

```

Expected output:

Laravel Pint v1.x.x


## STEP 5: Create Controller (To Test Pint)

### Run :

```
php artisan make:controller DemoController

```

### File created: app/Http/Controllers/DemoController.php

Add badly formatted code:

```

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller{
public function test(){
return "Hello Laravel Pint";
}
}

```

Bad spacing
Wrong brackets
Double quotes

## STEP 6: Check Code Without Fixing

### To check code style without modifying files:

```
vendor\bin\pint --test

```
### Output:

```
  ⨯ app/Http/Controllers/DemoController.php

```

⨯ means there are style issues.


## STEP 7: Run Pint to Fix Code

### Format the entire project:

```
# Windows

vendor\bin\pint


# Linux / Mac

vendor/bin/pint


```

### Output:

```
  ✓ app/Http/Controllers/DemoController.php

```
✓ means file has been formatted.




## STEP 8: Check Code After Fixing

### Check style again (optional):

```
vendor\bin\pint --test

```

### Output:

```
No output

```

note: "No output means code is now correctly formatted."



## STEP 9: See AUTO-FIXED Code (Result)

### Re-open DemoController.php:

```

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DemoController extends Controller
{
    public function test()
    {
        return 'Hello Laravel Pint';
    }
}

```


✔ Proper indentation
✔ PSR-12 standard
✔ Laravel style




## So you can see this type:


### old controller code:


<img width="1899" height="973" alt="Screenshot 2026-01-26 115747" src="https://github.com/user-attachments/assets/387dc656-dbb4-418f-ae49-471095bc49b7" />


### use vendor\bin\pint:


<img width="1915" height="394" alt="Screenshot 2026-01-26 120038" src="https://github.com/user-attachments/assets/ba54f18a-0bb0-46fd-8a91-96c600e42881" />


### then show in your controller code:


<img width="1917" height="1023" alt="Screenshot 2026-01-26 115955" src="https://github.com/user-attachments/assets/685bf766-055f-4370-ba52-b7f510538b89" />



---


# PHP_Laravel12_pint Folder Structure

```

PHP_Laravel12_pint/
│
├── app/
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── DemoController.php        <-- Your test controller
│   │   ├── Middleware/
│   │   └── Kernel.php
│   ├── Models/
│   │   └── User.php
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   └── app.php
│
├── config/
│   └── app.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   └── 2014_10_12_000000_create_users_table.php
│   └── seeders/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       └── welcome.blade.php
│
├── routes/
│   ├── web.php
│   └── api.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   └── Unit/
│
├── vendor/
│   └── bin/
│       └── pint                          <-- Laravel Pint binary
│
├── .env
├── artisan
├── composer.json
├── composer.lock
└── README.md

```
