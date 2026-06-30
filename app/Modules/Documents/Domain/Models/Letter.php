<?php

declare(strict_types=1);

namespace App\Modules\Documents\Domain\Models;

use App\Modules\Documents\Domain\Enums\LetterStatusEnum;
use App\Modules\Documents\Domain\Enums\LetterTypeEnum;
use App\Modules\IAM\Domain\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_no',
        'title',
        'content',
        'sender_id',
        'type',
        'status',
        'attachment',
    ];

    protected function casts(): array
    {
        return [
            'type'   => LetterTypeEnum::class,
            'status' => LetterStatusEnum::class,
        ];
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(LetterAction::class);
    }
}
