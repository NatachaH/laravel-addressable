<?php

namespace Nh\Addressable\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddressEvent
{
    use Dispatchable, SerializesModels;

    public $name;
    public $address;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($name, $address)
    {
        $this->name    = $name;
        $this->address = $address;
    }
}
