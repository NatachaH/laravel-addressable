# Installation

Install the package via composer:

```
composer require nh/addressable
```

To make a model addressable, add the **Addressable** trait to your model:

```
use Nh\Addressable\Traits\Addressable;

use Addressable;
```

# Views

## Add/Update an/some address/es automatic

You can add a single address
*The name must be address['field']*

```
<input type="text" name="address['type']" />
<input type="text" name="address['street_1']" />
<input type="text" name="address['street_2']" />
<input type="text" name="address['zip']" />
<input type="text" name="address['city']" />
<input type="text" name="address['state']" />
<input type="text" name="address['country']" />
```

Or multiple addresses
*The name must be addresses['type']['field']*

```
<input type="text" name="addresses['billing']['street_1']" />
<input type="text" name="addresses['billing']['street_2']" />
<input type="text" name="addresses['billing']['zip']" />
<input type="text" name="addresses['billing']['city']" />
<input type="text" name="addresses['billing']['state']" />
<input type="text" name="addresses['billing']['country']" />
```

# Model

## Attributes

You can retrieve an address.
*You can pass the type if there is multiple addresses*

```
$user->address()
$user->address('billing')
```

You can retrieve the postal address formated.

```
$user->address()->formated
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
*This will return an event with the $event->name as address.my-event*


```
AddressEvent::dispatch('my-event', $model);
```

By default the method **$model->setAddress()** will fire the event **address.created** or **address.updated**
