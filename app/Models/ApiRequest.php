<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 */
class ApiRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        "host",
        "endpoint",
        "method",
        "query",
        "body",
        "response_code",
        "response",
    ];
}
