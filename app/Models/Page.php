<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'banner_image',
        'banner_heading',
        'banner_description',
        'serial_no',
        'slug',
        'meta_title',
        'meta_description',
        'canonical_url',
        'focus_keywords',
        'schema',
        'redirect_301',
        'redirect_302',
        'is_active',
    ];

    protected $casts = [
        'schema' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get the menu associated with the page.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Accessor for banner image URL.
     */
    public function getBannerImageUrlAttribute()
    {
        return $this->banner_image
            ? asset('storage/pages/' . $this->banner_image)
            : asset('no-image.jpg');
    }
}
