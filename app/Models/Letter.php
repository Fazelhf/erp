<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Letter extends Model
{
    use HasFactory;

    // این بخش اجازه می‌دهد این فیلدها در دیتابیس پر شوند
    protected $fillable = [
        'letter_no', 
        'title', 
        'content', 
        'sender_id', 
        'type', 
        'attachment'
    ];

    // رابطه با جدول ارجاعات (هر نامه چندین ارجاع دارد)
    public function actions()
    {
        return $this->hasMany(LetterAction::class);
    }
}