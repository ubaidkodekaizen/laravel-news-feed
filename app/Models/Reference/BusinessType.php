<?php

namespace App\Models\Reference;

use Illuminate\Database\Eloquent\Model;

class BusinessType extends Model
{
    
    protected $table = 'bussiness_types';
    
    protected $fillable = ['name'];
}
