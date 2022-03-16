<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VtuBillPayment extends Model
{
    use HasFactory;

    const TYPE_CABLE = 'cable';
    const TYPE_AIRTIME = 'airtime';
    const TYPE_ELECTRICITY = 'electricity';

    protected $appends = [
        'amount_string'
    ];
    
    public function getAmountStringAttribute() {
        return config('app.currency').($this->attributes['amount']);
    }
}
