<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CedulaRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name', 'file_path', 'cedula_request_id'
    ];

    public function cedula() 
    {
        return $this->belongsTo(CedulaRequest::class, 'cedula_request_id', 'id');
    }
}
