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
    public function getPostalAttribute()
    {
        return $this->getPostal("\n");
    }

    /**
     * Get the postal address inline format.
     *
     * @return string
     */
    public function getPostalInlineAttribute()
    {
        return $this->getPostal(", ");
    }

    /**
     * Get the postal address format.
     * @param  string $break
     * @return string
     */
    public function getPostal(string $break = ', ')
    {
        // Set the street 1
        $address = $this->street_1;

        // Set the break if street 2
        if($this->street_1 && $this->street_2){ $address .= $break; }

        // Set the street 2
        $address .= $this->street_2;

        // Set the break if zip or city
        if($this->street_1 && ($this->zip || $this->city)){ $address .= $break; }

        // Set the ZIP
        $address .= $this->zip;

        // Set space if zip and city exists
        if($this->zip && $this->city){ $address .= ' '; }

        // Set the city
        $address .= $this->city;

        return $address ? nl2br($address) : '-';
    }

}
