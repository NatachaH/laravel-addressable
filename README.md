# Installation

Install the package via composer:

```
composer require nh/addressable
```

Publish the migration file for the addressable:

```
php artisan vendor:publish --tag=addressable
```

To make a model addressable, add the **Addressable** trait to your model:

```
use Nh\Addressable\Traits\Addressable;

use Addressable;
```
