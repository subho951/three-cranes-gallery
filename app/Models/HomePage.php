<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class HomePage extends Model{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sec2_title',
        'sec2_description',
        'sec3_title',
        'sec3_description',
        'sec3_image',
        'sec4_title',
        'sec4_description',
        'sec5_title',
        'sec5_description',
        'sec6_title',
        'sec6_description',
        'sec7_title',
        'sec7_description',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    // ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
        
    // ];
}
