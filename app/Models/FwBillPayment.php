<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FwBillPayment extends Model
{
    use HasFactory;

    const TYPE_CABLE = 'cable';
    const TYPE_AIRTIME = 'airtime';
    const TYPE_ELECTRICITY = 'electricity';
}
