<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    public function getIsActiveAttribute() {
        return ($this->attributes['id'] == status_active_id());
    }

    public function getIsCancelledAttribute() {
        return ($this->attributes['id'] == status_cancelled_id());
    }
}
