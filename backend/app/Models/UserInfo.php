<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'surname',
        'phone_number',
        'sex',
        'civil_status',
        'citizenship',
        'date_of_birth',
        'place_of_birth',
        'profession_occupation',
        'tax_identification_number',
        'address',
        'baranggay',
        'height',
        'weight',
    ];

    public function account() 
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
