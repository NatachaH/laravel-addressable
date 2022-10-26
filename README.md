# Installation

Install the package via composer:

```
composer require nh/addressable
```

Publish the databases and the models for the addresses:

```
php artisan vendor:publish --tag=addressable
```

To make a model addressable, add the **Addressable** trait to your model:

```
use Nh\Addressable\Traits\Addressable;

use Addressable;

/**
 * Default addressable type.
 * @var array
 */
protected $addressable = [
  'type' => null
];

```

# Views

## Add/Update an/some address/es automatic

You can add a single address
*The name must be address['field']*

```
<input type="text" name="address[id]" />
<input type="text" name="address[type]" />
<input type="text" name="address[street_1]" />
<input type="text" name="address[street_2]" />
<input type="text" name="address[zip]" />
<input type="text" name="address[city]" />
<input type="text" name="address[state]" />
<input type="text" name="address[country]" />
```

Or multiple addresses
*The name must be addresses['type']['field']*

```
<input type="text" name="addresses[billing][id]" />
<input type="text" name="addresses[billing][street_1]" />
<input type="text" name="addresses[billing][street_2]" />
<input type="text" name="addresses[billing][zip]" />
<input type="text" name="addresses[billing][city]" />
<input type="text" name="addresses[billing][state]" />
<input type="text" name="addresses[billing][country]" />
```

# Model

## Attributes

You can retrieve all addresses of a model with

```
$user->addresses
```

Or you can retrieve the default address.
*You have to add in the model the default address type*

```
$user->address
```

Or you can retrieve all addresses (or only the last one) by type.

```
$user->addressesByType('billing')
$user->addressByType('billing')
```

And you can retrieve the postal address postal formated.

```
$user->address->postal
```

You can check if a model have some addresses

```
$customer->hasAddresses() // Check if there any addresses
$customer->hasAddresses(false,'default') // Check if there any addresses with type 'default'
$customer->hasAddresses(true) // Check if there any addresses, even in the trash
$customer->hasAddresses(true,'default') // Check if there any addresses with type 'default', even in the trash
```

## Functions

To add **an** address

```
$user->setAddress(['type' => 'billing', 'street_1' => 'My streetname']);
```

To add **multiple** addresses

```
$user->setAddresses([
    'billing' => ['street_1' => 'My streetname for Billing'],
    'shipping' => ['street_1' => 'My streetname for Shipping'],
])
```

# Events

You can use the **AddressEvent** for dispatch events that happen to the addresses.
*You can pass a name, the parent model, the address model (or null) and the number of addresses affected*

```
AddressEvent::dispatch('my-event', $model, $address, 1);
```
