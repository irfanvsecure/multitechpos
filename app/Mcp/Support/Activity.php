<?php

namespace App\Mcp\Support;

use App\Cms\Cms;
use App\Mcp\Context;
use App\Models\ActivityLog;
use App\Models\SectionRevision;
use Illuminate\Database\Eloquent\Model;

/** Every write: activity log (before/after), optional revision, cache flush. */
class Activity
{
    public static function log(string $action, ?Model $subject, mixed $before, mixed $after, ?string $summary = null, array $urls = []): void
    {
        ActivityLog::query()->create([
            'tool' => Context::$tool,
            'action' => $action,
            'subject_type' => $subject ? class_basename($subject) : null,
            'subject_id' => $subject?->getKey(),
            'summary' => $summary ? mb_strimwidth($summary, 0, 250, '…') : null,
            'before' => self::compact($before),
            'after' => self::compact($after),
            'ip' => request()->ip(),
        ]);
        Cms::flush(self::absolute($urls));
    }

    /** Copy of a section / post before it changes. */
    public static function revision(Model $model, string $reason): void
    {
        SectionRevision::query()->create([
            'revisionable_type' => $model->getMorphClass(),
            'revisionable_id' => $model->getKey(),
            'snapshot' => $model->getAttributes() ? collect($model->getAttributes())->except(['id'])->map(
                fn ($v, $k) => in_array($k, ['attrs', 'embeds', 'options'], true) && is_string($v) ? json_decode($v, true) : $v
            )->all() : [],
            'reason' => $reason,
        ]);
    }

    public static function snapshot(?Model $model): ?array
    {
        if (! $model) {
            return null;
        }

        return collect($model->attributesToArray())->except(['created_at'])->all();
    }

    private static function compact(mixed $value): mixed
    {
        if ($value instanceof Model) {
            $value = self::snapshot($value);
        }

        return $value;
    }

    private static function absolute(array $paths): array
    {
        $root = rtrim((string) config('app.url'), '/');

        return array_values(array_filter(array_map(fn ($p) => $p ? (str_starts_with($p, 'http') ? $p : $root.$p) : null, $paths)));
    }
}
