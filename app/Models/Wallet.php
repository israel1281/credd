<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id', 'acc_no',
        'amt', 'max_amt_transfer',
        'type', 'is_default',
    ];

    protected $appends = [
        'loan_amount_string',
        'amount_string'
    ];

    // Scope
    public function scopeDefault($query) {
        return $query->whereType('main')->where('is_default', true);
    }

    // Accessors & Mutators
    public function getAmountAttribute() {
        return $this->attributes['amt'];
    }
    public function getLoanAmountAttribute() {
        return $this->attributes['loan_amt'];
    }
    public function getAmountStringAttribute() {
        return config('app.currency').($this->attributes['amt']);
    }

    public function getLoanAmountStringAttribute() {
        return config('app.currency').($this->attributes['loan_amt']);
    }

    // Relationship
    public function user() {
        return $this->belongsTo(User::class);
    }
}
