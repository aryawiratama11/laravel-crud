# Module CRUD Generator

This is a extended package for [Laravel-Module](https://nwidart.com/laravel-modules/v6/introduction) Package.
built for rapid development purpose.

## Instalation
```
// Install required package
composer require nwidart/laravel-modules

// then install this packe, NOTE: only support composer v2
composer require wailan/crud  
```
## Syntax
```
php artisan wailan:crud YourModelClassName YourModuleName 
```

## How to use?
```
// Create Module
php artisan module:make Product

// Create Crud for Product
php artisan wailan:crud Product Product

// you can also specify path for your model class
php artisan wailan:crud Api/V1/Product Product
```

## Generated File
- Migration
- Module Entity (Model)
- Module Controller (With CRUD Basic)
- Module Repository (for CRUD Logic)
- Module Request (Store and Update request)
- Module View (Index, Create, Edit, Show)
