<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accreditation extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'accreditation_name',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
