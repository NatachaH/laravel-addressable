<?php

namespace Nh\Addressable\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddressEvent
{
    use Dispatchable, SerializesModels;

    public $name;
    public $relation;
    public $model;

    /**
     * Create a new event instance.
     * @param string  $name
     * @param \Illuminate\Database\Eloquent\Model  $model
     */
    public function __construct($name,$model)
    {
          $this->name     = $name;
          $this->relation = 'address';
          $this->model    = $model;
    }
}
