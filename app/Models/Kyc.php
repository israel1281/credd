<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Kyc extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = null;

    public function scopePending($query) {
        return $query->where('status_id', status_pending_id());
    }

    public function scopeRejected($query) {
        return $query->where('status_id', status_rejected_id());
    }

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function status() {
        return $this->belongsTo(Status::class);
    }
}
