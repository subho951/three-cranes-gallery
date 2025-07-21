<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $fillable = [
        'main_category',
        'sub_category',
        'name',
        'slug',
        'cover_image',
        'short_description',
        'long_description',
        'product_sku',
        'product_weight',
        'product_weight_unit',
        'related_products',
        'is_feature',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];
}
