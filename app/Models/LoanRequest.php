<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LoanRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = null;

    protected $casts = [
        'start_at' => 'datetime',
        'expire_at' => 'datetime'
    ];

    protected $appends = [
        'amount_string',
        'interest_string'
    ];

    public function getAmountStringAttribute() {
        return config('app.currency').($this->attributes['amount']);
    }

    public function getInterestStringAttribute() {
        return config('app.currency').($this->attributes['interest']);
    }

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }

    // scope
    public function scopeActive($query) {
        return $query->where('status_id', status_active_id());
    }
    public function scopePending($query){
        return $query->where('status_id', status_pending_id());
    }
}
