<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street_1','street_2','zip','city','state','country','type'
    ];

    /**
     * Get the postal address format.
     *
     * @return string
     */
    public function getPostalAddressAttribute()
    {
        $break = "\n";

        $address = $this->street_1.$break;
        $address .= $this->street_2 ? $this->street_2.$break : '';
        $address .= $this->zip.' '.$this->city.$break;
        $address .= $this->country ?? '';

        return nl2br($address);
    }

}
