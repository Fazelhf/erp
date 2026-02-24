<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HrRequest extends Model {
    protected $fillable = ['user_id', 'type', 'start_date', 'end_date', 'reason', 'status', 'manager_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
