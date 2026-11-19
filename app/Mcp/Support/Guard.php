<?php

namespace App\Mcp\Support;

use App\Mcp\ToolError;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** Read-before-write: an update must quote the updated_at it read. */
class Guard
{
    public static function stamp(?Model $model): ?string
    {
        return $model?->updated_at?->toIso8601String();
    }

    public static function check(Model $model, ?string $expected): void
    {
        if ($expected === null || $expected === '') {
            throw new ToolError('expected_updated_at is required: read the record first and pass its updated_at.', ['current_updated_at' => self::stamp($model)]);
        }
        try {
            $given = Carbon::parse($expected)->getTimestamp();
        } catch (\Throwable) {
            throw new ToolError('expected_updated_at is not a valid date.');
        }
        if ($model->updated_at && $model->updated_at->getTimestamp() !== $given) {
            throw new ToolError('This record changed since you read it. Read it again and re-apply your change.', [
                'current_updated_at' => self::stamp($model),
                'you_sent' => $expected,
            ]);
        }
    }
}
