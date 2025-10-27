<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // الحقول المسموح بتحديثها عبر Mass Assignment
    protected $fillable = [
        'title',
        'slug',
        'body',
        'status',
        'image',
        'featured',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'other',
    ];

    // العلاقة مع الخدمات
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
