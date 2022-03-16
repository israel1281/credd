<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutodebitCharges extends Model
{
    use HasFactory;
    protected $guarded = null;

    public function scopeFailedOnce($query) {
        return $query->where('status_id', status_failed_id())->count() >= 1;
    }

    public function scopeFailedTwice($query) {
        return $query->where('status_id', status_failed_id())->count() >= 2;
    }

    public function scopeFailedMore($query) {
        return $query->where('status_id', status_failed_id())->count() >= 3;
    }
}
