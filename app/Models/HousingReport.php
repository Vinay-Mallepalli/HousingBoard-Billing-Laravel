<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HousingReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'housing_bills_id',
        'year',
        'mobile_number',
        'resident_name',
        'flat_number',
        'amount_paid',
        'payment_mode',
        'receipt_status',
        'receipt_sent_at'
    ];

    public function housingBill()
    {
        return $this->belongsTo(HousingBill::class);
    }
}

