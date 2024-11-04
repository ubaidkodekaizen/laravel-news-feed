<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductService extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'product_service_name',
        'product_service_description',
        'product_service_area',
        'status'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
