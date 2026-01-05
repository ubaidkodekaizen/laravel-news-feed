<?php

namespace App\Models\Content;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Define the table name if different from the plural form of the model
    protected $table = 'events';

    // Define the fillable fields (optional but recommended)
    protected $fillable = ['title', 'time', 'date', 'city', 'venue', 'url', 'image'];
}
