<?php
namespace Nh\Addressable\Traits;

use App;
use Illuminate\Database\Eloquent\Builder;

use Nh\Addressable\Events\AddressEvent;
use Nh\Addressable\Address;

trait Addressable
{

      /**
       * Bootstrap any application services.
       *
       * @return void
       */
      protected static function bootAddressable()
      {

          // After an item is saved
          static::saved(function($model)
          {
              // Add an address
              if(request()->has('address'))
              {
                  $model->setAddress(request()->address);
              }

              // Add some addresses
              if(request()->has('addresses'))
              {
                  $model->setAddresses(request()->addresses);
              }

          });

          // Before an item is deleted
          static::deleting(function ($model)
          {
              $hasSoftDelete = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model));
              $isForceDelete = !$hasSoftDelete || $model->isForceDeleting();

              if($isForceDelete)
              {
                $model->addresses()->forceDelete();
              } else {
                $model->addresses()->delete();
              }
          });

          // Before an item is restored, restore the addresses
          if(method_exists(static::class,'restoring'))
          {
              static::restoring(function ($model)
              {
                  $model->addresses()->withTrashed()->restore();
              });
          }

      }

      /**
       * Get the model record associated with the addresses.
       * @return Illuminate\Database\Eloquent\Collection
       */
      public function addresses()
      {
            return $this->morphMany(Address::class, 'addressable');
      }

      /**
       * Check if the model has some addresses.
       * @param boolean $withTrashed
       * @return boolean
       */
      public function hasAddresses($withTrashed = false)
      {
        return $withTrashed ? $this->addresses()->withTrashed()->exists() : $this->addresses()->exists();
      }

      /**
       * Get an address for a model.
       * @param string $field
       * @param string $lang
       */
      public function address($type = null)
      {
          return $this->addresses()->firstOrNew(['type' => $type]);
      }

      /**
       * Create or update an address for a model.
       * @param array $address
       */
      public function setAddress($address)
      {

          // Update or create the translation
          $address = $this->addresses()->updateOrCreate(
              [
                'addressable_id' => $this->id,
                'addressable_type' => get_class($this),
                'type' => $address['type'] ?? null
              ],
              [
                'street_1'  => $address['street_1'] ?? null,
                'street_2'  => $address['street_2'] ?? null,
                'zip'       => $address['zip'] ?? null,
                'city'      => $address['city'] ?? null,
                'state'     => $address['state'] ?? null,
                'country'   => $address['country'] ?? null
              ]
          );

          // Dispatch the event
          if($address->wasChanged())
          {
              AddressEvent::dispatch('address.updated', $this);
          } else {
              AddressEvent::dispatch('address.created', $this);
          }


      }

      /**
       * Create or update multiple addresses for a model.
       * @param array $address
       */
      public function setAddresses($addresses)
      {

          foreach ($addresses as $key => $address)
          {
              $address['type'] = $key;
              $this->setAddress($address);
          }

      }

}
