<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingBill extends Model
{
    use HasFactory;

    protected $fillable = [
        'housing_id', 'year', 'yearly_maintenance', 'bill_status', 'status'
    ];

    public function housingData()
    {
        return $this->belongsTo(HousingData::class, 'housing_id');
    }
    
}
