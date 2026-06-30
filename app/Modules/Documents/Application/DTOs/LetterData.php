<?php

declare(strict_types=1);

namespace App\Modules\Documents\Application\DTOs;

final readonly class LetterData
{
    public function __construct(
        public string  $title,
        public string  $content,
        public int     $toUserId,
        public ?string $description,
        public ?string $attachmentPath,
    ) {}
}
