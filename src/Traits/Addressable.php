<?php
namespace Nh\Addressable\Traits;

use App;
use Illuminate\Database\Eloquent\Builder;

use Nh\Addressable\Events\AddressEvent;
use App\Models\Address;

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
                AddressEvent::dispatch('force-deleted', $model);
              } else {
                $model->addresses()->delete();
                AddressEvent::dispatch('soft-deleted', $model);
              }
          });

          // Before an item is restored, restore the addresses
          if(method_exists(static::class,'restoring'))
          {
              static::restoring(function ($model)
              {
                  $model->addresses()->withTrashed()->restore();
                  AddressEvent::dispatch('restored', $model);
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
       * Get the default address of the model.
       * @return App\Models\Address
       */
       public function address()
       {
            $type = $this->addressable['default'] ?? null;
            return $this->morphOne(Address::class, 'addressable')->ofMany(['id' => 'max'], function ($query) use ($type) {
                $query->where('type', $type);
            });
       }

       /**
        * Check if the model has some addresses.
        * @param boolean $withTrashed
        * @param string $type
        * @return boolean
        */
       public function hasAddresses($withTrashed = false, $type = null)
       {
           $query = $this->addresses();

           if($withTrashed)
           {
               $query = $query->withTrashed();
           }

           if(!empty($type))
           {
               $query = $query->where('type',$type);
           }

           return $query->exists();
       }

      /**
       * Get all addresses for a model by type.
       * @param string $type
       */
      public function addressesByType($type)
      {
          return $this->addresses->where('type',$type);
      }

      /**
       * Get an address for a model by type.
       * @param string $type
       */
      public function addressByType($type)
      {
          return $this->addressesByType($type)->last();
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
                'id'               => $address['id'] ?? null,
                'addressable_id'   => $this->id,
                'addressable_type' => get_class($this)
              ],
              [
                'type'      => $address['type'] ?? null,
                'street_1'  => $address['street_1'] ?? null,
                'street_2'  => $address['street_2'] ?? null,
                'zip'       => $address['zip'] ?? null,
                'city'      => $address['city'] ?? null,
                'state'     => $address['state'] ?? null,
                'country'   => $address['country'] ?? null
              ]
          );


          // Dispatch the event
          if($address->wasRecentlyCreated)
          {
              AddressEvent::dispatch('created', $this, $address);
          } else if($address->wasChanged()) {
              AddressEvent::dispatch('updated', $this, $address);
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
