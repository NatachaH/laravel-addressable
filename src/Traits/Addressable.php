<?php
namespace Nh\Addressable\Traits;

use App;
use Illuminate\Database\Eloquent\Builder;

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
              // Add some translations
              if(request()->has('address'))
              {
                  $model->setAddress(request()->address);
              }
          });

          // Before an item is deleted
          static::deleting(function ($model)
          {
              $addresses_to_delete = $model->addresses()->withTrashed()->get();
              $hasSoftDelete = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model));
              $isForceDelete = !$hasSoftDelete || $model->isForceDeleting();

              if($isForceDelete)
              {
                $model->addresses()->forceDelete();
              } else {
                $model->addresses()->delete();
              }
          });

          // Before an item is restored, restore the translations
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
       * Get address for a model.
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
          $this->addresses()->updateOrCreate(
              [
                'addressable_id' => $this->id,
                'addressable_type' => get_class($this),
                'type' => $address['type']
              ],
              [
                'street_1'    => $address['street'],
                'street_2' => $address['street_2'],
                'number'    => $address['number'],
                'zip'       => $address['zip'],
                'city'      => $address['city'],
                'state'     => $address['state'],
                'country'   => $address['country']
              ]
          );

      }

}
