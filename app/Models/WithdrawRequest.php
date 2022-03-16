<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $guarded = null;

    protected $appends = [
        'amount_string'
    ];

    public function getAmountStringAttribute() {
        return config('app.currency').($this->attributes['amount']);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }
}
