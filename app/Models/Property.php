<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $details_url = null;

    protected $fillable = [
        "property_id",
        "county",
        "country",
        "town",
        "description",
        "details_url",
        "address",
        "image_url",
        "thumbnail_url",
        "latitude",
        "longitude",
        "no_of_bedrooms",
        "no_of_bathrooms",
        "price",
        "for_sale",
        "for_rent",
        "property_type_id",
        "property_created_at",
        "property_updated_at",
    ];

    public function agents()
    {
        return $this->belongsToMany(Agent::class, 'agents_properties')->withPivot("view", "sell");
    }

    public function property_type()
    {
        return $this->belongsTo(PropertyType::class);
    }
}
