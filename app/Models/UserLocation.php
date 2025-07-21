<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class UserLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'address',
        'country',
        'state',
        'city',
        'locality',
        'street_no',
        'zipcode',
        'lat',
        'lng',
    ];
}
