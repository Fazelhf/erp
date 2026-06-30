<?php

declare(strict_types=1);

namespace App\Modules\Documents\Domain\Models;

use App\Modules\Documents\Domain\Enums\LetterStatusEnum;
use App\Modules\IAM\Domain\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LetterAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'letter_id',
        'from_user_id',
        'to_user_id',
        'description',
        'status',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'status'  => LetterStatusEnum::class,
            'is_read' => 'boolean',
        ];
    }

    public function letter(): BelongsTo
    {
        return $this->belongsTo(Letter::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
