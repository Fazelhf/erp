<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LetterAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_id', 
        'from_user_id', 
        'to_user_id', 
        'description', 
        'status', 
        'is_read'
    ];

    // رابطه معکوس با نامه (هر ارجاع متعلق به یک نامه است)
    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    // رابطه با کاربر فرستنده (برای اینکه اسمش را نمایش دهیم)
    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}