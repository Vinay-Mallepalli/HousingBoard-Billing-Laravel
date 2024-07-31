<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingData extends Model
{
    use HasFactory;

    protected $table='housing_data';
    
    protected $fillable = [
        'id',
        'housing_name',
        'sheet_data',
    ];

    public function bills()
    {
        return $this->hasMany(HousingBill::class, 'housing_id');
    }
}
