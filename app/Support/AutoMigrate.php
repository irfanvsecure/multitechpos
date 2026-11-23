<?php

namespace App\Support;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

/**
 * Runs pending migrations by itself, so a `git pull` deploy without `php artisan migrate`
 * cannot leave the MCP tools broken ("no such table: upload_links").
 *
 * Cheap on every request: it only compares the list of migration files with the list seen at
 * the last successful run (a small file in storage/framework). Only when a new migration file
 * appears does it take a lock and run `migrate --force` once.
 */
class AutoMigrate
{
    public static function ensure(): void
    {
        if (app()->runningUnitTests() || ! config('app.auto_migrate', true)) {
            return;
        }
        $files = glob(database_path('migrations/*.php')) ?: [];
        $fingerprint = md5(implode('|', array_map('basename', $files)));
        $marker = storage_path('framework/migrations.fingerprint');
        if (is_file($marker) && trim((string) file_get_contents($marker)) === $fingerprint) {
            return;
        }

        $lock = fopen(storage_path('framework/migrations.lock'), 'c');
        if (! $lock || ! flock($lock, LOCK_EX)) {
            return;
        }
        try {
            // another request may have finished it while this one waited for the lock
            if (is_file($marker) && trim((string) file_get_contents($marker)) === $fingerprint) {
                return;
            }
            $code = Artisan::call('migrate', ['--force' => true]);
            if ($code === 0) {
                file_put_contents($marker, $fingerprint);
                Log::info('AutoMigrate: pending migrations applied', ['output' => trim(Artisan::output())]);
            } else {
                Log::error('AutoMigrate: migrate failed', ['output' => trim(Artisan::output())]);
            }
        } catch (\Throwable $e) {
            Log::error('AutoMigrate: '.$e->getMessage());
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }
}
