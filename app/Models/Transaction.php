<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = null;

    protected $appends = [
        'amount_string'
    ];

    const CREDIT = 'credit';
    const DEBIT = 'debit';
    const DEPOSIT = 'deposit';
    const WITHDRAW = 'withdraw';
    const LOAN_REQUEST = 'loan request';
    const LOAN_DEBIT = 'loan debit';
    const LOAN_CREDIT = 'loan credit';

    // Accessors & Mutators
    public function getAmountAttribute() {
        return $this->attributes['amt'];
    }
    public function getAmountStringAttribute() {
        return config('app.currency').($this->attributes['amt']);
    }

    // Scopes
    public function scopePending($query) {
        return $query->where('status_id', status_pending_id());
    }

    // Relationships
    public function status() {
        return $this->belongsTo(Status::class);
    }
}
