<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CedulaRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'request_uuid', 'type','first_name','height','weight','salary','sex','profession_occupation_business','last_name','middle_name','barangay_selection','tax_identification_number','civil_status','citizenship','date_of_birth','place_of_birth','status', 'staff_comment', 'pick_up_date', 'pick_up_time'
    ];

    public function owner() 
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function requirements() 
    {
        return $this->hasMany(CedulaRequirement::class);
    }
}
