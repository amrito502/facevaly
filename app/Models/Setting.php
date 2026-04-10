<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'default_commission_rate',
    ];

    public static function getSetting()
    {
        return self::first() ?? self::create([]);
    }
}