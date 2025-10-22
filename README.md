# openapi
OpenApi package for Bone Framework
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
Create a config array entry/file. You can add a client so the docs page can authorize and test your endpoints.
```php
<?php

return [
    'docs' => 'data/docs/api.json',
    'swaggerClient' => [
        'clientId' => '',
        'clientSecret' => '',
    ],
];

```
Run booty to deploy the front end assets.
```
vendor/bin/bone assets:deploy
```
Scan for your API annotations.
```
vendor/bin/bone docs:generate
```
Now you can head to `/api/docs` to view your API documentation. 😃
