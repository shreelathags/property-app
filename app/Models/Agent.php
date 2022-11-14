<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "last_name",
        "email",
        "phone",
        "address",
    ];

    public function properties()
    {
        return $this->belongsToMany(Property::class, 'agents_properties')->withPivot("view", "sell");
    }
}
