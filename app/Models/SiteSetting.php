<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'logo_path',
        'site_name',
        'slogan',
        'contact_email',
        'contact_phone',
        'contact_phone_2',
        'footer_text'
    ];

    public static function getSettings()
    {
        return self::first();
    }
}
