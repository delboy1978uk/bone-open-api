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
Create a config array entry/file.
```php
<?php

return [
    'docs' => 'data/docs/api.json',
];
```
Run booty to deploy the front end assets.
```
booty deploy
```
Scan for your API annotations.
```
docs generate
```
Now you can head to `/api/docs` to view your API documentation. 😃
