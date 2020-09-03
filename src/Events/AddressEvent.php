<?php

namespace Nh\Addressable\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddressEvent
{
    use Dispatchable, SerializesModels;

    public $name;
    public $model;

    /**
     * Create a new event instance.
     * @param string  $name
     * @param \Illuminate\Database\Eloquent\Model  $model
     */
    public function __construct($name,$model)
    {
          $this->name    = 'address.'.$name;
          $this->model   = $model;
    }
}
