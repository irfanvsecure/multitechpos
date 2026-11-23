<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UploadLink extends Model
{
    protected $guarded = ['id'];

    protected $casts = ['media_ids' => 'array', 'geo' => 'array', 'replaced' => 'array', 'expires_at' => 'datetime', 'max_files' => 'integer'];

    public static function findByToken(string $token): ?self
    {
        return self::query()->where('token_hash', hash('sha256', $token))->first();
    }

    public function expired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function remaining(): int
    {
        return max(0, $this->max_files - count($this->media_ids ?? []));
    }

    public function status(): string
    {
        if (count($this->media_ids ?? []) > 0 && $this->remaining() === 0) {
            return 'uploaded';
        }
        if ($this->expired()) {
            return count($this->media_ids ?? []) ? 'uploaded' : 'expired';
        }

        return count($this->media_ids ?? []) ? 'partly_uploaded' : 'waiting';
    }
}
