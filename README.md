# openapi
OpenApi package for Bone Mvc Framework
## installation
Use Composer
```
composer require delboy1978uk/open-api
```
## usage
Simply add to the `config/packages.php`
```php
<?php

// use statements here
use Bone\OpenApi\OpenApi\OpenApiPackage;

return [
    'packages' => [
        // packages here...,
        OpenApiPackage::class,
    ],
    // ...
];
```